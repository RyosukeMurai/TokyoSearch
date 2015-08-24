<?php

use yii\db\Schema;
use yii\db\Migration;

class m150820_050133_restore_database extends Migration
{
    public function up()
    {
	    $container = $this->getDb();
        $sql= file_get_contents('/var/www/lw2/src/migrations/files/init.sql');
        throw new Exception($sql);
        $container['db']->query($sql);
    }

    public function down()
    {
        echo "m150820_050133_restore_database cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
