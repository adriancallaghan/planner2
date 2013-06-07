<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;  
use Application\Form\AlbumForm;       
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;




class AlbumController extends AbstractActionController
{

    
    public function indexAction()
    {
        
        // grab the paginator from the AlbumTable
        /*$results = $this->getEntityManager()->getRepository('Application\Entity\Album')->findAll();
        
        // Create the paginator itself
        $paginator = new Paginator(new Adapter($results));
        
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1))
                ->setItemCountPerPage(2);
        */
        



        //$paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($albumModel->createQueryBuilder('*'))));
        
        /*$paginator = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );*/

        //$repository = $this->getEntityManager()->getRepository('Application\Entity\Album');
        //$query = $repository->findAll();
        
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $qb = $em->createQueryBuilder();
        
        $qb->select('e')->from('Application\Entity\Album','e');

        // Sorting
        //$qb->addOrderBy('p.' . $input->sort, $input->dir);

        $q = $qb->getQuery();

        
        $articles = $q->getResult(); // array of CmsArticle objects
        var_dump($articles);
        
        
        $adapter = new DoctrineAdapter(new ORMPaginator($q));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);

        
        
        return new ViewModel(array(
            'paginator' => $paginator,
            'title'     => 'Admin',
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));

    }
    
    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');


        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // set messages 
                //$this->flashMessenger()->addMessage('You must do something.');           
                //$this->flashMessenger()->addMessage(array('alert-info'=>'Soon this changes.'));           
                $this->flashMessenger()->addMessage(array('alert-success'=>'Added!'));           
                //$this->flashMessenger()->addMessage(array('alert-error'=>'Sorry, Error.')); 
                
                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            } else {
                //$this->flashMessenger()->addMessage(array('alert-error'=>'Form error'));
            }
        }
        return array('form' => $form);
    }

       
    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }
        $album = $this->getAlbumTable()->getAlbum($id);

        $form  = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($form->getData());
                
                // set messages 
                //$this->flashMessenger()->addMessage('You must do something.');           
                //$this->flashMessenger()->addMessage(array('alert-info'=>'Soon this changes.'));           
                $this->flashMessenger()->addMessage(array('alert-success'=>'Updated!'));           
                //$this->flashMessenger()->addMessage(array('alert-error'=>'Sorry, Error.')); 
                
                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
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
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
                $this->flashMessenger()->addMessage(array('alert-info'=>'Deleted'));
            } 
            
            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id'    => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }
        
    
}
