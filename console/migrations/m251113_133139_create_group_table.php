<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 */
class m251113_133139_create_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'course_id' => $this->integer()->notNull(),
            'teacher_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-group-course_id', '{{%group}}', 'course_id');
        $this->createIndex('idx-group-teacher_id', '{{%group}}', 'teacher_id');
        
        $this->addForeignKey(
            'fk-group-course_id',
            '{{%group}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-group-teacher_id',
            '{{%group}}',
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
        $this->dropForeignKey('fk-group-course_id', '{{%group}}');
        $this->dropForeignKey('fk-group-teacher_id', '{{%group}}');
        $this->dropTable('{{%group}}');
    }
}