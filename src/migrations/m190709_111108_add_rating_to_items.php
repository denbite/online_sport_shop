<?php

use yii\db\Migration;

/**
 * Class m190709_111108_add_rating_to_items
 */
class m190709_111108_add_rating_to_items
    extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%item}}', 'rate', $this->smallInteger()->unsigned()->after('collection')->defaultValue(0));
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%item}}', 'rate');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190709_111108_add_rating_to_items cannot be reverted.\n";

        return false;
    }
    */
}
