<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m251113_133419_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'payment_date' => $this->date()->notNull(),
            'payment_type' => $this->string(20)->notNull()->defaultValue('monthly'),
            'note' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-payment-student_id', '{{%payment}}', 'student_id');
        $this->createIndex('idx-payment-course_id', '{{%payment}}', 'course_id');
        $this->createIndex('idx-payment-payment_date', '{{%payment}}', 'payment_date');
        
        $this->addForeignKey(
            'fk-payment-student_id',
            '{{%payment}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-payment-course_id',
            '{{%payment}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payment-student_id', '{{%payment}}');
        $this->dropForeignKey('fk-payment-course_id', '{{%payment}}');
        $this->dropTable('{{%payment}}');
    }
}