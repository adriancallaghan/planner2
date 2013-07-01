<?php

namespace Application\Models;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Statement implements \Iterator, \Countable, ServiceLocatorAwareInterface
{

    use \Application\Traits\Iterable;
    
        
    protected $serviceLocator;
    // protected $_cache;

    public function __construct(\DatePeriod $datePeriod = null, $reverse = false) {
        
        if ($datePeriod==null){
            $start = new \DateTime("last Friday of last month");
            $end = new \DateTime("last Friday of this month");
            $datePeriod = new \DatePeriod($start, new \DateInterval('P1D') ,$end);
        }
        
        return $this->setPeriod($datePeriod, $reverse);
    }
        
    public function setPeriod(\DatePeriod $datePeriod, $reverse = false) {
        
        $datePeriod = iterator_to_array($datePeriod);

        if ($reverse){
            $datePeriod = array_reverse($datePeriod);
        }
        
        $this->setData($datePeriod);
        
        return $this;
    }
    
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }
    
    /*    
    public function getCache(){}    
    public function setCache(){}    
    public function clearCache(){}
    public function purgeCache(){}
    */

    
    /*
     * Override iterable current method
     */
    public function current(){
        
        // override iterable, to build an object and return it
        if ($this->valid()){            
            $data = $this->getData();
            $date = $data[$this->_position];
            return $this->getDate($date);          
        }
    }

    /*
     * toArray
     */
    public function toArray(){
         
        /*$articleIds = $this->getData();
        $return = null;
        
        if (count($articleIds)>0){
                        
            foreach($articleIds AS $articleId){
                $return[] = $this->getArticle($articleId)->toArray();
            }
            
        }
        
        return $return;*/
    }
    
    
    // accessible 
    public function getDate(\DateTime $dateTime){
        
        
        /*
         *  check cache here, return date if found
         */

        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');        

        
        /* fetch date - if not exists create a new date entry */
        if (!$date = $em->getRepository('Application\Entity\Date')->findOneBy(array('date'=>$dateTime))){
            
            
            // create a date entry
            $date = new \Application\Entity\Date(array('date'=>$dateTime));
            $em->persist($date); 

            
            // get weekly payments for this day
            $weeklyPayments = $em->getRepository('Application\Entity\Payment')
                    ->findBy(array(
                        'frequency'=>1,
                        'day'=>$dateTime->format('N'),
                        'active'=>'1'
                        ));
            
            // if there are some weekly payments add them to this date
            if ($weeklyPayments){
                foreach($weeklyPayments AS $weeklyPayment){
                    
                    // create a transaction and persist
                    $transaction = new \Application\Entity\Transaction(array(
                        'date'=>$date,
                        'payment'=>$weeklyPayment,
                        'amount'=>$weeklyPayment->amount,
                        'active'=>$weeklyPayment->active,
                    ));
                    
                    $em->persist($transaction); 
                    
                    // assign to the payments and persist
                    $weeklyPayment->addTransaction($transaction);
                    $em->persist($weeklyPayment);
                    
                    // already persistant
                    $date->addTransaction($transaction);

                }
            }

            
            // get monthly payments for this day
            $monthlyPayments = $em->getRepository('Application\Entity\Payment')
                    ->findBy(array(
                        'frequency'=>2,
                        'day'=>$dateTime->format('j'),
                        'active'=>'1'
                        ));
            
            
            // if there are some monthly payments add them to this date
            if ($monthlyPayments){
                foreach($monthlyPayments AS $monthlyPayment){
                    
                    // create a transaction and persist
                    $transaction = new \Application\Entity\Transaction(array(
                        'date'=>$date,
                        'payment'=>$monthlyPayment,
                        'amount'=>$monthlyPayment->amount,
                        'active'=>$monthlyPayment->active,
                    ));
                    
                    $em->persist($transaction); 
                    
                    // assign to the payments and persist
                    $monthlyPayment->addTransaction($transaction);
                    $em->persist($monthlyPayment);
                    
                    // already persistant
                    $date->addTransaction($transaction);

                }
            }


            $em->flush(); // save   
            
        }

        
        /*
         *  save date to cache here, before returning date
         */
        return $date;

    }
    

 
}

