<?php

namespace Application\Models;



class Dates 
{
    
    //use \Application\Traits\ReadOnly;
    

    public function getMonth($begin = "now"){
            
        
        
        $begin = new \DateTime($begin);
        $end = new \DateTime($begin->format("Y-m-d"));
        $end->modify('+1 month')
                ->modify( '+1 day' );
        
        $interval = new \DateInterval('P1D');

        $daterange = new \DatePeriod($begin, $interval ,$end);
        $daterangeArray = array_reverse(iterator_to_array($daterange));
        
        return $daterangeArray;
    }
    
    
}