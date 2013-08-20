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
//use Doctrine\Common\Collections;

class ChartController extends AbstractActionController
{
       
    public function indexAction(){
        
        
        /*
         * overall data
         */

        /*
        SELECT p.id, p.description AS Payment,a.name AS Account, SUM(t.amount) AS Amount 
        FROM payment p 
        LEFT JOIN account a ON p.account_id = a.id 
        LEFT JOIN transaction t ON t.payment_id = p.id 
        LEFT JOIN `date` d ON t.date_id = d.id  
        GROUP BY p.id 
        ORDER BY Amount DESC
        */
        
        $data = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->createQuery(
                  '
                      SELECT p.id, p.description AS Payment,a.name AS Account, SUM(t.amount) AS Amount, AVG(t.amount) AS Average 
                      FROM Application\Entity\Payment p 
                      JOIN p.account a 
                      JOIN p.transactions t 
                      GROUP BY p.id 
                      ORDER BY Amount DESC
                  '
                  )->getScalarResult();
 
        return new ViewModel(array(
            'data'      => $data
        ));
        
    }
    
    
    
    public function AverageAction(){
        
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); 
        
        // Requested Date
        $thisMonth      = new \DateTime($this->params()->fromRoute('datestamp','now'));
        $lastMonth      = new \DateTime($this->params()->fromRoute('datestamp','now'));
        $lastMonth->modify('-1 month');
        
        // Months surrounding requested Date
        $months = new \DatePeriod(
                new \DateTime("{$thisMonth->format('Y M D')} -1 months"),  
                new \DateInterval('P1M'), 
                new \DateTime("{$thisMonth->format('Y M D')} +2 months")
                );
        
        
        $avgMonthData = $em->createQuery(
                'SELECT p.id, p.description AS Payment,a.name AS Account, AVG(t.amount) AS Amount 
                FROM Application\Entity\Payment p 
                JOIN p.account a 
                JOIN p.transactions t 
                GROUP BY p.id 
                ORDER BY p.id ASC
                '
                )->getScalarResult();
               
        $thisMonthData = $em->createQuery(
                'SELECT p.id, p.description AS Payment,a.name AS Account, SUM(t.amount) AS Amount 
                FROM Application\Entity\Payment p 
                JOIN p.account a 
                JOIN p.transactions t 
                JOIN t.date d 
                WHERE d.date BETWEEN ?1 AND ?2 
                GROUP BY p.id 
                ORDER BY p.id ASC
                '
                )
               ->setParameter(1, new \DateTime("{$thisMonth->format('Y M D')} last Friday of last month"))
               ->setParameter(2, new \DateTime("{$thisMonth->format('Y M D')} last Friday of this month"))
               ->getScalarResult();
        
        $lastMonthData = $em->createQuery(
                'SELECT p.id, p.description AS Payment,a.name AS Account, SUM(t.amount) AS Amount 
                FROM Application\Entity\Payment p 
                JOIN p.account a 
                JOIN p.transactions t 
                JOIN t.date d 
                WHERE d.date BETWEEN ?1 AND ?2 
                GROUP BY p.id 
                ORDER BY p.id ASC
                '
                )
               ->setParameter(1, new \DateTime("{$lastMonth->format('Y M D')} last Friday of last month"))
               ->setParameter(2, new \DateTime("{$lastMonth->format('Y M D')} last Friday of this month"))
               ->getScalarResult();
        

        /*
         * format data
         */
        $labels = array();
        $data   = array();
        $excludes = array(12,10,11);
        function getPayment($payments, $paymentId){
            foreach($payments AS $payment){
                if ($payment['id']==$paymentId){
                    return $payment['Amount'];
                }
            }  
            return 0;
        }
        
        
                  
        foreach($thisMonthData AS $payment){
            
            if (in_array($payment['id'], $excludes)){
                continue;
            }
            
            $am     = floor(getPayment($avgMonthData,  $payment['id']));
            $lm     = floor(getPayment($lastMonthData, $payment['id']));
            $tm     = floor(getPayment($thisMonthData, $payment['id']));
            
            // label
            $labels[$payment['id']]         = "{$payment['Account']} {$payment['Payment']}";
             
            $data['this'][$payment['id']]   = $am - $tm;
            $data['last'][$payment['id']]   = $am - $lm;
            $data['avg'][$payment['id']]    = $am;
            
        }
        
        
        return new ViewModel(array(
            'labels'    => $labels,
            'data'      => $data,
            'months'    => $months,
            'today'     => $thisMonth
        ));
    
    }
    
    
    public function ActivityAction(){
        
                
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $paymentDao = $em->getRepository('Application\Entity\Payment');
 
        $payment = $em->getRepository('Application\Entity\Payment')->find($this->params()->fromRoute('id',0));
        
        return new ViewModel(array(
            'payment'      => $payment
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
