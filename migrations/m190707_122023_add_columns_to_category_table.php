<?php

use yii\db\Migration;

/**
 * Class m190707_122023_add_columns_to_category_table
 */
class m190707_122023_add_columns_to_category_table
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category', 'description', $this->string(255)->after('icon'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category', 'description');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190707_122023_add_columns_to_category_table cannot be reverted.\n";

        return false;
    }
    */
}
