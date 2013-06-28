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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        
        
        //var_dump($daterangeArray);
        
        $dates = new \Application\Models\Dates();
        var_dump($dates->getMonth());
        
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager
        $dateOrm = $em->getRepository('Application\Entity\Date'); // orm for date        

        return new ViewModel(array(
            'dates' => $dateOrm->findAll(),
            ));
    }
    
    
    
        
}
