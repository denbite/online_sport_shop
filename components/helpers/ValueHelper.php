<?php

namespace app\components\helpers;

class ValueHelper
{
    
    const  SECRET_KEY = 574;
    
    
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
    
}