<?php
namespace Blog\Model;

use Blog\Model\KeywordInterface;

class Keyword implements KeywordInterface {



	/**
	 * @var \Blog\Model\KeywordInterface
	 */
	protected $keywordPrototype;
	
	/**
	 * @var int
	 */
	protected $id;
	
	/**
	 * @var string
	 */
	protected $word;
	

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id	= $id;
	}
	
	public function getWord()
	{
		return $this->word;
	}
	
	public function setWord($word)
	{
		$this->word	= $word;
	}
}