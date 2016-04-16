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
								, PostHydrator $hydrator
								) {
		$this->postMapper	= $postMapper;
		$this->postHydrator	= $hydrator;
	}
	
	public function getAllPosts($limit = null) {
		return $this->postMapper->findAll(array(), $limit);
	}


	/**
	 * Returns Post with the corrsponding ID
	 * 
	 * @param int $postId
	 * @return PostInterface
	 * @throws \InvalidArgumentException
	 */
	public function getPost($postId) {
		if(!is_int($postId)) {
			throw new \InvalidArgumentException('Post id must be an integer');
		}
		$postArray	= $this->postMapper->find($postId);
		
		return $this->createPost($postArray);
	}
	

	/**
	 * Returns a number of $limit of Post matching $criterias
	 * 
	 * @param array $criterias
	 * @param int $limit
	 * @return PostInterface[]
	 * @throws \InvalidArgumentException
	 */
	public function getPublishedPosts($criterias = array(), $limit = null) {
		if(!is_array($criterias)) {
			throw new \InvalidArgumentException('Optionnal criterias argument must be an array');
		}
		if(!is_null($limit) && (!is_int($limit) || $limit <= 0)) {
			throw new \InvalidArgumentException('Optionnal limit argument must be an integer superior to 0');
		}
		$result	= $this->postMapper->findInPublishedPosts($criterias, $limit);
		
		if($result->count() == 0)
			return null;
		
     	foreach($result as $index => $postArray) {
	     	$postsArray[$index] = $this->createPost($postArray);
     	}
     	
     	return $postsArray;
	}
	
	
	
	
// 	public function getPublishedPostsByCategory($category, $limit = null) {
// 		return $this->postMapper->findAll(array('category_id' => $category), $limit);
// 	}

	/**
	 * Returns the previous and next posts published title and id, if they exist.
	 * 
	 * @param int $postId
	 * @return array[]
	 */
	
	public function getClosestPosts($postId) {
		if(!is_int($postId)) {
			throw new \InvalidArgumentException('Post id must be an integer');
		}
		
		$result	= $this->postMapper->getClosestPosts($postId);
		return array('preceding_post' => json_decode($result['preceding_post']),
					 'following_post' => json_decode($result['following_post'])
		);
	}
	
	public function savePost($values) {
		return $this->postMapper->savePost($values);
	}
	
	public function updatePost($updates, $originalPost) {
		$result	= $this->postMapper->updatePost($updates, $originalPost);
		return $this->createPost($result);
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