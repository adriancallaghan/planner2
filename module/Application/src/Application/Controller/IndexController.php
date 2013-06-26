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
        
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); // entity manager
        $dateOrm = $em->getRepository('Application\Entity\Date'); // orm for date
        $activityOrm = $em->getRepository('Application\Entity\Activity'); // orm for activity
        $transactionOrm = $em->getRepository('Application\Entity\Transaction'); // orm for transaction
        $accountOrm = $em->getRepository('Application\Entity\Account'); // orm for transaction
        
        
        
        return new ViewModel(array(
            'dates' => $dateOrm->findAll(),
            'accounts' => $accountOrm->findAll(),
            ));
        
        
        
          
    }
        
}
