<?php

namespace app\components\helpers;

use app\models\Config;
use app\models\ItemColorSize;
use app\models\Promotion;
use Yii;

class ValueHelper
{
    
    const  SECRET_KEY = 234;
    
    const CACHE_MULTIPLIER = 'config_price_multiplier';
    
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
        return Yii::$app->cache->getOrSet(self::CACHE_MULTIPLIER, function ()
        {
            $param = Config::find()->select('value')->where([ 'name' => 'priceMultiplier' ])->asArray()->one();
    
            if (is_array($param) and array_key_exists('value', $param)) {
                $multiplier = $param['value'];
            }
    
            if (empty($multiplier) and array_key_exists('priceMultiplier', Yii::$app->params)) {
                $multiplier = Yii::$app->params['priceMultiplier'];
            }
    
            return !empty($multiplier) ? $multiplier : 1;
        }, 300);
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
        return (int) $price >= 0 ? '₴ ' . number_format((int) $price, 0, ' ', ' ') : null;
    }
    
    /**
     * Check for this item's actual promotion and return formatted price in product page
     *
     * @param     $size
     * @param     $promotion
     *
     * @return string|null
     */
    public static function outPriceProduct($size, $promotion)
    {
        //todo-cache: 5 min
        if (!empty($promotion) and !empty($size)) {
            return self::addCurrency(self::verifySalePrice($size, $promotion));
        } elseif (!empty($size)) {
            return self::addCurrency(self::verifySellPrice($size));
        }
        
        return null;
    }
    
    /**
     * Check for this item's actual promotion and return formatted price in catalog
     *
     * @param     $colors
     * @param     $promotion
     *
     * @return string
     */
    public static function outPriceCatalog($colors, $promotion)
    {
        //todo-cache: 5 min
        $min_price = -1;
    
        if (!empty($promotion) and !empty($colors)) {
            foreach ($colors as $color) {
                foreach ($color['allSizes'] as $size) {
                    if ($min_price < 0 and $size['quantity'] > 0) {
                        $min_price = self::verifySalePrice($size, $promotion);
                    } elseif ($min_price > self::verifySalePrice($size, $promotion) and $size['quantity'] > 0) {
                        $min_price = self::verifySalePrice($size, $promotion);
                    }
                }
            }
    
            return ( $min_price > 0 ) ? self::addCurrency($min_price) : null;
        } elseif (!empty($colors)) {
            foreach ($colors as $color) {
                foreach ($color['allSizes'] as $size) {
                    if ($min_price < 0 and $size['quantity'] > 0) {
                        $min_price = self::verifySellPrice($size);
                    } elseif ($min_price > self::verifySellPrice($size) and $size['quantity'] > 0) {
                        $min_price = self::verifySellPrice($size);
                    }
                }
            }
    
            return ( $min_price > 0 ) ? self::addCurrency($min_price) : null;
        }
    
        return null;
    }
    
    public static function verifySalePrice($size, $promotion)
    {
        if ($promotion['type'] == Promotion::TYPE_PERCENT) {
            
            $m = (float) ( 100 - $promotion['sale'] ) / 100;
            
            if (array_key_exists('sale_price', $size) and array_key_exists('base_price',
                                                                           $size)) {
                if ($size['sale_price'] != self::format($size['base_price'] * $m)) {
                    ItemColorSize::updateAll([ 'sale_price' => self::format($size['base_price'] * $m) ],
                                             [ 'id' => $size['id'] ]);
                    $size['sale_price'] = self::format($size['base_price'] * $m);
                }
                
                return $size['sale_price'];
            }
            
        } elseif ($promotion['type'] == Promotion::TYPE_VALUE) {
            
            $diff = $promotion['sale'];
            
            if (array_key_exists('sale_price', $size) and array_key_exists('base_price',
                                                                           $size)) {
                if ($size['sale_price'] != self::format($size['base_price']) - $diff) {
                    ItemColorSize::updateAll([ 'sale_price' => self::format($size['base_price']) - $diff ],
                                             [ 'id' => $size['id'] ]);
                    $size['sale_price'] = self::format($size['base_price']) - $diff;
                }
                
                return $size['sale_price'];
            }
        }
        
        return null;
    }
    
    public static function verifySellPrice($size)
    {
        if (array_key_exists('sell_price', $size) and array_key_exists('base_price',
                                                                       $size)) {
            if ($size['sell_price'] != self::format($size['base_price'])) {
                ItemColorSize::updateAll([ 'sell_price' => self::format($size['base_price']) ],
                                         [ 'id' => $size['id'] ]);
                $size['sell_price'] = self::format($size['base_price']);
            }
            
            return ( $size['sell_price'] );
        }
        
        return null;
    }
    
    public static function getDelivery($price)
    {
        return ( !empty($price) and (int) $price > 1000 ) ? 'Бесплатно' : 'По тарифам перевозчика';
    }
    
}