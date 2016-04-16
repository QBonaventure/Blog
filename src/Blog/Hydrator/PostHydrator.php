<?php
namespace Blog\Hydrator;


use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Hydrator\ClassMethods;

class PostHydrator extends ClassMethods implements HydratorInterface
{
	public function __construct($underscoreSeparatedKeys = true) {
        parent::__construct($underscoreSeparatedKeys);
	}
	
	
	public function hydrate(array $data, $object) {
		$post	= parent::hydrate($data, $object);

		$post->setContentHTMLIfNotSet();
		
		return $post;
	}
	
}