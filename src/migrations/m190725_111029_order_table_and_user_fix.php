<?php

use yii\db\Migration;

/**
 * Class m190725_111029_order_table_and_user_fix
 */
class m190725_111029_order_table_and_user_fix
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(64),
            'phone' => $this->string(32)->notNull(),
            'email' => $this->string(),
            'city' => $this->string(64),
            'department' => $this->string(64),
            'invoice' => $this->string(32)->defaultValue(''),
            'sum' => $this->integer()->unsigned()->notNull(),
            'buy_sum' => $this->integer()->unsigned()->notNull(),
            'status' => $this->boolean()
                             ->defaultValue(\app\models\Order::ORDER_STATUS_NEW),
            'delivery' => $this->boolean()->notNull(),
            'comment' => $this->text(),
            'phone_status' => $this->smallInteger()->unsigned(),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);
    
        $this->execute("ALTER TABLE `order` AUTO_INCREMENT=1371;");
        
        $this->createTable('{{%order_size}}', [
            'order_id' => $this->integer()->unsigned()->notNull(),
            'size_id' => $this->integer()->unsigned()->notNull(),
            'quantity' => $this->smallInteger()->unsigned()->notNull(),
            'cost' => $this->integer()->unsigned()->notNull(),
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_size}}');
        
        $this->dropTable('{{%order}}');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190725_111029_order_table_and_user_fix cannot be reverted.\n";

        return false;
    }
    */
}
