<?php

namespace app\components\cart\calculator;

interface CalculatorInterface
{
    
    /**
     * @param \devanych\cart\CartItem[] $items
     *
     * @return integer
     */
    public function getCost(array $items);
    
    /**
     * @param \devanych\cart\CartItem[] $items
     *
     * @return integer
     */
    public function getCount(array $items);
}
