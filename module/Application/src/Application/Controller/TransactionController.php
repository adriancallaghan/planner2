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
use Application\Form\TransactionForm;       
use DoctrineModule\Paginator\Adapter\Collection as Adapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections;



class TransactionController extends AbstractActionController
{

    
    public function indexAction()
    {       
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $transactionDao = $em->getRepository('Application\Entity\Transaction');
        
        $transactions = $transactionDao->findAll();

        $collection = new Collections\ArrayCollection($transactions);

        // Create the paginator itself
        $paginator = new Paginator(new Adapter($collection));
        
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator
                ->setCurrentPageNumber(
                    (int)$this->params()->fromQuery('page', 1)
                )
                ->setItemCountPerPage(10);

        
        return new ViewModel(array(
            'paginator' => $paginator,
            'title'     => 'Transactions',
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));

    }
    
    public function addAction()
    {

        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager

        $dateDao = $em->getRepository('Application\Entity\Date');       
        $paymentDao = $em->getRepository('Application\Entity\Payment');           

        
        $dateTitles = array();
        foreach ($dateDao->findAll() AS $v){
            $dateTitles[$v->getId()] = "{$v->getDate()->format('Y-m-d')}";
        }        
                
        $paymentTitles = array();
        foreach ($paymentDao->findAll() AS $v){
            $paymentTitles[$v->id] = "{$v->account->name} - {$v->description}";
        }

        $transaction = new \Application\Entity\Transaction();
        $form = new TransactionForm();      
        $form->get('date_id')
                ->setValue((int) $this->params()->fromRoute('id', 0)) // defaults to the route
                ->setAttributes(array('options'=>$dateTitles));
        $form->get('payment_id')
                ->setValue((int) 0) // defaults to zero
                ->setAttributes(array('options'=>$paymentTitles));
        $form->setInputFilter($transaction->getInputFilter())
            ->setData($this->getRequest()->getPost())
            ->get('submit')->setValue('Add');

        
        if ($this->getRequest()->isPost() && $form->isValid()) {  

            $paymentId = (int) $form->get('payment_id')->getValue();
            $payment = $paymentDao->find($paymentId);  
            
            $dateId = (int) $form->get('date_id')->getValue();
            $date = $dateDao->find($dateId);  
            
            $transaction->setOptions($form->getData()); // set the data     
            
                        
            if (!$payment){
                
                $this->flashMessenger()->addMessage(array('alert-error'=>'Error! Payment not found!')); 
                
            } elseif (!$date){
                
                $this->flashMessenger()->addMessage(array('alert-error'=>'Error! Date not found!')); 
                
            } else {    
                
                //$em->persist($payment); // set data                
                $em->persist($date); // set data
                //$em->persist($transaction); // set data
                
                $payment->addTransaction($transaction);
                $date->addTransaction($transaction);
                
                $em->flush(); // save            
                $this->flashMessenger()->addMessage(array('alert-success'=>'Transaction added!'));
                
            }

            // Redirect to list of transactions
            //return $this->redirect()->toRoute('transactions');
            return $this->redirect()->toRoute('home');
        }
        
        
        return array('form' => $form);
    }

       
    // Add content to this method:
    public function editAction()
    {
       
        
        $id = (int) $this->params()->fromRoute('id', 0); // id that we editing, defaults to zero
        $form  = new TransactionForm(); // form used for the edit
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        $dateDao = $em->getRepository('Application\Entity\Date');       
        $paymentDao = $em->getRepository('Application\Entity\Payment'); 
        $transaction = $em->getRepository('Application\Entity\Transaction')->find($id);
        
        // if we do not have an entry for the transaction, i.e id not found or not defined
        // send them to add
        if (!$transaction) {
            return $this->redirect()->toRoute('transactions', array(
                'action' => 'add'
            ));
        }
        
        $dateTitles = array();
        foreach ($dateDao->findAll() AS $v){
            $dateTitles[$v->getId()] = "{$v->getDate()->format('Y-m-d')}";
        }        
                
        $paymentTitles = array();
        foreach ($paymentDao->findAll() AS $v){
            $paymentTitles[$v->id] = "{$v->account->name} - {$v->description}";
        }


        // setup form
        // (validation, data and button)        
        $form->setInputFilter($transaction->getInputFilter())
                ->setData($transaction->toArray())
                ->get('submit')->setAttribute('value', 'Edit');
        
        $form->get('payment_id')
                ->setValue($transaction->payment->id)
                ->setAttributes(array('options'=>$paymentTitles));
                
        $form->get('date_id')
                ->setValue($transaction->date->getId())
                ->setAttributes(array('options'=>$dateTitles));
        
        
        // process a submission
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->getRequest()->getPost()); // set the form with the submitted values

            // is valid?
            if ($form->isValid()) {
                
                
                $paymentId = (int) $form->get('payment_id')->getValue();
                $payment = $paymentDao->find($paymentId);  

                $dateId = (int) $form->get('date_id')->getValue();
                $date = $dateDao->find($dateId);  

                
                $transaction->setOptions($form->getData()); // set the data     


                if (!$payment){

                    $this->flashMessenger()->addMessage(array('alert-error'=>'Error! Payment not found!')); 

                } elseif (!$date){

                    $this->flashMessenger()->addMessage(array('alert-error'=>'Error! Date not found!')); 

                } else {    

                    //$em->persist($payment); // set data                
                    $em->persist($date); // set data
                    //$em->persist($transaction); // set data

                    $payment->addTransaction($transaction);                    
                    $date->addTransaction($transaction);
                    
                    $em->flush(); // save            
                    $this->flashMessenger()->addMessage(array('alert-success'=>'Transaction Updated!'));

                }
                
                //return $this->redirect()->toRoute('transactions');
                return $this->redirect()->toRoute('home');


            } else {
                //$this->flashMessenger()->addMessage(array('alert-error'=>'Form error'));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        
        $id = (int) $this->params()->fromRoute('id', 0); // id that we deleting, defaults to zero
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        $transaction = $em->getRepository('Application\Entity\Transaction')->find($id);
        
        
        // if we do not have an entry for the payment, i.e id not found or not defined
        // send them back to the main screen
        if (!$transaction) {
            return $this->redirect()->toRoute('transactions');
        }
        

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('del', 'No') == 'Yes') {
  
            $em->remove($transaction); // boom biddy bye bye
            $em->flush();
                 
            $this->flashMessenger()->addMessage(array('alert-info'=>'Deleted'));

            // Redirect to list of payments
            //return $this->redirect()->toRoute('transactions');
            return $this->redirect()->toRoute('home');
        }

        return array(
            'id'    => $id,
            'transaction' => $transaction
        );
    }
        
    
}
