<?php
namespace Blog\Factory;

use Blog\Mapper\PostMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class PostMapperFactory implements FactoryInterface {
	
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return new PostMapper($serviceLocator->get('Zend\Db\Adapter\Adapter')
								, $serviceLocator->get('Blog\Hydrator\PostHydratorInterface')
				);
	}
}