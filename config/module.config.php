<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

return array(
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'blog' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Posts',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                	'read' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:id].:title',
                            'defaults' => array(
		                        '__NAMESPACE__' => 'Blog\Controller',
		                        'controller'    => 'Posts',
		                        'action'    => 'read',
                            ),
                        ),
                    ),
                	'categories' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:categoryId].[{category}].[:categoryName]',
                            'defaults' => array(
		                        '__NAMESPACE__' => 'Blog\Controller',
		                        'controller'    => 'Posts',
		                        'action'    => 'category-index',
                            ),
                        ),
                    ),
                ),
            ),
            'administration' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/administration/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Administration',
                        'action'        => 'administration-index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
		            'posts' => array(
		                'type' => 'Segment',
		                'options' => array(
		                    'route'    => '/posts',
		                    'defaults' => array(
		                        '__NAMESPACE__' => 'Blog\Controller',
		                        'controller'    => 'Posts',
		                        'action'        => 'administration-index',
		                    ),
		                ),
		                'may_terminate' => true,
		                'child_routes' => array(
		                    'create' => array(
		                        'type'    => 'Segment',
		                        'options' => array(
		                            'route'    => '/{create}',
		                            'defaults' => array(
				                        '__NAMESPACE__' => 'Blog\Controller',
				                        'controller'    => 'Posts',
				                        'action'    => 'create',
		                            ),
		                        ),
		                'may_terminate' => true,
		                		'child_routes' => array(
									'preview' => array(
				                		'type'    => 'Segment',
				               		 		'options' => array(
				                			'route'    => '/preview',
				                     		'defaults' => array(
						                		'__NAMESPACE__' => 'Blog\Controller',
						                		'controller'    => 'Posts',
						                		'action'    => 'preview',
				                            ),
				                        ),
				                    ),
		                		),
		                    ),
		                    'edit' => array(
		                        'type'    => 'Segment',
		                        'options' => array(
		                            'route'    => '/{edit}.:id',
					                'constraints' => array(
					                    'id' => '[0-9]*',
					                ),
		                            'defaults' => array(
				                        '__NAMESPACE__' => 'Blog\Controller',
				                        'controller'    => 'Posts',
				                        'action'    => 'edit',
		                            ),
		                        ),
		                'may_terminate' => true,
		                		'child_routes' => array(
									'preview' => array(
				                		'type'    => 'Segment',
				               		 		'options' => array(
				                			'route'    => '/preview',
				                     		'defaults' => array(
						                		'__NAMESPACE__' => 'Blog\Controller',
						                		'controller'    => 'Posts',
						                		'action'    => 'preview',
				                            ),
				                        ),
				                    ),
		                		),
		                    ),
		                ),
		        	),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    	'invokables' => array(
        	'WikiParser'	=> 'Inner\Parser\Wiki',
//     		'Blog\Form\KeywordFieldset'	=> 'Blog\Form\KeywordFieldset',	
    	),
        'factories' => array(
         	'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
    		'mailing'	=> 'Application\Service\MailServiceFactory',
            'Blog\Service\PostServiceInterface' => 'Blog\Factory\PostServiceFactory',
        	'Blog\Mapper\PostMapperInterface'	=> 'Blog\Factory\PostMapperFactory',
        	'Blog\Hydrator\PostHydratorInterface'	=> 'Blog\Hydrator\Factory\PostHydratorFactory',
        	'Blog\Hydrator\KeywordHydratorInterface'	=> 'Blog\Factory\KeywordHydratorFactory',
        	'Blog\Form\Fieldset\PostFieldset' => 'Blog\Form\Posts\CreateFactory'
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
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
            'Blog\Controller\Administration' => 'Blog\Controller\AdministrationController',
        ),
        'factories' => array(
            'Blog\Controller\Posts' => 'Blog\Factory\PostsControllerFactory',
            'Blog\Controller\Blog' => 'Blog\Factory\BlogControllerFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    	'view_helpers' => array(
      		'invokables' => array(
        		'MainClass' => 'Application\View\Helper\RouteMatch',
      			'ToUri'		=> 'Inner\View\Helper\ToUri',
      ),
   ),
    'console' => array(
        'router' => array(
            'routes' => array(
	            'blog' => array(
	                'type' => 'Segment',
	                'options' => array(
	                    'route'    => '/blog[/:action]',
	                    'defaults' => array(
	                        '__NAMESPACE__' => 'Blog\Controller',
	                        'controller'    => 'Index',
	                        'action'        => 'index',
	                    ),
	                ),
	                'may_terminate' => true,
	            ),
            ),
        ),
    ),
);
