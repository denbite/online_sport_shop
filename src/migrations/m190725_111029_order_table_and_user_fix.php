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
            'invoice' => $this->string(32)->defaultValue(''), // change to notNull(), it will be generate by API
            'status' => $this->boolean()->defaultValue(1), // change status from model const to pending
            'delivery' => $this->boolean()->notNull(),
            'issue_date' => $this->integer()->notNull(),
        ]);
        
        $this->execute("ALTER TABLE `order` AUTO_INCREMENT=1373;");
        
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
