<?php

use yii\db\Migration;
use app\models\File;

class m160417_082909_search_add extends Migration
{
    public $tableName = '{{%search}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => yii\db\Schema::TYPE_PK,
            'link' => yii\db\Schema::TYPE_TEXT . ' NOT NULL',
            'model' => yii\db\Schema::TYPE_STRING . '(200) NOT NULL',
            'language_id' => yii\db\Schema::TYPE_STRING . '(2) NOT NULL',
            'params' => yii\db\Schema::TYPE_TEXT . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }

}