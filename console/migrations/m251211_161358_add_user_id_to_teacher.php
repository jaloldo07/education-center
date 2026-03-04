<?php

use yii\db\Migration;

class m251211_161358_add_user_id_to_teacher extends Migration
{
    public function safeUp()
{
    $this->addColumn('{{%teacher}}', 'user_id', $this->integer()->after('id'));
    $this->createIndex('idx-teacher-user_id', '{{%teacher}}', 'user_id');
    $this->addForeignKey('fk-teacher-user_id', '{{%teacher}}', 'user_id', '{{%user}}', 'id', 'SET NULL');
}

public function safeDown()
{
    $this->dropForeignKey('fk-teacher-user_id', '{{%teacher}}');
    $this->dropIndex('idx-teacher-user_id', '{{%teacher}}');
    $this->dropColumn('{{%teacher}}', 'user_id');
}
}
