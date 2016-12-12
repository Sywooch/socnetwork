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

    public $preparedPairReferralChild;

    /**
     * events
     * @return array
     */
    public function events()
    {
        return [
            User::EVENT_PAY_TO_REFERRALS => 'payToReferrals',
        ];
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

        if ($this->owner->referral == 0) {
            $this->owner->transferMoneyToUser($adminId, $paimentAmountOnSignUp);
        } else {
            $this->owner->transferMoneyToUser($adminId, $payToAdminDefaultAmount);
            $this->payToMyReferral($this->owner, $this->owner->referral, ($paimentAmountOnSignUp - $payToAdminDefaultAmount), $adminId);
        }
    }

    /**
     * pay to my referrals
     * @param User $paier
     * @param integer $referralID
     * @param integer $totalAmmount
     * @param integer $adminId
     * @return boolean
     */
    public function payToMyReferral($paier, $referralID, $totalAmmount, $adminId)
    {
        $payReferrals = ((int) $this->owner->getSetting('pay_to_referals_amount') * 2);
        $this->prepareReferrals($paier, $referralID);
        if (($totalAmmount < $payReferrals) || !$this->preparedPairReferralChild || ($this->preparedPairReferralChild && $this->preparedPairReferralChild->referral == 0)) {
            return $paier->transferMoneyToUser($adminId, $totalAmmount);
        }
        $ref = User::findById($this->preparedPairReferralChild->referral);
        if ($paier->transferMoneyToUser($ref->id, $payReferrals, YII_ENV_DEV)) {
            $this->preparedPairReferralChild->paid_to_referrals = User::PAID_TO_REFERRALS;
            if ($this->preparedPairReferralChild->validate()) {
                $this->preparedPairReferralChild->save();
            }
        }
        $totalAmmount = $totalAmmount - $payReferrals;
        return $ref->payToMyReferral($paier, $ref->referral, $totalAmmount, $adminId);
    }

    /**
     * prepare referral child
     * @param User $paier
     * @param integer $referralID
     * @return User
     */
    public function prepareReferrals($paier, $referralID)
    {
        $this->preparedPairReferralChild = User::find()->where([
                    'referral' => $referralID,
                    'status' => User::STATUS_ACTIVE,
//                    'paid_to_referrals' => User::PAID_TO_REFERRALS_FALSE,
                ])->andWhere('id!=:uid', ['uid' => $paier->primaryKey])->orderBy(['id' => SORT_DESC])->one();
        return $this->preparedPairReferralChild;
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

}
