<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test_answer}}`.
 */
class m251128_095728_create_test_answer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test_answer}}', [
            'id' => $this->primaryKey(),
            'attempt_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'answer' => $this->text()->null()->comment('JSON for multiple answers or single answer'),
            'is_correct' => $this->boolean()->null(),
            'points_awarded' => $this->integer()->null(),
            'answered_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Indexes
        $this->createIndex('idx-test_answer-attempt_id', '{{%test_answer}}', 'attempt_id');
        $this->createIndex('idx-test_answer-question_id', '{{%test_answer}}', 'question_id');

        // Foreign keys
        $this->addForeignKey(
            'fk-test_answer-attempt_id',
            '{{%test_answer}}',
            'attempt_id',
            '{{%test_attempt}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_answer-question_id',
            '{{%test_answer}}',
            'question_id',
            '{{%test_question}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-test_answer-question_id', '{{%test_answer}}');
        $this->dropForeignKey('fk-test_answer-attempt_id', '{{%test_answer}}');
        
        $this->dropIndex('idx-test_answer-question_id', '{{%test_answer}}');
        $this->dropIndex('idx-test_answer-attempt_id', '{{%test_answer}}');
        
        $this->dropTable('{{%test_answer}}');
    }
}
