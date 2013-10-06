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

class StatementController extends AbstractActionController
{
        
    public function indexAction()
    {
        
        // Requested Date
        $dateTime = new \DateTime($this->params()->fromRoute('datestamp','now'));
        
        // Months surrounding requested Date
        $months = new \DatePeriod(
                new \DateTime("{$dateTime->format('Y M D')} -1 months"),  
                new \DateInterval('P1M'), 
                new \DateTime("{$dateTime->format('Y M D')} +2 months")
                );
        
        // Statement
        $statement = $this->getServiceLocator()->get('Application\Models\Statement');

        /* change period
         * 
         * Should be one month of transactions from last Friday of last month, to last Thursday of this month, payday to payday
         * This is loosely coupled
         */
        $statement->setPeriod(
            new \DatePeriod(
                new \DateTime("{$dateTime->format('Y M D')} last Friday of last month"),
                new \DateInterval('P1D'),
                new \DateTime("{$dateTime->format('Y M D')} last Friday of this month")
            )
        );
            
        //$statement->setPeriod(new \DatePeriod(new \DateTime("now"),new \DateInterval('P1D'),10)); // testing
                                
        // to view
        return new ViewModel(array(
            'statement'     => $statement,
            'today'         => $dateTime,
            'months'        => $months,
            'flashMessages' => $this->flashMessenger()->getMessages(),
            ));
    }
    
    public function rebuildbalanceAction(){
        
        /*
         * Rebuilds balance
         */
        $em         = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $dateDao    = $em->getRepository('Application\Entity\Date');
        $dates      = $dateDao->findAll();
        $total      = count($dates);
        
       
        
        foreach($dates AS $key=>$date){
            $em->persist($date);
            $prevBal = $date->getTransactionTotal();
            $date->setTransactionTotal();
            $currBal = $date->getTransactionTotal();
            $diff = $prevBal - $currBal;
            echo "Processing $key/$total prev:$prevBal current:$currBal diff:$diff id:{$date->getId()}<br />";
            
        }
        $em->flush(); // save

        die('Complete');
        
    }
    
    
        
}
