<?php

use yii\db\Migration;

class m231111_000000_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique()->notNull(),
            'phone' => $this->string()->notNull(),
            'email' => $this->string()->unique()->notNull(),
            'email_verified_at' => $this->timestamp(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
