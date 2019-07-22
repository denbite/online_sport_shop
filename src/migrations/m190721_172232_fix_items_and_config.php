<?php

use yii\db\Migration;

/**
 * Class m190721_172232_fix_items_and_config
 */
class m190721_172232_fix_items_and_config
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%item_color_size}}', 'price', 'base_price');
        $this->addColumn('{{%item_color_size}}', 'sell_price',
                         $this->integer(11)->unsigned()->notNull()->defaultValue(0)->after('base_price'));
        $this->addColumn('{{%item_color_size}}', 'sale_price',
                         $this->integer(11)->unsigned()->notNull()->defaultValue(0)->after('sell_price'));
        
        
        $this->createTable('{{%config}}', [
            'group' => $this->string(32),
            'name' => $this->string(64)->unique()->notNull(),
            'value' => $this->string(),
            'label' => $this->string(),
        ]);
        
        $this->insert('{{%config}}', [
            'group' => 'Коэфициенты',
            'name' => 'priceMultiplier',
            'value' => 0.95,
            'label' => 'Коэфициент, на который умножается базовая цена для продаж',
        ]);
        
        $this->insert('{{%config}}', [
            'group' => 'Коэфициенты',
            'name' => 'buyMultiplier',
            'value' => 0.6,
            'label' => 'Коэфициент, на который умножается базовая цена при закупке товаров',
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropTable('{{%config}}');
        
        $this->dropColumn('{{%item_color_size}}', 'sale_price');
        $this->dropColumn('{{%item_color_size}}', 'sell_price');
        $this->renameColumn('{{%item_color_size}}', 'base_price', 'price');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190721_172232_fix_items_and_config cannot be reverted.\n";

        return false;
    }
    */
}
