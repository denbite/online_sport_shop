<?php

namespace app\components\helpers;

use Yii;

class ValueHelper
{
    
    const  SECRET_KEY = 234;
    
    
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
    
    /**
     * Format price
     *
     * @param int $price
     *
     * @return string
     */
    public static function formatPrice($price)
    {
        return '₴ ' . round($price * Yii::$app->params['priceMultiplier'], -1);
    }
    
}