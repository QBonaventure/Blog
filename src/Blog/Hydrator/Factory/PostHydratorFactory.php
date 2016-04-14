<?php
namespace Blog\Hydrator\Factory;

use Blog\Hydrator\PostHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Blog\Hydrator\Strategy\DateTimeStrategy;
use Blog\Hydrator\Strategy\KeywordStrategy;
use Blog\Hydrator\Strategy\AuthorStrategy;


class PostHydratorFactory implements FactoryInterface {
	
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$hydrator	= new PostHydrator();
		$hydrator->addStrategy('creation_date', new DateTimeStrategy());
		$hydrator->addStrategy('publication_date', new DateTimeStrategy());
		$hydrator->addStrategy('keywords', new KeywordStrategy());
		$hydrator->addStrategy('author', new AuthorStrategy());
		return $hydrator;
	}
}