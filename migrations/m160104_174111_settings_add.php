<?php

use yii\db\Schema;
use yii\db\Migration;

class m160104_174111_settings_add extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%settings}} (
                        `key` varchar(250) DEFAULT 'settings' COMMENT 'key',
                        `value` text COMMENT 'value',
                        `model` varchar(250) DEFAULT 'settings' COMMENT 'model'
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');
    }

}