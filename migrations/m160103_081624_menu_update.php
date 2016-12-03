<?php

use yii\db\Schema;
use yii\db\Migration;

class m160103_081624_menu_update extends Migration
{
    public function up()
    {
        $this->addColumn('{{%menu}}', 'visible', 'tinyint(1) NOT NULL COMMENT "visible"');
        $this->addColumn('{{%menu}}', 'icon', 'varchar(100) DEFAULT NULL COMMENT "icon"');
    }

    public function down()
    {
        $this->dropColumn('{{%menu}}', 'visible');
        $this->dropColumn('{{%menu}}', 'icon');
    }

}