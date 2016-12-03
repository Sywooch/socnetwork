<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'avatar' => Schema::TYPE_STRING . '(50) DEFAULT NULL',
                ], $tableOptions);
        
//        admin user : 
//        login: admin 
//        password: 123qwe
        $this->execute("INSERT INTO {{%user}} (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created_at`, `updated_at`)
            VALUES
	(1, 'admin', 'OjF4oXLUbbbq7ugyb1M1BWnFQ3swNok3', '$2y$13\$Akz6YZMIbZEqTgz5pqGSU.xST07qqXKRTgyy0lpe7cvL4lxiw3tvO', NULL, 'gaftonsifon@yandex.com', " . \app\models\User::ROLE_ADMIN . ", 10, 1440945768, 1440945768);
");
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }

}