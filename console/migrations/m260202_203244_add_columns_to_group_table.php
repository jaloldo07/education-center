
<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%group}}`.
 */
class m260202_203244_add_columns_to_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%group}}', 'status', $this->string()->defaultValue('pending'));
        $this->addColumn('{{%group}}', 'schedule', $this->string());
        $this->addColumn('{{%group}}', 'room', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%group}}', 'status');
        $this->dropColumn('{{%group}}', 'schedule');
        $this->dropColumn('{{%group}}', 'room');
    }
}