<?php

/**
 * Description of SearchBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors\user;

use yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\components\helper\Helper;
use app\models\User;
use app\components\extend\Url;
use app\components\extend\Html;

class UserReferralBehavior extends \yii\base\Behavior
{

    public $countReferrals;
    public $usersersThatHavePaid = [];

    /**
     * events
     * @return array
     */
    public function events()
    {
        return [
            User::EVENT_PAY_TO_REFERRALS => 'payToReferrals',
            User::EVENT_APPLY_BONUS => 'applyBonus',
        ];
    }

    /**
     * applyBonus
     */
    public function applyBonus()
    {
//        $this->owner->balance = 2000;
        /* TODO #PS: save referal logic behavior */
        $subscribePrice = $this->owner->getSetting('subscribe_price');
        $this->owner->balance = (int) $subscribePrice;
        $subscribe_admin_bonus = (int) $this->owner->getSetting('subscribe_admin_bonus');
        $adminId = (int) $this->owner->getSetting('admin_payment_receiver_id', null);
        if ($this->owner->referral > 0) {
            $transaction = yii::$app->db->beginTransaction();
            $this->owner->transferMoneyToUser($adminId, $subscribe_admin_bonus, false);
            $this->payBonuses($this->owner, $this->owner->referral, ($subscribePrice - $subscribe_admin_bonus), $adminId);
            $transaction->commit();
        } else {
            $this->owner->transferMoneyToUser($adminId, $subscribePrice);
        }
    }

    /**
     * pay to my referrals
     * @param User $paier
     * @param integer $referralID
     * @param integer $totalAmmount
     * @param integer $adminId
     * @param integer $iteration
     * @return boolean
     */
    public function payBonuses($paier, $referralID, $totalAmmount, $adminId, $iteration = 1)
    {
        $payReferrals = (int) $this->owner->getSetting('subscribe_referral_bonus');
        $user = User::findById($referralID);
        if (($totalAmmount >= $payReferrals) && ($user) && ($iteration <= 7)) {
            $iteration++;
            if ($paier->transferMoneyToUser($user->primaryKey, $payReferrals, false)) {
                $totalAmmount = $totalAmmount - $payReferrals;
            }
            return $paier->payToMyReferral($paier, $user->referral, $totalAmmount, $adminId, $iteration);
        } else {
            $paier->transferMoneyToUser($adminId, $totalAmmount, false);
        }
    }

    /**
     * pay to the referrals
     */
    public function payToReferrals()
    {
//        $this->owner->balance = 2000;
        /* TODO #PS: save referal logic behavior */
        $paimentAmountOnSignUp = $this->owner->getSetting('pay_on_signup_amount');
        $this->owner->balance = (int) $paimentAmountOnSignUp;
        $payToAdminDefaultAmount = (int) $this->owner->getSetting('pay_to_administration_amount');
        $adminId = (int) $this->owner->getSetting('admin_payment_receiver_id', null);
        if ($this->owner->referral > 0) {
            $transaction = yii::$app->db->beginTransaction();
            $this->owner->transferMoneyToUser($adminId, $payToAdminDefaultAmount, false);
            $this->payToMyReferral($this->owner, $this->owner->referral, ($paimentAmountOnSignUp - $payToAdminDefaultAmount), $adminId);
            if (is_array($this->usersersThatHavePaid) && count($this->usersersThatHavePaid) > 0) {
                $setPaid = User::updateAll(['paid_to_referrals' => User::PAID_TO_REFERRALS], ['in', 'id', $this->usersersThatHavePaid]);
                $transaction->commit();
            }
        } else {
            $this->owner->transferMoneyToUser($adminId, $paimentAmountOnSignUp);
        }
    }

    /**
     * pay to my referrals
     * @param User $paier
     * @param integer $referralID
     * @param integer $totalAmmount
     * @param integer $adminId
     * @param integer $iteration
     * @return boolean
     */
    public function payToMyReferral($paier, $referralID, $totalAmmount, $adminId, $iteration = 1)
    {
        $payReferrals = ((int) $this->owner->getSetting('pay_to_referals_amount') * 2);
        $user = User::findById($referralID);
        if (($totalAmmount >= $payReferrals) && ($user) && ($iteration <= 7)) {
            $iteration++;
            if ($pair = $user->getPair($paier->primaryKey)) {
                if ($paier->transferMoneyToUser($user->primaryKey, $payReferrals, false)) {
                    $totalAmmount = $totalAmmount - $payReferrals;
                    $this->usersersThatHavePaid[] = $pair->primaryKey;
                }
            }
            return $paier->payToMyReferral($paier, $user->referral, $totalAmmount, $adminId, $iteration);
        } else {
            $paier->transferMoneyToUser($adminId, $totalAmmount, false);
        }
    }

    public function getPair($childId)
    {
        return $this->getTeam()->andWhere('id!=:childid AND status=:stat AND paid_to_referrals=:ptref', [
                    'childid' => $childId,
                    'stat' => User::STATUS_ACTIVE,
                    'ptref' => User::PAID_TO_REFERRALS_FALSE,
                ])->one();
    }

    /**
     * referral url
     * @return string
     */
    public function getReferralUrl()
    {
        $r = $this->getEncodeReferral();
        return Url::to(['/site/signup', 'ref' => $r], true);
    }

    /**
     * encode referral id
     * @param integer $id
     * @return type
     */
    public function getEncodeReferral($id = null)
    {
        $h = Helper::str();
        if ($id !== null) {
            return $h->b64Encode((int) $id);
        } else {
            return $h->b64Encode((int) $this->owner->primaryKey);
        }
    }

    /**
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getTeam($userId = null)
    {
        if (!$userId) {
            $userId = $this->owner->primaryKey;
        }
        $connection = yii::$app->getDb();
        $sql = 'SELECT GROUP_CONCAT(lv SEPARATOR \',\') FROM (
                        SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR \',\') FROM `bps_user` WHERE referral IN (@pv)) AS lv FROM bps_user
                        JOIN
                        (SELECT @pv:=' . ($userId) . ')tmp 
                        WHERE (referral IN (@pv))) a;';
        $command = $connection->createCommand($sql);
        $result = str_replace($userId . ',', '', $command->queryScalar());
        return User::find()->where(['in', 'id', ($result ? explode(',', $result) : 0)]);
    }

    /**
     * get best from team
     * @return \yii\db\ActiveQuery
     */
    public function getBestFromTeam()
    {
        $q = $this->owner->getTeam();
        $q->alias('u');
        $q->select("*,(SELECT count(id) as CN FROM {{%user}} uc WHERE referral=u.id) as countReferrals ");
        return $q;
    }

}
