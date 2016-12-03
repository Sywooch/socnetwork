<?php

use yii\db\Migration;

class m160417_201944_search_values_add extends Migration
{
    public $tableName = '{{%search_values}}';

    public function up()
    {
        $this->execute("CREATE TABLE $this->tableName (
                            `search_id` int(11) NOT NULL,
                            `value` text NOT NULL,
                            `attribute` varchar(100) DEFAULT NULL,
                            FULLTEXT KEY `search_values_index` (`value`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }

}