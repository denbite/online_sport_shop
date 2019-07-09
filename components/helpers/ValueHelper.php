<?php

namespace app\components\helpers;

use Yii;

class ValueHelper
{
    
    const  SECRET_KEY = 0;
    
    
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
     * @param $x
     *
     * @return string
     */
    public static function formatPrice($x)
    {
        return '₴ ' . round($x * Yii::$app->params['priceMultiplier'], -1);
    }
    
}