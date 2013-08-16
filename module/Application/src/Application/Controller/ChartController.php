<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\Common\Collections;

class ChartController extends AbstractActionController
{
       
    public function indexAction(){
        
        
        /*
         * 
         * 
         * need all items for this month
         * foreach item, find the average spent per month
         * 
         * producing two lines, this month, and the avergae
         * 
         */
        
        // Requested Date
        $dateTime = new \DateTime($this->params()->fromRoute('datestamp','now'));
        
        
        $firstFriday    = new \DateTime("{$dateTime->format('Y M D')} last Friday of last month");
        $lastFriday     = new \DateTime("{$dateTime->format('Y M D')} last Friday of this month");
            
        
                
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        
        $dql = "SELECT SUM(e.transactionTotal) AS balance FROM Application\Entity\Date e WHERE e.date BETWEEN ?1 AND ?2";
        $balance = $em->createQuery($dql)
                      ->setParameter(1, $firstFriday)
                      ->setParameter(2, $lastFriday)
                      ->getSingleScalarResult();        
        
        var_dump($balance);
        
        die;
        
        
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $accountEntity = $em->getRepository('Application\Entity\Account');

        $accounts = $accountEntity->findAll();
        
        $labels = array();
        $data = array();
        
        foreach($accounts AS $account){
            
            $payments   = $account->getPayments();
            foreach($payments AS $payment){                
                          
                $total = 0;
                foreach($payment->getTransactions() AS $transaction){
                    $amount = $transaction->amount;
                    if ($amount<0){
                        $total += abs($amount);
                    }
                }
                
                
                if ($total>0){
                    $labels[]   = $payment->description;
                    $data[]     = $total;
                }
            }
            
        }
        
        return new ViewModel(array(
            'labels'    => $labels,
            'data'    => $data,
            //'accounts' => $accountsCollection,
        ));
    
    }
    
    
    
    /*public function indexAction()
    {
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $accountEntity = $em->getRepository('Application\Entity\Account');

        $accounts = $accountEntity->findAll();
        
        $labels = array();
        $data = array();
        
        foreach($accounts AS $account){
            
            $payments   = $account->getPayments();
            foreach($payments AS $payment){                
                          
                $total = 0;
                foreach($payment->getTransactions() AS $transaction){
                    $amount = $transaction->amount;
                    if ($amount<0){
                        $total += abs($amount);
                    }
                }
                
                
                if ($total>0){
                    $labels[]   = $payment->description;
                    $data[]     = $total;
                }
            }
            
        }
        
        return new ViewModel(array(
            'labels'    => $labels,
            'data'    => $data,
            //'accounts' => $accountsCollection,
        ));
    
    }*/

    
        
}
