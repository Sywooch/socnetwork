<?php

use yii\db\Schema;
use yii\db\Migration;

class m150830_141602_translation_tables_add extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%i18n_message_source}} (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `message` text,
                        `category` varchar(32) DEFAULT 'db',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        $this->execute("CREATE TABLE {{%i18n_message}} (
                        `id` int(11) NOT NULL DEFAULT '0',
                        `language` varchar(16) NOT NULL DEFAULT '',
                        `translation` text,
                        `is_new` tinyint(1) DEFAULT '1',
                        PRIMARY KEY (`id`,`language`),
                        CONSTRAINT `FK_i18n_message_i18n_message_source` FOREIGN KEY (`id`) REFERENCES {{%i18n_message_source}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        );
    }

    public function down()
    {
        $this->dropTable('{{%i18n_message}}');
        $this->dropTable('{{%i18n_message_source}}');
    }

}