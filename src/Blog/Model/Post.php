<?php
namespace Blog\Model;

use Blog\Model\PostInterface;
use Blog\Model\KeywordInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Datetime;
use Inner\Strings\ToUri;
use Inner\Parser\Wiki;

class Post implements PostInterface {
	
	/**
	 * @var int
	 */
	protected $id;
	
	/**
	 * @var int $statusId
	 */
	protected $statusId;
	
	/**
	 * @var string $status
	 */
	protected $status;
	
	/**
	 * @var int categoryId;
	 */
	protected $categoryId;
	
	/**
	 * @var string category;
	 */
	protected $category;
	
	/**
	 * @var int $authorId
	 */
	protected $authorId;
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var string
	 */
	protected $lead;
	
	/**
	 * @var string
	 */
	protected $body;
	
	/**
	 * @var string
	 */
	protected $bodyHTML;
	
	
	/**
	 * @var \Blog\Model\Author
	 */
	protected $author;
	
	/**
	 * @var DateTime
	 */
	protected $creationDate;
	
	/**
	 * @var DateTime
	 */
	protected $publicationDate;
	
	/**
	 * @var \Blog\Model\KeywordInterface[]
	 */
	protected $keywords;
	
	protected $keywordsArray;
	
	
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($postId)
	{
		$this->id	= (int) $postId;
	}
	
	public function getStatusId() {
		return $this->statusId;
	}
	
	public function setStatusId($postStatusId)
	{
		$this->statusId	= $postStatusId;
	}
	
	public function setStatus($postStatus)
	{
		$this->status	= $postStatus;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getCategoryId()
	{
		return $this->categoryId;
	}
	
	public function setCategoryId($postCategoryId)
	{
		$this->categoryId	= $postCategoryId;
	}
	
	public function setCategory($postCategory)
	{
		$this->category	= $postCategory;
	}
	
	public function getCategory()
	{
		return $this->category;
	}
	
	public function getAuthorId()
	{
		return $this->authorId;
	}
	
	public function setAuthorId($postAuthorId)
	{
		$this->authorId	= $postAuthorId;
	}
	
	public function getCreationDate()
	{
		return $this->creationDate;
	}
	
	public function setCreationDate(DateTime $postCreationDate = null)
	{
		$this->creationDate	= $postCreationDate;
	}
	
	public function getPublicationDate()
	{
		if(!$this->publicationDate)
			return new \DateTime();
		return $this->publicationDate;
	}
	
	public function setPublicationDate(DateTime $postPublicationDate = null)
	{
		$this->publicationDate	= $postPublicationDate;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getTitleForUri()
	{
		$uriParser	= new ToUri();
		return $uriParser->toUri($this->getTitle());
	}
	
	public function setTitle($postTitle)
	{
		return $this->title	= $postTitle;
	}
	
	public function getLead() {
		return $this->lead;
	}
	
	public function setLead($postLead)
	{
		$this->lead	= $postLead;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function setBody($postContent)
	{
		$this->body	= $postContent;
	}
	
	public function getBodyHTML()
	{
		return $this->bodyHTML;
	}
	
	public function setBodyHTML($postContent)
	{
		$this->bodyHTML	= $postContent;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public function setAuthor($author)
	{
		$this->author	= $author;
	}
	

	/**
	 * @return \Blog\Model\KeywordInterface[]
	 */
	public function getKeywords()
	{
		return $this->keywords;
	}
	
	public function setKeywords($keywords)
	{
		$this->keywords	= $keywords;
	}
	
	public function getKeywordsArray() {
		if(!$this->keywordsArray && count($this->keywords) > 0) {
			foreach($this->keywords as $keyword)
				$this->keywordsArray[]	= $keyword->getWord();
		}
			
		return $this->keywordsArray;
	}
	
	
	public function getParser() {
		return new Wiki();
	}
	
	
	public function setContentHTMLIfNotSet() {
		if($this->getBodyHTML() == null) {
			$this->bodyHTML = $this->getParser()->toHTML($this->getBody());
		}
	}
	
// 	public function getArrayCopy() {
//         $data = get_object_vars($this);
//         return $data['data'];
//     }
}