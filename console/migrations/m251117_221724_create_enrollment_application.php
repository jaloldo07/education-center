<?php
use yii\db\Migration;

class m251117_221724_create_enrollment_application extends Migration
{
    public function up()
    {
        $this->createTable('{{%enrollment_application}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'message' => $this->text(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'admin_comment' => $this->text(),
            'reviewed_by' => $this->integer(),
            'reviewed_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-enrollment_application-student_id', '{{%enrollment_application}}', 'student_id');
        $this->createIndex('idx-enrollment_application-course_id', '{{%enrollment_application}}', 'course_id');
        $this->createIndex('idx-enrollment_application-status', '{{%enrollment_application}}', 'status');

        $this->addForeignKey('fk-enrollment_application-student_id', '{{%enrollment_application}}', 'student_id', '{{%student}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-enrollment_application-course_id', '{{%enrollment_application}}', 'course_id', '{{%course}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-enrollment_application-group_id', '{{%enrollment_application}}', 'group_id', '{{%group}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-enrollment_application-student_id', '{{%enrollment_application}}');
        $this->dropForeignKey('fk-enrollment_application-course_id', '{{%enrollment_application}}');
        $this->dropForeignKey('fk-enrollment_application-group_id', '{{%enrollment_application}}');
        $this->dropTable('{{%enrollment_application}}');
    }
}