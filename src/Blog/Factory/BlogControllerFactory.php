<?php
namespace Blog\Factory;

use Blog\Controller\BlogController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogControllerFactory implements FactoryInterface
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
		
		return new BlogController($postsService
         							, $authService
         							, $viewHelper
         	);
     }
 }