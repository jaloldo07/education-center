<?php

use yii\db\Migration;

/**
 * Creates tables for floating chat widget system
 */
class m241203_000000_create_floating_chat_tables extends Migration
{
    public function safeUp()
    {
        // 1. CHAT MESSAGE (1-to-1)
        $this->createTable('{{%chat_message}}', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'is_read' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-chat_message-sender_id', '{{%chat_message}}', 'sender_id');
        $this->createIndex('idx-chat_message-receiver_id', '{{%chat_message}}', 'receiver_id');
        $this->createIndex('idx-chat_message-is_read', '{{%chat_message}}', 'is_read');

        $this->addForeignKey(
            'fk-chat_message-sender_id',
            '{{%chat_message}}',
            'sender_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-chat_message-receiver_id',
            '{{%chat_message}}',
            'receiver_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // 2. GROUP MESSAGE
        $this->createTable('{{%group_message}}', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-group_message-teacher_id', '{{%group_message}}', 'teacher_id');
        $this->createIndex('idx-group_message-group_id', '{{%group_message}}', 'group_id');

        $this->addForeignKey(
            'fk-group_message-teacher_id',
            '{{%group_message}}',
            'teacher_id',
            '{{%teacher}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-group_message-group_id',
            '{{%group_message}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        // 3. SUPPORT TICKET
        $this->createTable('{{%support_ticket}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'subject' => $this->string(255)->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('open'),
            'admin_reply' => $this->text()->null(),
            'admin_replied_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
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

        echo "✅ Floating chat tables created successfully!\n";
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-support_ticket-user_id', '{{%support_ticket}}');
        $this->dropForeignKey('fk-group_message-group_id', '{{%group_message}}');
        $this->dropForeignKey('fk-group_message-teacher_id', '{{%group_message}}');
        $this->dropForeignKey('fk-chat_message-receiver_id', '{{%chat_message}}');
        $this->dropForeignKey('fk-chat_message-sender_id', '{{%chat_message}}');

        $this->dropTable('{{%support_ticket}}');
        $this->dropTable('{{%group_message}}');
        $this->dropTable('{{%chat_message}}');

        echo "❌ Floating chat tables dropped.\n";
    }
}
