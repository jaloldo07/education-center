<?php

use yii\db\Migration;

class m251210_110333_add_is_group_message_to_chat_message extends Migration
{
  public function safeUp()
    {
        $this->addColumn('{{%chat_message}}', 'is_group_message', $this->boolean()->defaultValue(0)->after('is_read'));
        $this->addColumn('{{%chat_message}}', 'group_id', $this->integer()->null()->after('is_group_message'));
        
        // Add index
        $this->createIndex(
            'idx-chat_message-group_id',
            '{{%chat_message}}',
            'group_id'
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx-chat_message-group_id', '{{%chat_message}}');
        $this->dropColumn('{{%chat_message}}', 'group_id');
        $this->dropColumn('{{%chat_message}}', 'is_group_message');
    }
}
