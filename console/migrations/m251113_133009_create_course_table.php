<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%course}}`.
 */
class m251113_133009_create_course_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%course}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'duration' => $this->integer()->notNull()->comment('Duration in months'),
            'price' => $this->decimal(10, 2)->notNull(),
            'teacher_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-course-teacher_id', '{{%course}}', 'teacher_id');
        $this->addForeignKey(
            'fk-course-teacher_id',
            '{{%course}}',
            'teacher_id',
            '{{%teacher}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-course-teacher_id', '{{%course}}');
        $this->dropTable('{{%course}}');
    }
}