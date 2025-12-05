<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%teacher}}`.
 */
class m251113_130826_create_teacher_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%teacher}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'subject' => $this->string(255)->notNull(),
            'experience_years' => $this->integer()->notNull()->defaultValue(0),
            'phone' => $this->string(20),
            'email' => $this->string(255)->notNull()->unique(),
            'bio' => $this->text(),
            'rating' => $this->decimal(3, 2)->defaultValue(0.00),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-teacher-email', '{{%teacher}}', 'email');
        $this->createIndex('idx-teacher-rating', '{{%teacher}}', 'rating');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%teacher}}');
    }
}