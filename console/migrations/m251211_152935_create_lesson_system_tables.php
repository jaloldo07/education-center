<?php

use yii\db\Migration;

class m251211_152935_create_lesson_system_tables extends Migration
{
   public function safeUp()
    {
        // 1. LESSON TABLE
        $this->createTable('{{%lesson}}', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'content_type' => "ENUM('video','text','pdf','image') NOT NULL",
            'content' => $this->text()->comment('Text content'),
            'file_path' => $this->string(255)->comment('PDF/Image path'),
            'video_url' => $this->string(500)->comment('YouTube/Vimeo URL or local video'),
            'order_number' => $this->integer()->notNull()->defaultValue(1),
            'difficulty_level' => "ENUM('easy','medium','hard') DEFAULT 'medium'",
            'duration_minutes' => $this->integer()->comment('Estimated duration'),
            'min_watch_time' => $this->integer()->comment('Minimum seconds to complete (for video)'),
            'is_published' => $this->boolean()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        // Index
        $this->createIndex('idx-lesson-course_id', '{{%lesson}}', 'course_id');
        $this->createIndex('idx-lesson-order', '{{%lesson}}', ['course_id', 'order_number']);
        
        // Foreign key
        $this->addForeignKey(
            'fk-lesson-course_id',
            '{{%lesson}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // 2. LESSON_PROGRESS TABLE
        $this->createTable('{{%lesson_progress}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'lesson_id' => $this->integer()->notNull(),
            'status' => "ENUM('not_started','in_progress','completed') DEFAULT 'not_started'",
            'progress_percentage' => $this->integer()->defaultValue(0)->comment('0-100'),
            'time_spent' => $this->integer()->defaultValue(0)->comment('Seconds'),
            'video_progress' => $this->integer()->defaultValue(0)->comment('Video current time (seconds)'),
            'completed_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        // Indexes
        $this->createIndex('idx-lesson_progress-student_id', '{{%lesson_progress}}', 'student_id');
        $this->createIndex('idx-lesson_progress-lesson_id', '{{%lesson_progress}}', 'lesson_id');
        $this->createIndex('unique-student-lesson', '{{%lesson_progress}}', ['student_id', 'lesson_id'], true);
        
        // Foreign keys
        $this->addForeignKey(
            'fk-lesson_progress-student_id',
            '{{%lesson_progress}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-lesson_progress-lesson_id',
            '{{%lesson_progress}}',
            'lesson_id',
            '{{%lesson}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // 3. COURSE_TEST TABLE (link existing tests to courses)
        $this->createTable('{{%course_test}}', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'test_id' => $this->integer()->notNull(),
            'is_final_test' => $this->boolean()->defaultValue(0)->comment('Kurs oxiridagi asosiy test'),
            'order_number' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        // Indexes
        $this->createIndex('idx-course_test-course_id', '{{%course_test}}', 'course_id');
        $this->createIndex('idx-course_test-test_id', '{{%course_test}}', 'test_id');
        
        // Foreign keys
        $this->addForeignKey(
            'fk-course_test-course_id',
            '{{%course_test}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-course_test-test_id',
            '{{%course_test}}',
            'test_id',
            '{{%test}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // Drop foreign keys first
        $this->dropForeignKey('fk-course_test-test_id', '{{%course_test}}');
        $this->dropForeignKey('fk-course_test-course_id', '{{%course_test}}');
        $this->dropForeignKey('fk-lesson_progress-lesson_id', '{{%lesson_progress}}');
        $this->dropForeignKey('fk-lesson_progress-student_id', '{{%lesson_progress}}');
        $this->dropForeignKey('fk-lesson-course_id', '{{%lesson}}');

        // Drop tables
        $this->dropTable('{{%course_test}}');
        $this->dropTable('{{%lesson_progress}}');
        $this->dropTable('{{%lesson}}');
    }
}
