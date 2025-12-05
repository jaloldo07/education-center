<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student}}`.
 */
class m251113_132727_create_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'full_name' => $this->string(255)->notNull(),
            'birth_date' => $this->date(),
            'phone' => $this->string(20),
            'email' => $this->string(255)->notNull()->unique(),
            'address' => $this->text(),
            'enrolled_date' => $this->date()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-student-email', '{{%student}}', 'email');
        $this->createIndex('idx-student-user_id', '{{%student}}', 'user_id');
        $this->addForeignKey(
            'fk-student-user_id',
            '{{%student}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-student-user_id', '{{%student}}');
        $this->dropTable('{{%student}}');
    }
}