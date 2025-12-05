<?php
use yii\db\Migration;

class m251116_211338_create_teacher_application_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%teacher_application}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
            'phone' => $this->string(20)->notNull(),
            'subject' => $this->string(255)->notNull(),
            'experience_years' => $this->integer()->notNull(),
            'education' => $this->text()->notNull(),
            'bio' => $this->text()->notNull(),
            'cv_file' => $this->string(255),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'admin_comment' => $this->text(),
            'reviewed_by' => $this->integer(),
            'reviewed_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-teacher_application-email', '{{%teacher_application}}', 'email');
        $this->createIndex('idx-teacher_application-status', '{{%teacher_application}}', 'status');
    }

    public function down()
    {
        $this->dropTable('{{%teacher_application}}');
    }
}