<?php
namespace Blog\Hydrator\Strategy;

use Blog\Model\Author;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class AuthorStrategy extends DefaultStrategy
{
	
	/**
	* {@inheritdoc}
	*
	* Convert an array into a User object
	*/
 	public function hydrate($value)
	{
		if(is_string($value))
		{
 			$value	= json_decode($value, true);
		}
		$value	= (array) $value;
		if (is_array($value) && $value != null)
		{
			$keyword = new Author();
			$keyword->setId($value['id']);
			$keyword->setUsername($value['username']);
			$keyword->setRole($value['role']);
			return $keyword;
		}
		return $value;
	}
	
	
	
 	public function extract($keywords)
	{

		if (is_array($keywords)) {
      		foreach($keywords as $keyword)
      		{
				$keywordArray['id']		= $keyword->getId();
				$keywordArray['word']	= $keyword->getWord();
				$keywordsArray[]		= $keywordArray;
      		}
      		$keywords	= $keywordsArray;
		}
		
		return $keywords;
	}
}