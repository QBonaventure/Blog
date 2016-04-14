<?php
namespace Blog\Model;

interface KeywordInterface {
	
	/**
	 * @return integer
	 */
	public function getId();

	/**
	 * @param integer $id id of the keyword
	 */
	public function setId($id);
	
	/**
	 * @return string
	 */
	public function getWord();

	/**
	 * @param string $id actual keyword
	 */
	public function setWord($word);
}