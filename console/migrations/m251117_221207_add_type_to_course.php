<?php
use yii\db\Migration;

class m251117_221207_add_type_to_course extends Migration
{
    public function up()
    {
        $this->addColumn('{{%course}}', 'type', $this->string(20)->notNull()->defaultValue('free')->after('price'));
        $this->createIndex('idx-course-type', '{{%course}}', 'type');
    }

    public function down()
    {
        $this->dropIndex('idx-course-type', '{{%course}}');
        $this->dropColumn('{{%course}}', 'type');
    }
}