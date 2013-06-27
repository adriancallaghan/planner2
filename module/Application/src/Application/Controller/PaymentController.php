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
use Application\Form\PaymentForm;       
use DoctrineModule\Paginator\Adapter\Collection as Adapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections;



class PaymentController extends AbstractActionController
{

    
    public function indexAction()
    {       
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $paymentDao = $em->getRepository('Application\Entity\Payment');
        
        $payments = $paymentDao->findAll();

        $collection = new Collections\ArrayCollection($payments);

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
            'title'     => 'Payments',
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));

    }
    
    public function addAction()
    {

        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager

        $accountDao = $em->getRepository('Application\Entity\Account');       

        /*
         * so far findAll() seems somewhat limited, it returns just an array with no relationship to the primary keys
         * Index By can used by this does not work with findAll and appears to require crafting a full query - which seems
         * a bit backward!
         * 
         * initial flow was to cast findAll to an array collection to allow mapping, then looping through returning the property I wanted
         * and mantaining the keys - however the keys are just an array and have little to do with the data - so no go :(
         * 
         * $accounts = new Collections\ArrayCollection($accountDao->findAll()); 
         * 
         *  $form->get('account_id')->setAttributes(
            array(
                'options'=>$accounts->map(function($v){
                    return "{$v->title} ({$v->artist})";            
                    })->toArray()
                )
        );
         * 
         * Brute forced until I learn more..... 
         */
        $accountTitles = array();
        foreach ($accountDao->findAll() AS $v){
            $accountTitles[$v->id] = "{$v->name}";
        }

        $payment = new \Application\Entity\Payment();
        $form = new PaymentForm();      
        $form->get('account_id')
                ->setValue((int) $this->params()->fromRoute('id', 0)) // defaults to the route
                ->setAttributes(array('options'=>$accountTitles));
        $form->setInputFilter($payment->getInputFilter())
            ->setData($this->getRequest()->getPost())
            ->get('submit')->setValue('Add');

        
        if ($this->getRequest()->isPost() && $form->isValid()) {  
            
            $accountId = (int) $form->get('account_id')->getValue();
            $account = $accountDao->find($accountId);                        
            $payment->setOptions($form->getData()); // set the data     
            
            if ($account){
                $em->persist($account); // set data
                $em->persist($payment); // set data
                $account->addPayment($payment);
                $em->flush(); // save            
                $this->flashMessenger()->addMessage(array('alert-success'=>'Payment added!'));  
            }
            else {
                $this->flashMessenger()->addMessage(array('alert-error'=>'Error! Account not found!')); 
            }

            // Redirect to list of payments
            return $this->redirect()->toRoute('payments');
        }
        
        
        return array('form' => $form);
    }

       
    // Add content to this method:
    public function editAction()
    {
       
        
        $id = (int) $this->params()->fromRoute('id', 0); // id that we editing, defaults to zero
        $form  = new PaymentForm(); // form used for the edit
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        $accountDao = $em->getRepository('Application\Entity\Account');  
        $payment = $em->getRepository('Application\Entity\Payment')->find($id);
        
        
        // if we do not have an entry for the payment, i.e id not found or not defined
        // send them to add
        if (!$payment) {
            return $this->redirect()->toRoute('payments', array(
                'action' => 'add'
            ));
        }

        // retarded but robust way to do this
        $accountTitles = array();
        foreach ($accountDao->findAll() AS $v){
            $accountTitles[$v->id] = "{$v->name}";
        }
        
        
        // setup form
        // (validation, data and button)        
        $form->setInputFilter($payment->getInputFilter())
                ->setData($payment->toArray())
                ->get('submit')->setAttribute('value', 'Edit');

        $form->get('account_id')
                ->setValue((int) !isset($payment->account) ? null : $payment->account->id)
                ->setAttributes(array('options'=>$accountTitles));
        
        // process a submission
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->getRequest()->getPost()); // set the form with the submitted values

            // is valid?
            if ($form->isValid()) {
                
                $accountId = (int) $form->get('account_id')->getValue();
                
                
                $payment->setOptions($form->getData()); // set the data    
                $payment->id = $id;        
                
                $account = $accountDao->find($accountId);                        
                $payment->setOptions($form->getData()); // set the data     
            
                if ($account){
                    $em->persist($account); // set data
                    $em->persist($payment); // set data
                    $account->addPayment($payment);
                    $em->flush(); // save            
                    $this->flashMessenger()->addMessage(array('alert-success'=>'Payment Updated!'));  
                }
                else {
                    $this->flashMessenger()->addMessage(array('alert-error'=>'Error! Account not found!')); 
                }
                
                // Redirect to list of payments
                return $this->redirect()->toRoute('payments');

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
        $payment = $em->getRepository('Application\Entity\Payment')->find($id);
        
        
        // if we do not have an entry for the account, i.e id not found or not defined
        // send them back to the main screen
        if (!$payment) {
            return $this->redirect()->toRoute('payments');
        }
        

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('del', 'No') == 'Yes') {
  
            $em->remove($payment); // boom biddy bye bye
            $em->flush();
                 
            $this->flashMessenger()->addMessage(array('alert-info'=>'Deleted'));

            // Redirect to list of accounts
            return $this->redirect()->toRoute('payments');
        }

        return array(
            'id'    => $id,
            'payment' => $payment
        );
    }
        
    
}
