

<?php

use yii\db\Migration;

class m260202_133902_add_columns_to_payment_table extends Migration
{
    public function safeUp()
    {
        // Status: 0-pending, 1-paid, 2-failed
        $this->addColumn('{{%payment}}', 'status', $this->integer()->defaultValue(0)->after('amount'));
        
        // Payment Method: click, payme, cash
        $this->addColumn('{{%payment}}', 'payment_method', $this->string(50)->after('status'));
        
        // Transaction ID (Click yoki Payme dan keladigan ID)
        $this->addColumn('{{%payment}}', 'transaction_id', $this->string(255)->after('payment_method'));
        
        // payment_date ni NULL bo'lishiga ruxsat beramiz (chunki to'lov endi boshlanayotganda sana bo'lmasligi mumkin)
        $this->alterColumn('{{%payment}}', 'payment_date', $this->date()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%payment}}', 'transaction_id');
        $this->dropColumn('{{%payment}}', 'payment_method');
        $this->dropColumn('{{%payment}}', 'status');
        $this->alterColumn('{{%payment}}', 'payment_date', $this->date()->notNull());
    }
}