<?php
use yii\db\Migration;

class m251116_205439_create_attendance_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%attendance}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'attendance_date' => $this->date()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('present'),
            'note' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-attendance-student_id', '{{%attendance}}', 'student_id');
        $this->createIndex('idx-attendance-group_id', '{{%attendance}}', 'group_id');
        $this->createIndex('idx-attendance-date', '{{%attendance}}', 'attendance_date');
        $this->createIndex('idx-attendance-unique', '{{%attendance}}', ['student_id', 'group_id', 'attendance_date'], true);
        
        $this->addForeignKey(
            'fk-attendance-student_id',
            '{{%attendance}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-attendance-group_id',
            '{{%attendance}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-attendance-student_id', '{{%attendance}}');
        $this->dropForeignKey('fk-attendance-group_id', '{{%attendance}}');
        $this->dropTable('{{%attendance}}');
    }
}