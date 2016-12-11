<?php

use yii\db\Migration;

class m161210_134108_user_update extends Migration
{

    public $tableName = '{{%user}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'referral', $this->integer()
                        ->comment('user referral')
                        ->defaultValue(0));
        $this->addColumn($this->tableName, 'balance', $this
                        ->money()
                        ->comment('balance')
                        ->defaultValue(0));
        $this->addColumn($this->tableName, 'paid_to_referrals', $this
                        ->smallInteger()
                        ->comment('paid to referrals')
                        ->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'referral');
        $this->dropColumn($this->tableName, 'balance');
        $this->dropColumn($this->tableName, 'paid_to_referrals');
    }

}
