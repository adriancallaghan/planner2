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
use Application\Form\TagForm; 
use DoctrineModule\Paginator\Adapter\Collection as Adapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections;

class TagController extends AbstractActionController
{
    public function indexAction()
    {
                
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $tagEntity = $em->getRepository('Application\Entity\Tag');

        $tags = $tagEntity->findAll();

        $collection = new Collections\ArrayCollection($tags);

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
            'title'     => 'Tags',
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));
        
    }
    
    
    
    public function addAction()
    {

        $tag = new \Application\Entity\Tag();
        $form = new TagForm();      
        $form->setInputFilter($tag->getInputFilter())
            ->setData($this->getRequest()->getPost())
            ->get('submit')->setValue('Add');
        
        
        if ($this->getRequest()->isPost() && $form->isValid()) {                
            $tag->setOptions($form->getData()); // set the data            
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager
            $em->persist($tag); // set data
            $em->flush(); // save      
            // set messages 
            //$this->flashMessenger()->addMessage('You must do something.');           
            //$this->flashMessenger()->addMessage(array('alert-info'=>'Soon this changes.'));           
            $this->flashMessenger()->addMessage(array('alert-success'=>'Added!'));           
            //$this->flashMessenger()->addMessage(array('alert-error'=>'Sorry, Error.')); 

            // Redirect to list of tags
            return $this->redirect()->toRoute('tags');
        }
        
        
        return array('form' => $form);
    }

       
    // Add content to this method:
    public function editAction()
    {
       
        
        $id = (int) $this->params()->fromRoute('id', 0); // id that we editing, defaults to zero
        $form  = new TagForm(); // form used for the edit
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');  
        $tag = $em->getRepository('Application\Entity\Tag')->find($id);
        
        
        // if we do not have an entry for the tag, i.e id not found or not defined
        // send them to add
        if (!$tag) {
            return $this->redirect()->toRoute('tags', array(
                'action' => 'add'
            ));
        }

        // setup form
        // (validation, data and button)
        $form->setInputFilter($tag->getInputFilter())
                ->setData($tag->toArray())
                ->get('submit')->setAttribute('value', 'Edit');


        // process a submission
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->getRequest()->getPost()); // set the form with the submitted values

            // is valid?
            if ($form->isValid()) {
                
                $tag->setOptions($form->getData()); // set the data    
                $tag->id = $id;        
                $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager
                $em->persist($tag); // set data
                $em->flush(); // save
                
                // set messages 
                //$this->flashMessenger()->addMessage('You must do something.');           
                //$this->flashMessenger()->addMessage(array('alert-info'=>'Soon this changes.'));           
                $this->flashMessenger()->addMessage(array('alert-success'=>'Updated!'));           
                //$this->flashMessenger()->addMessage(array('alert-error'=>'Sorry, Error.')); 
                
                // Redirect to list of tags
                return $this->redirect()->toRoute('tags');

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
        $tag = $em->getRepository('Application\Entity\Tag')->find($id);
        
        
        // if we do not have an entry for the tag, i.e id not found or not defined
        // send them back to the main screen
        if (!$tag) {
            return $this->redirect()->toRoute('tags');
        }
        

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('del', 'No') == 'Yes') {
  
            $em->remove($tag); // boom biddy bye bye
            $em->flush();
                 
            $this->flashMessenger()->addMessage(array('alert-info'=>'Deleted'));

            // Redirect to list of tags
            return $this->redirect()->toRoute('tags');
        }

        return array(
            'id'    => $id,
            'tag' => $tag
        );
    }
        
    
    
    
        
}
