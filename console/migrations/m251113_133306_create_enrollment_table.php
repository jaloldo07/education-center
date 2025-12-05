<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%enrollment}}`.
 */
class m251113_133306_create_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%enrollment}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'enrolled_on' => $this->date()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('active'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-enrollment-student_id', '{{%enrollment}}', 'student_id');
        $this->createIndex('idx-enrollment-group_id', '{{%enrollment}}', 'group_id');
        $this->createIndex('idx-enrollment-status', '{{%enrollment}}', 'status');
        
        $this->addForeignKey(
            'fk-enrollment-student_id',
            '{{%enrollment}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-enrollment-group_id',
            '{{%enrollment}}',
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
        $this->dropForeignKey('fk-enrollment-student_id', '{{%enrollment}}');
        $this->dropForeignKey('fk-enrollment-group_id', '{{%enrollment}}');
        $this->dropTable('{{%enrollment}}');
    }
}