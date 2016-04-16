<?php
namespace Blog\Hydrator\Strategy;

use Blog\Model\Keyword;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class KeywordStrategy extends DefaultStrategy
{
	
	/**
	* {@inheritdoc}
	*
	* Convert an array into a Keyword object
	*/
 	public function hydrate($value)
	{
		if(is_string($value))
		{
 			$value	= json_decode($value, true);
		}

		if (is_array($value))
		{
      		foreach($value as $keywordValues)
      		{
				$keyword = new Keyword();
				$keywordValues	= (array) $keywordValues;
				$keyword->setId($keywordValues['id']);
				$keyword->setWord($keywordValues['word']);
				$keywords[]	= $keyword;
      		} 
		}
		
		return $keywords;
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