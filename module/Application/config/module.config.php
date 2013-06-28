<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'   => 'Application\Controller\IndexController',
            'Application\Controller\Date' => 'Application\Controller\DateController',
            'Application\Controller\Transaction' => 'Application\Controller\TransactionController',
            'Application\Controller\Payment' => 'Application\Controller\PaymentController',
            'Application\Controller\Account' => 'Application\Controller\AccountController',
            'Application\Controller\Tag' => 'Application\Controller\TagController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ), 
            'dates' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/date[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Date',
                        'action'     => 'index',
                    ),
                ),
            ),
            'transactions' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/transaction[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Transaction',
                        'action'     => 'index',
                    ),
                ),
            ),
            'payments' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/payment[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Payment',
                        'action'     => 'index',
                    ),
                ),
            ),
            'accounts' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Account',
                        'action'     => 'index',
                    ),
                ),
            ),  
            'tags' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/tag[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Tag',
                        'action'     => 'index',
                    ),
                ),
            ), 
        ),
    ),

    
    'navigation' => array(
        'default' => array(
            'Home' => array(
                'label' => 'Home',
                'route' => 'home',                
            ),
            'dates' => array(
                'label' => 'Dates',
                'route' => 'dates',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'dates',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'dates',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'dates',
                        'action' => 'delete',
                    ),
                )
            ), 
            'transactions' => array(
                'label' => 'Transactions',
                'route' => 'transactions',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'transactions',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'transactions',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'transactions',
                        'action' => 'delete',
                    ),
                )
            ), 
            'payments' => array(
                'label' => 'Payments',
                'route' => 'payments',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'payments',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'payments',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'payments',
                        'action' => 'delete',
                    ),
                )
            ), 
            'accounts' => array(
                'label' => 'Accounts',
                'route' => 'accounts',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'accounts',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'accounts',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'accounts',
                        'action' => 'delete',
                    ),
                )
            ), 
            'tags' => array(
                'label' => 'Tags',
                'route' => 'tags',
                'pages' => array(
                    array(
                        'label' => 'Add',
                        'route' => 'tags',
                        'action' => 'add',
                    ),
                    array(
                        'label' => 'Edit',
                        'route' => 'tags',
                        'action' => 'edit',
                    ),
                    array(
                        'label' => 'Delete',
                        'route' => 'tags',
                        'action' => 'delete',
                    ),
                )
            ),
        ),
    ),
    
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                return $serviceManager->get('doctrine.authenticationservice.orm_default');
            }
        ),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            //'layout/header'           => __DIR__ . '/../view/layout/header.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password',
            ),
        ),
    ),
);
