<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test}}`.
 */
class m251128_095628_create_test_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test}}', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->null(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'duration' => $this->integer()->notNull()->comment('Duration in minutes'),
            'passing_score' => $this->integer()->notNull()->defaultValue(60)->comment('Minimum score to pass (percentage)'),
            'total_questions' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->string(20)->notNull()->defaultValue('draft')->comment('draft, active, closed'),
            'start_date' => $this->dateTime()->null(),
            'end_date' => $this->dateTime()->null(),
            'require_face_control' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Indexes
        $this->createIndex('idx-test-teacher_id', '{{%test}}', 'teacher_id');
        $this->createIndex('idx-test-course_id', '{{%test}}', 'course_id');
        $this->createIndex('idx-test-group_id', '{{%test}}', 'group_id');
        $this->createIndex('idx-test-status', '{{%test}}', 'status');

        // Foreign keys
        $this->addForeignKey(
            'fk-test-teacher_id',
            '{{%test}}',
            'teacher_id',
            '{{%teacher}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test-course_id',
            '{{%test}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test-group_id',
            '{{%test}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-test-group_id', '{{%test}}');
        $this->dropForeignKey('fk-test-course_id', '{{%test}}');
        $this->dropForeignKey('fk-test-teacher_id', '{{%test}}');
        
        $this->dropIndex('idx-test-status', '{{%test}}');
        $this->dropIndex('idx-test-group_id', '{{%test}}');
        $this->dropIndex('idx-test-course_id', '{{%test}}');
        $this->dropIndex('idx-test-teacher_id', '{{%test}}');
        
        $this->dropTable('{{%test}}');
    }
}