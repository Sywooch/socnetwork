<?php

use yii\db\Migration;
use app\models\File;

class m160407_170514_file_add extends Migration
{
    public $tableName = '{{%file}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'scheme' => yii\db\Schema::TYPE_STRING . '(20) NOT NULL',
            'host' => yii\db\Schema::TYPE_STRING . '(150) NOT NULL',
            'path' => yii\db\Schema::TYPE_TEXT . ' NOT NULL',
            'name' => yii\db\Schema::TYPE_STRING . '(50) NOT NULL',
            'title' => yii\db\Schema::TYPE_TEXT . ' NOT NULL',
            'extension' => yii\db\Schema::TYPE_STRING . '(25) NOT NULL',
            'size' => yii\db\Schema::TYPE_BIGINT . ' NOT NULL',
            'mime' => yii\db\Schema::TYPE_STRING . '(150) NOT NULL',
            'created_at' => yii\db\Schema::TYPE_INTEGER,
            'status' => yii\db\Schema::TYPE_BOOLEAN . ' DEFAULT ' . File::STATUS_UPLOADED . ' NOT NULL',
            'location' => yii\db\Schema::TYPE_BOOLEAN . ' DEFAULT ' . File::LOCATION_LOCAL . ' NOT NULL',
            'owner' => yii\db\Schema::TYPE_STRING . '(50) NOT NULL DEFAULT 0',
        ]);
        $this->createIndex('file_name_index', $this->tableName, 'name');
        $this->execute("CREATE TABLE {{%file_destination}} (
                            `file_name` varchar(50) NOT NULL,
                            `destination` varchar(100) NOT NULL
                          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->createIndex('dest_file_name_index', '{{%file_destination}}', 'file_name');
    }

    public function down()
    {
        $this->dropIndex('file_name_index', $this->tableName);
        $this->dropTable($this->tableName);
        $this->dropIndex('dest_file_name_index', '{{%file_destination}}');
        $this->dropTable('{{%file_destination}}');
    }

}