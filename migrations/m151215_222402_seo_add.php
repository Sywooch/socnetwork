<?php

use yii\db\Schema;
use yii\db\Migration;

class m151215_222402_seo_add extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%seo}} (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `url` text COMMENT 'url',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");


        $this->execute("CREATE TABLE {{%seo_t}} (
                        `seo_id` int(11) unsigned NOT NULL COMMENT 'seo',
                        `alias` text NOT NULL COMMENT 'alias',
                        `title` varchar(100) DEFAULT NULL COMMENT 'title',
                        `h1` text COMMENT 'h1',
                        `keywords` text COMMENT 'keywords',
                        `description` text COMMENT 'description',
                        `language_id` varchar(2) NOT NULL DEFAULT 'en' COMMENT 'language',
                        KEY `seo_seo_t_fk` (`seo_id`),
                        CONSTRAINT `seo_seo_t_fk` FOREIGN KEY (`seo_id`) REFERENCES {{%seo}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropForeignKey('seo_seo_t_fk', '{{%seo_t}}');
        $this->dropTable('{{%seo_t}}');
        $this->dropTable('{{%seo}}');
    }

}