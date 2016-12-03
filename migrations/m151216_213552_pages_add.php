<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_213552_pages_add extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%pages}} (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
                        `status` tinyint(2) DEFAULT '1' COMMENT 'status',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");
        $this->execute("CREATE TABLE {{%pages_t}} (
                        `page_id` int(11) unsigned NOT NULL COMMENT 'page',
                        `title` varchar(250) NOT NULL DEFAULT '' COMMENT 'title',
                        `content` text NOT NULL COMMENT 'content',
                        `language_id` varchar(2) NOT NULL DEFAULT '' COMMENT 'language',
                        KEY `pages_pages_t_fk` (`page_id`),
                        CONSTRAINT `pages_pages_t_fk` FOREIGN KEY (`page_id`) REFERENCES {{%pages}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropForeignKey('seo_seo_t_fk', '{{%pages_t}}');
        $this->dropTable('{{%pages_t}}');
        $this->dropTable('{{%pages}}');
    }

}