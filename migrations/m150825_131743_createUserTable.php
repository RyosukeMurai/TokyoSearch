<?php

use yii\db\Schema;
use yii\db\Migration;

class m150825_131743_createUserTable extends Migration
{
    public function up()
    {
        $this->createTable('user',[
            'id' => Schema::TYPE_PK, 
            'username' => Schema::TYPE_STRING . ' NOT NULL ',
            'password' => Schema::TYPE_STRING . ' NOT NULL ',
            'accesstoken' => Schema::TYPE_STRING . ' NOT NULL ',
            'authkey' => Schema::TYPE_STRING . ' NOT NULL ',
            'email' => Schema::TYPE_STRING . ' NOT NULL ',
            'firstname' => Schema::TYPE_STRING,
            'lastname' => Schema::TYPE_STRING,
            'update' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
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
