<?php

use yii\db\Schema;
use yii\db\Migration;

class m151207_161341_menu_add extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%menu}} (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
                        `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'type',
                        `parent` int(11) NOT NULL DEFAULT '0' COMMENT 'item parent',
                        `order` int(11) NOT NULL DEFAULT '0' COMMENT 'item order',
                        `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'item is active',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;");

        $this->execute("CREATE TABLE {{%menu_t}} (
                        `menu_id` int(11) unsigned NOT NULL COMMENT 'menu',
                        `title` varchar(250) NOT NULL DEFAULT '' COMMENT 'title',
                        `url` text NOT NULL COMMENT 'menu url',
                        `language_id` varchar(2) NOT NULL DEFAULT 'en' COMMENT 'language',
                        KEY `menu_t_menu_fk` (`menu_id`),
                        CONSTRAINT `menu_t_menu_fk` FOREIGN KEY (`menu_id`) REFERENCES {{%menu}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropTable('{{%menu_t}}');
        $this->dropTable('{{%menu}}');
    }

}