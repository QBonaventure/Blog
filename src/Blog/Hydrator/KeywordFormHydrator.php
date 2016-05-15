<?php
namespace Blog\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Hydrator\ClassMethods;

class KeywordFormHydrator extends ClassMethods implements HydratorInterface
{
	public function __construct($underscoreSeparatedKeys = true) {
        parent::__construct($underscoreSeparatedKeys);
	}
	
	public function extract($object) {
// 		$result['word']	= $object->getWord();
// 		$result['id']		= $object->getid();
// 		$result['content']	= $object->getBody();
// 		$result['category']	= $object->getCategoryId();
// 		$result['status']	= $object->getStatusId();
	
// 		foreach($object->getKeywords() as $keyword) {
// 			$result['keywords'][] = array('id' => $keyword->getId(),
// 					'word' => $keyword->getWord());
// 		}
	
		return $result;
	}
	
}