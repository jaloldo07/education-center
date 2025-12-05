
<?php
use yii\db\Migration;

class m251117_233334_create_notification_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'message' => $this->text()->notNull(),
            'type' => $this->string(20)->notNull()->defaultValue('info'),
            'is_read' => $this->boolean()->defaultValue(false),
            'link' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-notification-user_id', '{{%notification}}', 'user_id');
        $this->createIndex('idx-notification-is_read', '{{%notification}}', 'is_read');
        
        $this->addForeignKey(
            'fk-notification-user_id',
            '{{%notification}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-notification-user_id', '{{%notification}}');
        $this->dropTable('{{%notification}}');
    }
}