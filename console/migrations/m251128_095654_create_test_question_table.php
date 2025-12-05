<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test_question}}`.
 */
class m251128_095654_create_test_question_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test_question}}', [
            'id' => $this->primaryKey(),
            'test_id' => $this->integer()->notNull(),
            'question_text' => $this->text()->notNull(),
            'question_type' => $this->string(20)->notNull()->defaultValue('single_choice')->comment('single_choice, multiple_choice, text'),
            'options' => $this->text()->comment('JSON array of options'),
            'correct_answer' => $this->text()->comment('JSON for multiple correct answers or single answer'),
            'points' => $this->integer()->notNull()->defaultValue(1),
            'order' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Indexes
        $this->createIndex('idx-test_question-test_id', '{{%test_question}}', 'test_id');
        $this->createIndex('idx-test_question-order', '{{%test_question}}', 'order');

        // Foreign key
        $this->addForeignKey(
            'fk-test_question-test_id',
            '{{%test_question}}',
            'test_id',
            '{{%test}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-test_question-test_id', '{{%test_question}}');
        $this->dropIndex('idx-test_question-order', '{{%test_question}}');
        $this->dropIndex('idx-test_question-test_id', '{{%test_question}}');
        $this->dropTable('{{%test_question}}');
    }
}
