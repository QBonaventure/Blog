<?php
namespace Blog\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Hydrator\ClassMethods;

class KeywordHydrator extends ClassMethods implements HydratorInterface
{
	public function __construct($underscoreSeparatedKeys = true) {
        parent::__construct($underscoreSeparatedKeys);
	}
	
	public function hydrate(array $data, $object) {
		$object->setId((is_int($data['id']) && $data['id'] > 0) ? $data['id'] : null);
		$object->setWord(($data['word']) ? $data['word'] : null);
		
		return $object;
	}
	
	
}