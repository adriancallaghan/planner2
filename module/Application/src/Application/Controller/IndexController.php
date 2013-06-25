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
        
        
        /*
         * 
         * NEW DATE
        $newDate = new \Application\Entity\Date();
        //$newDate->setOptions(array(''));
        $em->persist($newDate); // set data
        $em->flush(); // save     
        
        var_dump($dateOrm->findAll()->toArray()); 
           */

        
        /*
         * NEW ACTIVITY
        $date = $dateOrm->findOneById(2);
        $newActivity = new \Application\Entity\Activity();
        $newActivity->setOptions(array('comment'=>'test2'));
        
        $date->addActivity($newActivity);
        
        $em->persist($date); // set data
        $em->persist($newActivity); // set data
        $em->flush(); // save    
        
        var_dump($date->getActivities()->toArray());
        */
        
        
        
        /*
         * NEW TRANSACTION DEFINITION FOR ACTIVITY
         */ 
        /*
        $activity = $activityOrm->findOneById(2);
        $newTransaction = new \Application\Entity\Transaction();
        $newTransaction->setOptions(array('description'=>'desc1000'));
        $activity->setTransaction($newTransaction);
        $em->persist($activity); // set data
        $em->persist($newTransaction); // set data
        $em->flush(); // save    
        
        var_dump($activity->getTransaction()->toArray());
        */
        
        
        var_dump($dateOrm->findOneById(2)->getActivities()->toArray()[0]->getTransaction()->toArray());
        
        
        die;
        $transaction_types = array(0=>'payment',1=>'loan',2=>'mobile');

        
        
        $todayKey = '20130917';

        $dates = (object) array(
            '20130919'=>(object) array(
                'unix'=>mktime(0,0,0,9,19,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130918'=>(object) array(
                'unix'=>mktime(0,0,0,9,18,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array(
                    't1'=>(object) array(
                        'account'=>1,
                        'amount'=>'312.42',
                        'type'=>$transaction_types[2],
                        'order'=>2
                        ),
                     't2'=>(object) array(
                        'account'=>4,
                        'amount'=>'-28.99',
                        'type'=>$transaction_types[2],
                        'order'=>3
                        ),
                    't3'=>(object) array(
                        'account'=>4,
                        'amount'=>'-8.99',
                        'type'=>$transaction_types[0],
                        'order'=>3
                        ),
                    't4'=>(object) array(
                        'account'=>4,
                        'amount'=>'-25.99',
                        'type'=>$transaction_types[1],
                        'order'=>4
                        ),
                    't5'=>(object) array(
                        'account'=>4,
                        'amount'=>'-22.99',
                        'type'=>$transaction_types[2],
                        'order'=>5
                        )

                )
            ),
            '20130917'=>(object) array(
                'unix'=>mktime(0,0,0,9,17,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array(
                    't7'=>(object) array(
                        'account'=>1,
                        'amount'=>'312.42',
                        'type'=>$transaction_types[2],
                        'order'=>2
                        )
                )
            ),
            '20130916'=>(object) array(
                'unix'=>mktime(0,0,0,9,16,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130915'=>(object) array(
                'unix'=>mktime(0,0,0,9,15,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array(
                    't6'=>(object) array(
                        'account'=>4,
                        'amount'=>'-21.99',
                        'type'=>$transaction_types[1],
                        'order'=>6
                        ),
                )
            ),
            '20130914'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130913'=>(object) array(
                'unix'=>mktime(0,0,0,9,13,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130912'=>(object) array(
                'unix'=>mktime(0,0,0,9,12,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130911'=>(object) array(
                'unix'=>mktime(0,0,0,9,11,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130910'=>(object) array(
                'unix'=>mktime(0,0,0,9,10,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130909'=>(object) array(
                'unix'=>mktime(0,0,0,9,9,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130908'=>(object) array(
                'unix'=>mktime(0,0,0,9,8,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),
            '20130907'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130906'=>(object) array(
                'unix'=>mktime(0,0,0,9,13,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130905'=>(object) array(
                'unix'=>mktime(0,0,0,9,12,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130904'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130903'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130902'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130901'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130831'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'351.53',
                'transactions'=>(object) array()
            ),
            '20130830'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130829'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'321.53',
                'transactions'=>(object) array()
            ),            
            '20130828'=>(object) array(
                'unix'=>mktime(0,0,0,9,14,2013),
                'balance'=>'381.53',
                'transactions'=>(object) array()
            ),
        );
        
        
        
        
          
        return new ViewModel(array(
            'dates' => $dates,
            'todayKey' => $todayKey,
            'transaction_types' => $transaction_types,
            ));
    }
}
