<?php

namespace app\components\models;

class Status
{
    
    const STATUS_DISABLE = 0;
    
    const STATUS_ACTIVE = 1;
    
    public static function getStatusesArray()
    {
        return [
            self::STATUS_DISABLE => 'Выкл',
            self::STATUS_ACTIVE => 'Вкл',
        ];
    }
    
    public static function getStatusCssClass()
    {
        return [
            self::STATUS_DISABLE => 'danger',
            self::STATUS_ACTIVE => 'success',
        ];
    }
    
}