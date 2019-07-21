<?php


namespace app\components\cart\storage;


use app\components\cart\CartItem;
use Yii;
use yii\helpers\Json;
use yii\web\Cookie;

class CookieStorage
    implements StorageInterface
{
    
    /**
     * @var array $params Custom configuration params
     */
    private $params;
    
    public function __construct(array $params)
    {
        $this->params = $params;
    }
    
    /**
     * @return CartItem[]
     */
    public function load()
    {
        if ($cookie = Yii::$app->request->cookies->get($this->params['key'])) {
            return array_filter(array_map(function (array $row)
            {
                if (isset($row['id'], $row['quantity']) && $product = $this->findProduct($row['id'])) {
                    return new CartItem($product, $row['quantity'], $this->params);
                }
                
                return false;
            }, Json::decode($cookie->value)));
        }
        
        return [];
    }
    
    /**
     * @param integer $productId
     *
     * @return object|null
     */
    private function findProduct($productId)
    {
        return $this->params['productClass']::find()
                                            ->where([ $this->params['productFieldId'] => $productId ])
                                            ->limit(1)
                                            ->one();
    }
    
    /**
     * @param CartItem[] $items
     *
     * @return void
     */
    public function save(array $items)
    {
        Yii::$app->response->cookies->add(new Cookie([
                                                         'name' => $this->params['key'],
                                                         'value' => Json::encode(array_map(function (CartItem $item)
                                                         {
                                                             return [
                                                                 'id' => $item->getId(),
                                                                 'quantity' => $item->getQuantity(),
                                                             ];
                                                         }, $items)),
                                                         'expire' => time() + $this->params['expire'],
                                                     ]));
    }
}