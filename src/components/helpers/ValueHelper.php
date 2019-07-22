<?php

namespace app\components\helpers;

use app\models\Config;
use app\models\ItemColorSize;
use app\models\Promotion;
use Yii;

class ValueHelper
{
    
    const  SECRET_KEY = 234;
    
    const PRICE_PROMOTION = 1;
    
    const PRICE_WITHOUT_PROMOTION = 2;
    
    /**
     * Encrypt index in url
     *
     * @param $x
     *
     * @return null|int
     */
    public static function encryptValue($x)
    {
        return ( $x = (int) $x and $x > 0 ) ? $x + self::SECRET_KEY : null;
    }
    
    public static function getMultiplier()
    {
        $param = Config::find()->select('value')->where([ 'name' => 'priceMultiplier' ])->asArray()->one();
        
        if (is_array($param) and array_key_exists('value', $param)) {
            $multiplier = $param['value'];
        }
    
        if (empty($multiplier) and array_key_exists('priceMultiplier', Yii::$app->params)) {
            $multiplier = Yii::$app->params['priceMultiplier'];
        }
    
        return !empty($multiplier) ? $multiplier : 1;
    }
    
    /**
     * Decrypt index from url
     *
     * @param $x
     *
     * @return null|int
     */
    public static function decryptValue($x)
    {
        return ( $x = (int) $x and $x > self::SECRET_KEY ) ? $x - self::SECRET_KEY : null;
    }
    
    public static function format($price)
    {
        return round($price * self::getMultiplier(), -1);
    }
    
    /**
     * Add currency to the front of price
     *
     * @param $price
     *
     * @return string
     */
    public static function addCurrency($price)
    {
        return "₴ {$price}";
    }
    
    /**
     * Check for this item's actual promotion and return formatted price
     *
     * @param     $size
     * @param     $promotion
     * @param int $type u can choose what type of return price u need
     *
     * @return string
     */
    public static function outPrice($size, $promotion, $type = self::PRICE_PROMOTION)
    {
        if ($type == self::PRICE_PROMOTION and !empty($promotion) and !empty($size)) {
            if ($promotion['type'] == Promotion::TYPE_PERCENT) {
            
                $m = (float) ( 100 - $promotion['sale'] ) / 100;
            
                if ($size['sale_price'] != self::format($size['base_price'] * $m)) {
                    ItemColorSize::updateAll([ 'sale_price' => self::format($size['base_price'] * $m) ],
                        [ 'id' => $size['id'] ]);
                }
            
                return self::format($size['base_price'] * $m);
            
            } elseif ($promotion['type'] == Promotion::TYPE_VALUE) {
            } else {
                // check if equal sell_price and base_price * nulti, if not -> change it and save
                return $size['sell_price'];
            }
        }
    
        return null;
    }
    
}