<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test_attempt}}`.
 */
class m251128_095713_create_test_attempt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test_attempt}}', [
            'id' => $this->primaryKey(),
            'test_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'face_photo' => $this->string(255)->null()->comment('Face control photo path'),
            'started_at' => $this->integer()->notNull(),
            'finished_at' => $this->integer()->null(),
            'score' => $this->decimal(5, 2)->null()->comment('Score in percentage'),
            'points_earned' => $this->integer()->null(),
            'total_points' => $this->integer()->null(),
            'status' => $this->string(20)->notNull()->defaultValue('in_progress')->comment('in_progress, completed, abandoned'),
            'ip_address' => $this->string(45)->null(),
            'user_agent' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Indexes
        $this->createIndex('idx-test_attempt-test_id', '{{%test_attempt}}', 'test_id');
        $this->createIndex('idx-test_attempt-student_id', '{{%test_attempt}}', 'student_id');
        $this->createIndex('idx-test_attempt-status', '{{%test_attempt}}', 'status');

        // Foreign keys
        $this->addForeignKey(
            'fk-test_attempt-test_id',
            '{{%test_attempt}}',
            'test_id',
            '{{%test}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_attempt-student_id',
            '{{%test_attempt}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-test_attempt-student_id', '{{%test_attempt}}');
        $this->dropForeignKey('fk-test_attempt-test_id', '{{%test_attempt}}');
        
        $this->dropIndex('idx-test_attempt-status', '{{%test_attempt}}');
        $this->dropIndex('idx-test_attempt-student_id', '{{%test_attempt}}');
        $this->dropIndex('idx-test_attempt-test_id', '{{%test_attempt}}');
        
        $this->dropTable('{{%test_attempt}}');
    }
}
