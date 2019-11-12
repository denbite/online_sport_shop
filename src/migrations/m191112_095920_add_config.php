<?php

use yii\db\Migration;

/**
 * Class m191112_095920_add_config
 */
class m191112_095920_add_config
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%config}}', [
            'group' => 'Значения',
            'name' => 'minPriceForFreeDelivery',
            'value' => '1000',
            'label' => 'Минимальная цена заказа для бесплатной доставки',
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%config}}', [ 'name' => 'minPriceForFreeDelivery' ]);
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191112_095920_add_config cannot be reverted.\n";

        return false;
    }
    */
}
