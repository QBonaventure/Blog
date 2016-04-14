<?php

namespace BlogTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;

class BlogControllerTest extends AbstractHttpControllerTestCase
{

	protected $traceError = true;
	
    public function setUp()
    {
        $this->setApplicationConfig(
            include 'D:\Development\Workspace\bonaventure.php\config\application.config.php'
        );
        parent::setUp();
    }
    
    public function testActionCanBeAccessed()
    {
    	$this->mockLogin();
    	$this->dispatch('/administration/blog/posts/créer');
    	$this->assertResponseStatusCode(200);
    	
    	$this->assertModuleName('Blog');
    	$this->assertControllerName('Blog\Controller\Posts');
    	$this->assertControllerClass('PostsController');
    	$this->assertMatchedRouteName('administration/posts/create');
    }
    
//     public function testContactActionCanBeAccessed()
//     {
// //     	$this->mockLogin();
//     	$this->dispatch('/contact');
//     	$this->assertResponseStatusCode(200);
    	
//     	$this->assertModuleName('Application');
//     	$this->assertControllerName('Application\Controller\Index');
//     	$this->assertControllerClass('IndexController');
//     	$this->assertMatchedRouteName('application');
    
// //     	$this->assertModuleName('Blog');
// //     	$this->assertControllerName('Blog\Controller\Posts');
// //     	$this->assertControllerClass('PostsController');
// //     	$this->assertMatchedRouteName('administration/posts/create');
//     }

    protected function mockLogin()
    {
    	$userSessionValues = new \stdClass();
    	$userSessionValues->id	= 2;
     	$userSessionValues->username	= 'quentin@bonaventure.xyz';
     	$userSessionValues->role	= 'administrator';
    	$userSessionModel = new Session();
    	$userSessionModel->write($userSessionValues);
    
    	$authService = $this->getMock('Zend\Authentication\AuthenticationService');
//     	$authService->expects($this->any())
// 			    	->method('getIdentity')
// 			    	->will($this->returnValue($userSessionModel));
    
    	$authService->expects($this->any())
    				->method('hasIdentity')
    				->will($this->returnValue(true));
    	
//     	$authService->getStorage()->write($userSessionModel);
    	
    	$authService->expects($this->any())
    				->method('getStorage')
    				->will($this->returnValue($userSessionModel));

    	$this->getApplicationServiceLocator()->setAllowOverride(true);
    	$this->getApplicationServiceLocator()->setService('Zend\Authentication\AuthenticationService', $authService);
    }
}