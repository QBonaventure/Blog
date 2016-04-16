<?php
namespace Blog\Model;

interface PostInterface {

	public function getId();
	
	public function setId($id);
	
	public function getStatusId();
	
	public function setStatusId($statusId);
	
	public function getTitle();
	
	public function setTitle($title);
	
	public function getLead();
	
	public function setLead($lead);
	
	public function getBody();
	
	public function setBody($content);
	
	public function getAuthor();
	
	public function setAuthor($author);
	
	/**
	 * @return Blog\Model\Keyword[]
	 */
	public function getKeywords();
	
	public function setKeywords($keywords);
}