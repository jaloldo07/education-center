<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%support_ticket}}`.
 */
class m251215_095359_create_support_ticket_table extends Migration
{
   public function safeUp()
    {
        $this->createTable('{{%support_ticket}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'subject' => $this->string(255)->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('open'),
            'admin_reply' => $this->text(),
            'admin_replied_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-support_ticket-user_id', '{{%support_ticket}}', 'user_id');
        $this->createIndex('idx-support_ticket-status', '{{%support_ticket}}', 'status');
        
        $this->addForeignKey(
            'fk-support_ticket-user_id',
            '{{%support_ticket}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%support_ticket}}');
    }
}
