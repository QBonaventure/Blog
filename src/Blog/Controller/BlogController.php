<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;

use Blog\Service\PostServiceInterface;
use Zend\View\HelperPluginManager;

use Zend\View\Model\JsonModel;

class BlogController extends AbstractActionController
{

	protected $postsService;
	protected $authService;
	protected $parser;
	protected $viewHelper;
	
	public function __construct(PostServiceInterface $postsService
								, AuthenticationService $authService
								, HelperPluginManager $viewHelper
			)
	{
		$this->postsService	= $postsService;
		$this->authService	= $authService;
		$this->viewHelper	= $viewHelper;
// 		$this->viewHelper->get('headScript')->appendFile('/js/blog/posts.js', 'text/javascript');
	}
	
	protected function _getHelper($helper, $serviceLocator)
	{
		return $this->getServiceLocator()
		->get('viewhelpermanager')
		->get($helper);
	}
}