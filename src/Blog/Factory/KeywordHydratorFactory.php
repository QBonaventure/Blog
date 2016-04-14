<?php
namespace Blog\Factory;

use Blog\Hydrator\KeywordHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class KeywordHydratorFactory implements FactoryInterface {
	
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$hydrator	= new KeywordHydrator();
		return $hydrator;
	}
}