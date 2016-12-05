<?php

use yii\db\Migration;

class m161203_183041_user_update extends Migration
{

    public $tableName = '{{%user}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'first_name', $this->string('200')->comment('first name'));
        $this->addColumn($this->tableName, 'last_name', $this->string('200')->comment('last name'));
        $this->addColumn($this->tableName, 'country', $this->string('200')->comment('country'));
        $this->addColumn($this->tableName, 'city', $this->string('200')->comment('city'));
        $this->addColumn($this->tableName, 'skype', $this->string('200')->comment('skype login'));
        $this->addColumn($this->tableName, 'gender', $this->integer(2)->comment('gender'));
        $this->addColumn($this->tableName, 'about', $this->text()->comment('about me'));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'first_name');
        $this->dropColumn($this->tableName, 'last_name');
        $this->dropColumn($this->tableName, 'country');
        $this->dropColumn($this->tableName, 'city');
        $this->dropColumn($this->tableName, 'skype');
        $this->dropColumn($this->tableName, 'gender');
        $this->dropColumn($this->tableName, 'about');
    }

}
