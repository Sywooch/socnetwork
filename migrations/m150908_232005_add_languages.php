<?php

use yii\db\Schema;
use yii\db\Migration;

class m150908_232005_add_languages extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%languages}} (
                        `language_id` varchar(2) NOT NULL COMMENT 'id',
                        `language_name` varchar(64) NOT NULL COMMENT 'Name',
                        `language_active` tinyint(1) DEFAULT '1' COMMENT 'Active',
                        `language_is_default` tinyint(1) DEFAULT '0' COMMENT 'Is default',
                        PRIMARY KEY (`language_id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->execute("INSERT INTO {{%languages}} (`language_id`, `language_name`, `language_active`,`language_is_default`)
                            VALUES
                        ('en', 'English', 1, 1);");
    }

    public function down()
    {
        $this->dropTable('{{%languages}}');
    }

}