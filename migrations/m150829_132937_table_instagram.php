<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_132937_table_instagram extends Migration
{
    public function up()
    {
        $this->createTable('instagram',[
            'id' => Schema::TYPE_PK,
            'external_id' => Schema::TYPE_STRING,
            'videos' => Schema::TYPE_TEXT,
            'images' => Schema::TYPE_TEXT,
            'tags' => Schema::TYPE_TEXT,
            'caption' => Schema::TYPE_TEXT,
            'location' => Schema::TYPE_TEXT,
            'user' => Schema::TYPE_TEXT,
            'update' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public function down()
    {

        $this->dropTable('instagram');
        return true;
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
