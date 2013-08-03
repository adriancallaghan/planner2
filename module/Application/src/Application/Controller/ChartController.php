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
        
    public function indexAction()
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
    
    }
    
        
}
