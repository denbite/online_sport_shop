<?php

namespace app\components\cart\calculator;

class SimpleCalculator
    implements CalculatorInterface
{
    
    /**
     * @param \devanych\cart\CartItem[] $items
     * @param boolean                   $purchase
     *
     * @param bool                      $withPromotion
     *
     * @return integer
     */
    public function getCost(array $items, $purchase = false, $withPromotion = true)
    {
        $cost = 0;
        foreach ($items as $item) {
            $cost += $item->getCost($purchase, $withPromotion);
        }
        
        return $cost;
    }
    
    /**
     * @param \devanych\cart\CartItem[] $items
     *
     * @return integer
     */
    public function getCount(array $items)
    {
        $count = 0;
        foreach ($items as $item) {
            $count += $item->getQuantity();
        }
        
        return $count;
    }
}
