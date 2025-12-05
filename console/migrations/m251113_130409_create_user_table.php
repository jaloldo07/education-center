<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m251113_130409_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'role' => $this->string(20)->notNull()->defaultValue('student'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Qo‘shimcha indekslar
        $this->createIndex('idx-user-role', '{{%user}}', 'role');
        $this->createIndex('idx-user-status', '{{%user}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Indekslarni o‘chirish
        $this->dropIndex('idx-user-role', '{{%user}}');
        $this->dropIndex('idx-user-status', '{{%user}}');

        // Jadvalni o‘chirish
        $this->dropTable('{{%user}}');
    }
}
