<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
 
use Application\Model\Message;
use Application\Model\MessageTable;
 
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

use Zend\Form\View\Helper\FormRow;

class Module
{

    public function onPreRoute($e){
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        $serviceManager->get('router')->setTranslator($serviceManager->get('translator'));
    }
    
    public function onBootstrap(MvcEvent $e)
    {
		$translator = $e->getApplication()->getServiceManager()->get('translator');
		$translator->setLocale('fr_FR');        
		$translator->addTranslationFile('PhpArray', __DIR__.'/language/routes/fr_FR.php', 'default', 'fr_FR');
    	
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach('route', array($this, 'onPreRoute'), 100);
        
   		$eventManager->attach(new Listener\PostListener());
		
    }
    
    public function moduleCheck(MvcEvent $e) {
    	$matches	= $e->getRouteMatch();
    	$controller	= $matches->getParam('controller', '');
    	if(0 !== strpos($controller, __NAMESPACE__)) {
    		return;
    	}
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				include __DIR__ . '/autoload_classmap.php',
				),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Blog' => __DIR__ . '/src/Blog',
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
    	return array(
    			'factories' => array(
    					'formRow' => function($sm) {
    					$helper = new FormRow();
    					$helper->setRenderErrors(false);
    					return $helper;
    				}
    			),
    			'invokables' => array(
    				'administrationMenu' => 'Blog\View\Helper\AdministrationMenu',
    			),		
    		);
    }


    public function getServiceConfig() {
		return array(
			'factories' => array(
				'Application\Model\MessageTable'=>function($sm){
	    			$tableGateway = $sm->get('MessageTableGateway');
	    			$table = new MessageTable($tableGateway);
	    			return $table;
    			},
    					//Instantiating TableGateway to inject to StudentTable class
    				'MessageTableGateway'=>function($sm){
    				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$resultSetPrototype = new ResultSet();
    				$resultSetPrototype->setArrayObjectPrototype(new Message());
    				return new TableGateway('messages', $dbAdapter,null,$resultSetPrototype);
    				}
    			)
    		);
    }
}
