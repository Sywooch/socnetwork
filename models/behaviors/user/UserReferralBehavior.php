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

    public function events()
    {
        return [
            User::EVENT_PAY_TO_REFERRALS => 'payToReferrals',
        ];
    }

    public function payToReferrals()
    {
//        $this->owner->balance = 2000;
        /* TODO #PS: save referal logic behavior */
        $paimentAmountOnSignUp = $this->owner->getSetting('pay_on_signup_amount');
        $this->owner->balance = (int) $paimentAmountOnSignUp;
        $payToAdminDefaultAmount = (int) $this->owner->getSetting('pay_to_administration_amount');
        $payReferrals = (int) $this->owner->getSetting('pay_to_referals_amount');
        $adminId = (int) $this->owner->getSetting('admin_payment_receiver_id', null);

        if ($this->owner->referral == 0) {
            $this->owner->transferMoneyToUser($adminId, $paimentAmountOnSignUp);
        } else {
            $this->owner->transferMoneyToUser($adminId, $payToAdminDefaultAmount);
        }
    }

    public function getReferralUrl()
    {
        $r = $this->getEncodeReferral();
        return Url::to(['/site/signup', 'ref' => $r], true);
    }

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
