<?php

namespace app\components\helpers;

use Yii;

class TransactionHelper
{
    
    /**
     * @param callable $function
     *
     * @throws \Exception
     */
    public static function wrap(callable $function)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $function();
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
        
        Yii::$app->db->close();
    }
    
}
