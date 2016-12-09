<?php

use yii\db\Migration;

class m161208_102908_user_friends_create extends Migration
{

    public $tableName = '{{%user_friends}}';
    public $userTableName = '{{%user}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn($this->userTableName, 'referal', $this->integer()->defaultValue(0));
        $this->createTable($this->tableName, [
            'user_id' => $this->integer(11)->comment('user'),
            'sender_id' => $this->integer(11)->comment('friend'),
            'status' => $this->smallInteger(1)->comment('status')->defaultValue(0),
                ], $tableOptions);
    }

    public function down()
    {
        $this->dropColumn($this->userTableName, 'referal');
        return $this->dropTable($this->tableName);
    }

}
