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
use Application\Form\AccountForm; 
use DoctrineModule\Paginator\Adapter\Collection as Adapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections;

class AccountController extends AbstractActionController
{
    public function indexAction()
    {
                
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $accountEntity = $em->getRepository('Application\Entity\Account');

        $accounts = $accountEntity->findAll();

        $collection = new Collections\ArrayCollection($accounts);

        // Create the paginator itself
        $paginator = new Paginator(new Adapter($collection));
        
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator
                ->setCurrentPageNumber(
                    (int)$this->params()->fromQuery('page', 1)
                )
                ->setItemCountPerPage(6);

        
        return new ViewModel(array(
            'paginator' => $paginator,
            'title'     => 'Accounts',
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));
        
    }
    
    
    
    public function addAction()
    {

        $account = new \Application\Entity\Account();
        $form = new AccountForm();      
        $form->setInputFilter($account->getInputFilter())
            ->setData($this->getRequest()->getPost())
            ->get('submit')->setValue('Add');
        
        
        if ($this->getRequest()->isPost() && $form->isValid()) {                
            $account->setOptions($form->getData()); // set the data            
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager
            $em->persist($account); // set data
            $em->flush(); // save      
            // set messages 
            //$this->flashMessenger()->addMessage('You must do something.');           
            //$this->flashMessenger()->addMessage(array('alert-info'=>'Soon this changes.'));           
            $this->flashMessenger()->addMessage(array('alert-success'=>'Added!'));           
            //$this->flashMessenger()->addMessage(array('alert-error'=>'Sorry, Error.')); 

            // Redirect to list of accounts
            return $this->redirect()->toRoute('accounts');
        }
        
        
        return array('form' => $form);
    }

       
    // Add content to this method:
    public function editAction()
    {
       
        
        $id = (int) $this->params()->fromRoute('id', 0); // id that we editing, defaults to zero
        $form  = new AccountForm(); // form used for the edit
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        $account = $em->getRepository('Application\Entity\Account')->find($id);
        
        
        // if we do not have an entry for the account, i.e id not found or not defined
        // send them to add
        if (!$account) {
            return $this->redirect()->toRoute('accounts', array(
                'action' => 'add'
            ));
        }

        // setup form
        // (validation, data and button)
        $form->setInputFilter($account->getInputFilter())
                ->setData($account->toArray())
                ->get('submit')->setAttribute('value', 'Edit');


        // process a submission
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->getRequest()->getPost()); // set the form with the submitted values

            // is valid?
            if ($form->isValid()) {
                
                $account->setOptions($form->getData()); // set the data    
                $account->id = $id;        
                $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager
                $em->persist($account); // set data
                $em->flush(); // save
                
                // set messages 
                //$this->flashMessenger()->addMessage('You must do something.');           
                //$this->flashMessenger()->addMessage(array('alert-info'=>'Soon this changes.'));           
                $this->flashMessenger()->addMessage(array('alert-success'=>'Updated!'));           
                //$this->flashMessenger()->addMessage(array('alert-error'=>'Sorry, Error.')); 
                
                // Redirect to list of accounts
                return $this->redirect()->toRoute('accounts');

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
        $account = $em->getRepository('Application\Entity\Account')->find($id);
        
        
        // if we do not have an entry for the account, i.e id not found or not defined
        // send them back to the main screen
        if (!$account) {
            return $this->redirect()->toRoute('accounts');
        }
        

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('del', 'No') == 'Yes') {
  
            $em->remove($account); // boom biddy bye bye
            $em->flush();
                 
            $this->flashMessenger()->addMessage(array('alert-info'=>'Deleted'));

            // Redirect to list of accounts
            return $this->redirect()->toRoute('accounts');
        }

        return array(
            'id'    => $id,
            'account' => $account
        );
    }
        
    
    
    
        
}
