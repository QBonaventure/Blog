<?php
namespace Blog\Hydrator;


use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Hydrator\ClassMethods;

class PostFormHydrator extends ClassMethods implements HydratorInterface
{
	public function __construct($underscoreSeparatedKeys = true) {
        parent::__construct($underscoreSeparatedKeys);
	}
	
	
	public function hydrate(array $data, $object) {
		$post	= parent::hydrate($data, $object);

		$post->setContentHTMLIfNotSet();
		
		return $post;
	}
	
	public function extract($object) {
		$result['title']	= $object->getTitle();
		$result['lead']		= $object->getLead();
		$result['content']	= $object->getBody();
		$result['category']	= $object->getCategoryId();
		$result['status']	= $object->getStatusId();

		return $result;
	}
}