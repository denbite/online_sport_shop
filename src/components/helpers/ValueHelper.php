<?php

namespace app\components\helpers;

use app\models\Config;
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
    
    public static function getMultiplier()
    {
        $param = Config::find()->select('value')->where([ 'name' => 'priceMultiplier' ])->asArray()->one();
        
        if (is_array($param) and array_key_exists('value', $param)) {
            $multiplier = $param['value'];
        }
        
        if (empty($multiplier)) {
            $multiplier = Yii::$app->params['priceMultiplier'];
        }
        
        return $multiplier;
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
     * Format price
     *
     * @param int $price
     *
     * @return string
     */
    public static function outPrice($price)
    {
        return '₴ ' . $price;
    }
    
}