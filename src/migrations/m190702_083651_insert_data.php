<?php

use app\models\Category;
use yii\db\Migration;

/**
 * Class m190702_083651_insert_data
 */
class m190702_083651_insert_data extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $root = new Category([ 'name' => 'Каталог' ]);
        $root->makeRoot();
    
        $swim = new Category([ 'name' => 'Плавание' ]);
        $swim->appendTo($root);
    
        $cloth = new Category([ 'name' => 'Одежда' ]);
        $cloth->appendTo($root);
    
        $cap = new Category([ 'name' => 'Шапочки для плавания' ]);
        $cap->appendTo($swim);
    
        $trunks = new Category([ 'name' => 'Плавки' ]);
        $trunks->appendTo($swim);
    
        $swimwear = new Category([ 'name' => 'Купальники' ]);
        $swimwear->appendTo($swim);
    
        $sneakers = new Category([ 'name' => 'Кроссовки' ]);
        $sneakers->appendTo($cloth);
    
        $jackets = new Category([ 'name' => 'Куртки' ]);
        $jackets->appendTo($cloth);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Category::deleteAll();
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190702_083651_insert_data cannot be reverted.\n";

        return false;
    }
    */
}
