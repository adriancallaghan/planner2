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
use Zend\View\Helper\HeadScript;

class AnalysisController extends AbstractActionController
{



    public function MonthAction(){
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $dateTime = new \DateTime($this->params()->fromRoute('datestamp','now'));
        
        

        // Months surrounding requested Date
        $months = new \DatePeriod(
                new \DateTime("{$dateTime->format('Y M D')} -6 months"),  
                new \DateInterval('P1M'), 
                new \DateTime("{$dateTime->format('Y M D')} +6 months")
                );
                
        // Years surrounding requested Date
        $years = new \DatePeriod(
                new \DateTime("{$dateTime->format('Y M D')} -1 year"),  
                new \DateInterval('P1Y'), 
                new \DateTime("{$dateTime->format('Y M D')} +2 years")
                );  
                
        
        // Statement
        $statement = $this->getServiceLocator()->get('Application\Models\Statement');

        if ($dateTime > new \DateTime("1st october 2013")){
            $statement->setPeriod(
                new \DatePeriod(
                    new \DateTime("{$dateTime->format('Y M D')} first day of this month"),
                    new \DateInterval('P1D'),
                    new \DateTime("{$dateTime->format('Y M D')} first day of next month")
                )
            );
        } else {
        
            $statement->setPeriod(
                new \DatePeriod(
                    new \DateTime("{$dateTime->format('Y M D')} last Friday of last month"),
                    new \DateInterval('P1D'),
                    new \DateTime("{$dateTime->format('Y M D')} last Friday of this month")
                )
            );
        }
        
        $monthbreakdown = array();
        foreach($statement AS $date) {

		if (count($transactions = $date->getTransactions())>0){
			foreach($transactions AS $transaction) {
				if ($transaction->active && $transaction->amount<0){

					if (!isset($monthbreakdown[$transaction->payment->id])){
						$monthbreakdown[$transaction->payment->id] = array('title'=>"{$transaction->payment->account->name} {$transaction->payment->description}", 'total'=>0);
					}
					$monthbreakdown[$transaction->payment->id]['total']+=(float) $transaction->amount;
				
				}

			}
		}
	}
        

	foreach($months AS $month){

		$in 	= 0;
		$out 	= 0;
		$amounts = $em->createQuery(
		        'SELECT t.amount AS Amount, d.date   
		        FROM Application\Entity\Date d 
		        JOIN d.transactions t 
		        WHERE d.date > ?1 AND d.date < ?2 AND t.active=1 
		        ORDER BY d.date ASC
		        '
		        )
		       ->setParameter(1, new \DateTime("{$month->format('Y M D')} first day of this month"))
		       ->setParameter(2, new \DateTime("{$month->format('Y M D')} first day of next month"))
		       ->getScalarResult(); 
		if ($amounts){
			foreach($amounts as $amount){
				if ($amount['Amount'] > 0){
					$in += $amount['Amount'];
				} else {
					$out += $amount['Amount'];
				}

			}
		}

		
		$ydates[$month->format("U")] = array(
			'In'		=>$in,
			'Out'		=>$out,
			'Profit'	=>$in + $out,
			'Balance'	=>$statement->getBalance($month),
			'Date'		=>$month,
		);
	}
        
        
        
        // to view
        return new ViewModel(array(
                'statement'         => $statement,
                'today'             => $dateTime,
                'months'            => $months,
                'monthbreakdown'    => $monthbreakdown,
                'ydates'             => $ydates,
            ));
        
    } 



























       /*
       MOVED
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
        /*
        $data = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->createQuery(
                  '
                      SELECT p.id, p.description AS Payment,a.name AS Account, SUM(t.amount) AS Amount, AVG(t.amount) AS Average 
                      FROM Application\Entity\Payment p 
                      JOIN p.account a 
                      JOIN p.transactions t 
                      WHERE t.active=1 
                      GROUP BY p.id 
                      ORDER BY Amount DESC
                  '
                  )->getScalarResult();
 
        return new ViewModel(array(
            'data'      => $data
        ));
        
    }
    
    */
    
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
                WHERE t.active=1 
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
                WHERE d.date BETWEEN ?1 AND ?2 AND t.active=1 
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
                WHERE d.date BETWEEN ?1 AND ?2 AND t.active=1 
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
    /*
    MOVED
    public function ActivityAction(){
        
        $em             = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $thisMonth      = new \DateTime($this->params()->fromRoute('datestamp','now'));
        
        $thisMonthData = $em->createQuery(
                'SELECT p.id, p.description AS Payment,a.name AS Account, t.amount AS Amount, d.date AS Date, t.comment as Comment  
                FROM Application\Entity\Payment p 
                JOIN p.account a 
                JOIN p.transactions t 
                JOIN t.date d 
                WHERE d.date < ?1 AND t.active=1 AND p.id=?2 
                ORDER BY t.date ASC
                '
                )
               ->setParameter(1, new \DateTime("{$thisMonth->format('Y M D')} last Friday of this month"))
               ->setParameter(2, $this->params()->fromRoute('id',1))
               ->getScalarResult();
             
               /*
                * 'SELECT SUM(t.amount) AS Amount, a.name as Account   
                FROM Application\Entity\Payment p 
                JOIN p.account a 
                JOIN p.transactions t 
                JOIN t.date d 
                WHERE d.date < ?1 AND t.active=1 AND p.id=?2 
                ORDER BY t.date ASC
                '
                *//*
        $total = $em->createQuery(
                'SELECT SUM(t.amount) AS Amount, a.name as Account, d.date   
                FROM Application\Entity\Date d 
                JOIN d.transactions t 
                JOIN t.payment p 
                JOIN p.account a 
                WHERE d.date < ?1 AND t.active=1 AND p.id=?2 
                ORDER BY d.date ASC
                '
                )
               ->setParameter(1, new \DateTime("{$thisMonth->format('Y M D')} last Friday of this month"))
               ->setParameter(2, $this->params()->fromRoute('id',1))
               ->getScalarResult();       
               
        return new ViewModel(array(
            'transactions'      => $thisMonthData,
            'total'             => $total
        ));
        
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); 
        $payment = $em->getRepository('Application\Entity\Payment')->find($this->params()->fromRoute('id',1));
        
        return new ViewModel(array(
            'payment'      => $payment
        ));
        
    }    */
    
    
    public function yearAction(){
        
	// x3 graphs, balance, amount in, amount out for each month

        // Requested Date
        $dateTime 	= new \DateTime($this->params()->fromRoute('datestamp','now'));
	$em 		= $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
	$statement	= $this->getServiceLocator()->get('Application\Models\Statement');

        // Years surrounding requested Date
        $years = new \DatePeriod(
                new \DateTime("{$dateTime->format('Y M D')} -1 year"),  
                new \DateInterval('P1Y'), 
                new \DateTime("{$dateTime->format('Y M D')} +2 years")
                );
        
       // Months surrounding requested Date
        $months = new \DatePeriod(
                new \DateTime("first day of January {$dateTime->format('Y')}"),  
                new \DateInterval('P1M'), 
                new \DateTime("last day of December {$dateTime->format('Y')}")
                );


	$data = array();

	foreach($months AS $month){

		$in 	= 0;
		$out 	= 0;
		$amounts = $em->createQuery(
		        'SELECT t.amount AS Amount, d.date   
		        FROM Application\Entity\Date d 
		        JOIN d.transactions t 
		        WHERE d.date > ?1 AND d.date < ?2 AND t.active=1 
		        ORDER BY d.date ASC
		        '
		        )
		       ->setParameter(1, new \DateTime("{$month->format('Y M D')} first day of this month"))
		       ->setParameter(2, new \DateTime("{$month->format('Y M D')} first day of next month"))
		       ->getScalarResult(); 
		if ($amounts){
			foreach($amounts as $amount){
				if ($amount['Amount'] > 0){
					$in += $amount['Amount'];
				} else {
					$out += $amount['Amount'];
				}

			}
		}

		
		$data[$month->format("U")] = array(
			'In'		=>$in,
			'Out'		=>$out,
			'Profit'	=>$in + $out,
			'Balance'	=>$statement->getBalance($month),
			'Date'		=>$month,
		);
	}
	
                            
        // to view
        return new ViewModel(array(
            'data'     => $data,
            'today'         => $dateTime,
    	    'years'         => $years,
            ));
        
    } 


    
        
}
