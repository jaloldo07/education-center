<?php
use yii\db\Migration;

class m251117_123336_create_schedule_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%schedule}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->notNull(),
            'teacher_id' => $this->integer()->notNull(),
            'day_of_week' => $this->smallInteger()->notNull(),
            'start_time' => $this->time()->notNull(),
            'end_time' => $this->time()->notNull(),
            'room' => $this->string(100),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // Foreign keys
        $this->addForeignKey('fk-schedule-group', '{{%schedule}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-schedule-teacher', '{{%schedule}}', 'teacher_id', '{{%teacher}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule}}');
    }
}