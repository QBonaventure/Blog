<?php
namespace Blog\Factory;

use Blog\Controller\PostsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostsControllerFactory implements FactoryInterface
{
     /**
      * Create service
      *
      * @param ServiceLocatorInterface $serviceLocator
      *
      * @return mixed
      */
     public function createService(ServiceLocatorInterface $serviceLocator)
     {
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$postsService		= $realServiceLocator->get('Blog\Service\PostServiceInterface');
		$authService        = $realServiceLocator->get('AuthService');
		$viewHelper			= $realServiceLocator->get('viewhelpermanager');
		$viewRenderer		= $realServiceLocator->get('ViewRenderer');
		$cryptography		= $realServiceLocator->get('CryptographyInterface');
		
		return new PostsController($postsService
         							, $authService
         							, $viewHelper
									, $viewRenderer
									, $cryptography
         	);
     }
 }