<?php
namespace Blog\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

use Blog\Mapper\PostMapperInterface;
use Blog\Service\PostServiceInterface;
use Blog\Hydrator\PostHydrator;

use Blog\Form\Posts\CreateForm;

use Blog\Model\Post;



class PostService implements PostServiceInterface, EventManagerAwareInterface {

	/**
	 * @var EventManagerInterface
	 */
	protected $eventManager;
	
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	 */
	protected $postMapper;

	/**
	 * @var \Blog\Hydrator\PostHydrator
	 */
	protected $postHydrator;

     /**
      * @param \Blog\Mapper\PostMapperInterface $postMapper
      */
	public function __construct(PostMapperInterface $postMapper
//      							, EventManagerInterface $eventManager
								, PostHydrator $hydrator
								) {
		$this->postMapper	= $postMapper;
		$this->postHydrator	= $hydrator;
// 		$this->eventManager	= $evetManager;
	}
	
	public function getAllPosts($limit = null) {
		return $this->postMapper->findAll(array(), $limit);
	}
	
	public function getPost($postId) {
		return $this->postMapper->find($postId);
	}
	
	public function getPublishedPosts($criterias = array(), $limit = null) {
		return $this->postMapper->findInPublishedPosts($criterias, $limit);
	}
	
	public function getPublishedPostsByCategory($category, $limit = null) {
		return $this->postMapper->findAll(array('category_id' => $category), $limit);
	}
	
	public function getClosestPosts($postId) {
		$result	= $this->postMapper->getClosestPosts($postId);
		
		return array('preceding_post' => json_decode($result['preceding_post']),
					 'following_post' => json_decode($result['following_post'])
		);
	}
	
	public function savePost($values) {
		return $this->postMapper->savePost($values);
	}
	
	public function updatePost($updates, $originalPost) {
		return $this->postMapper->updatePost($updates, $originalPost);
	}
	
	public function publishPost($postId, $userId) {
		$this->getEventManager()->trigger('postPublished', null, array('kjhkjhjh'));
		return $this->postMapper->publishPost($postId, $userId);
	}
	
	public function unpublishPost($postId, $userId) {
		return $this->postMapper->unpublishPost($postId, $userId);
	}
	
	public function deletePost($postId, $userId) {
		return $this->postMapper->removePost($postId, $userId);
	}
	
	public function getCategories() {
		return $this->postMapper->getCategories();
	}
	
	public function getCategory($categoryId) {
		return $this->postMapper->getCategories($categoryId);
	}
	
	
	
	public function getDbAdapter() {
		return $this->postMapper->getDbAdapter();
	}
	
	public function getPostPrototype() {
		return $this->postMapper->getPostPrototype();
	}
	
	public function createPost($values = array()) {
		$post	= $this->postHydrator->hydrate($values, new Post());
		
		return $post;
	}
	
	public function getCreateForm() {
		$form	= new CreateForm($this->getDbAdapter());
		$form->bind(new Post());
		
		
		return $form;
	}

    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
	    $eventManager->addIdentifiers(array(
	        get_called_class()
	    ));

        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}