<?php

namespace Application\Models;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Statement implements \Iterator, \Countable, ServiceLocatorAwareInterface
{

    use \Application\Traits\Iterable;

    protected $_serviceLocator;
    protected $_datePeriod;
    protected $_reversed;
    protected $_statementTitle;
    protected $_account;
        
        
    public function getServiceLocator() {
        return $this->_serviceLocator;
    }
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->_serviceLocator = $serviceLocator;
    }

    public function getAccount(){        
        if (!isset($this->_account)){
            throw new \Exception('Account not defined in statement');
        }
        return $this->_account;
    }
    
    public function setAccount(\Application\Entity\Account $account) {
        
        $this->_account = $account;
        return $this;
    }
    
    
    public function getPeriod(){        
        if (!isset($this->_datePeriod)){
            $this->setPeriod(new \DatePeriod(new \DateTime("now"),new \DateInterval('P1D'),7));
        }
        return $this->_datePeriod;
    }
    
    public function setPeriod(\DatePeriod $datePeriod, $reversed = false) {
        /*
         * Main query method for object
         */
        $this->_datePeriod = $datePeriod;
        $this->_reversed = $reversed;

        $datePeriod = iterator_to_array($this->_datePeriod);

        if ($this->_reversed){
            $datePeriod = array_reverse($datePeriod);
        }

        $dates = array();

        // must be fetched first, (generated) for the balance method to be accurate
        foreach($datePeriod AS $dateTime){            
            $dates[] = $this->fetchDate($dateTime);
        }
        
        $this->setData($dates);
        
        return $this;
    }
    
    public function getBalance(\DateTime $dateTime){
        /*
         * returns balance on any given day
         * 
         */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        $account = $this->getAccount();
        
        
        $dql = "SELECT SUM(e.transactionTotal) AS balance FROM Application\Entity\Date e WHERE e.date <=?1 AND e.account = ?2";
        $balance = $em->createQuery($dql)
                      ->setParameter(1, $dateTime)
                      ->setParameter(2, $account)
                      ->getSingleScalarResult();
        
       
        return $balance + $this->getAccount()->getStartBalance();
        //return $balance - "381.22"; // app start balance
        
    }

    public function getStatementTitle(){        
        if (!isset($this->_statementTitle)){
            $this->setStatementTitle();
        }
        return (string) $this->_statementTitle;
    }
    
    public function setStatementTitle($title = null){
        
        if ($title==null){
 
            $datePeriod = iterator_to_array($this->getPeriod());

            if ($this->_reversed){
                $datePeriod = array_reverse($datePeriod);
            }

            $from = reset($datePeriod);
            $to = end($datePeriod);
            $endBalance = number_format($this->getBalance($to),2);
            $days = count($datePeriod);
            $s = $days==1 ? '': '`s';
            $account = $this->getAccount();
            
            // _statementTitle
            //$title = "{$from->format('D jS M')} to {$to->format('D jS M')} &pound;$endBalance ($days day$s)";
            //$title = "{$from->format('D jS M')} to {$to->format('D jS M')}";
            $title = "{$from->format('D jS M')} to {$to->format('D jS M')} ({$account->name})";
            
        }
        
        $this->_statementTitle = $title;
        
        return $this;
    }

    public function fetchDate(\DateTime $dateTime){
        
        
        /*
         *  check cache here, return date if found
         */

        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');        

     
        
        $account = $this->getAccount();
        
        /* fetch date - if not exists create a new date entry */       
        if (!$date = $em->getRepository('Application\Entity\Date')->findOneBy(array('date'=>$dateTime,'account'=>$account->id))){

            // create a date entry
            $date = new \Application\Entity\Date();
            $date->setDate($dateTime);
            $date->setAccount($account);       
            $em->persist($date); 

            
            // get weekly payments for this day
            $weeklyPayments = $em->getRepository('Application\Entity\Payment')
                    ->findBy(array(
                        'frequency'=>1,
                        'day'=>$dateTime->format('N'),
                        'active'=>'1',  
                        'payee'=>$this->getAccount(),
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
                        'active'=>'1',
                        'payee'=>$this->getAccount(),
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

