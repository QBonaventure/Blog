<?php
namespace Blog\Mapper;

use Blog\Model\Post;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\TableIdentifier;

 class PostMapper implements PostMapperInterface
 {
     /**
      * @var \Zend\Db\Adapter\AdapterInterface
      */
     protected $dbAdapter;

     /**
      * @var \Zend\Stdlib\Hydrator\HydratorInterface
      */
     protected $hydrator;
     
     

//      /**
//       * @var \Blog\Model\PostInterface
//       */
//      protected $postPrototype;

     /**
      * @param AdapterInterface  $dbAdapter
      */
     public function __construct(AdapterInterface $dbAdapter,
     								HydratorInterface $hydrator) {
         $this->dbAdapter			= $dbAdapter;
         $this->hydrator			= $hydrator;
//          $this->postPrototype		= $postPrototype;
     }
     
     
     public function getDbAdapter() {
     	return $this->dbAdapter;
     }
     
     
//      public function getPostPrototype() {
//      	return $this->postPrototype;
//      }

     /**
      * @param int|string $id
      *
      * @return PostInterface
      * @throws \InvalidArgumentException
      */
     public function find($id)
     {
     	$spResponse = 0;
     	$prepareStmt = $this->dbAdapter->createStatement();
     	$sql	= 'SELECT * FROM blog.view_posts WHERE id = :id';

     	$prepareStmt->prepare($sql);
		$result = $prepareStmt->execute(array('id'	=> $id));
		$newPostObject	= $this->hydrator->hydrate($result->current(), new Post());
     	
		return $newPostObject;
	}


     /**
      * @return array|PostInterface[]
      */
     public function findInPublishedPosts($criterias = array(), $limit = null) {
     	$sql	= new SQL($this->dbAdapter);
     	$select	= $sql->select();
     	$select->from(new TableIdentifier('mv_published_posts', 'blog'));
     	
     	if($criterias)
     		$select->where($criterias);
     	if($limit)
	     	$select->limit($limit);
     	     	
     	$stmt	= $sql->prepareStatementForSqlObject($select);
     	$result	= $stmt->execute();
     	
		return $result;
     }

     /**
      * @return array|PostInterface[]
      */
     public function findAll($criterias = array(), $limit = 0) {     	
     	$sql	= 'SELECT * FROM blog.get_posts()';
     	if($limit)
     		$sql .= '  LIMIT :limit';
     	$stmt = $this->dbAdapter->createStatement($sql);
     	$stmt->prepare();
     	$params	= array();
     	if($limit)
     		$params[':limit']	= $limit;
     	$result = $stmt->execute($params);

     	$postsArray	= json_decode($result->current()['posts'], true);

     	foreach($postsArray as $index => $post) {
	     	$postsArray[$index] = $this->hydrator->hydrate($post, new Post());

     	}
 
// 		if ($result instanceof ResultInterface && $result->isQueryResult())
// 		{
// 			$resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
// 			$oi	= $resultSet->initialize($result)->current();

// 			return $resultSet->initialize($result);
// 		}
		return $postsArray;
     }
     
     
     public function findPublishedPostsByCategory($category) {
     	$sql	= 'SELECT * FROM blog.view_posts WHERE category_id = :category';
     	$stmt = $this->dbAdapter->createStatement($sql);
     	$stmt->prepare();
     	$result	= $stmt->execute(array('category' => $category));

     	$postsArray	= iterator_to_array($result);

     	foreach($postsArray as $index => $post) {
	     	$postsArray[$index] = $this->hydrator->hydrate($post, new Post());
     	}

		return $postsArray;
     }
     
     public function getClosestPosts($postId) {
     	$sql = 'SELECT (SELECT row_to_json(_.*) AS row_to_json
						FROM ( SELECT preceding_post.id, preceding_post.title) _ WHERE _.id IS NOT NULL) AS preceding_post,
					(SELECT row_to_json(_.*) AS row_to_json
						FROM ( SELECT following_post.id, following_post.title) _ WHERE _.id IS NOT NULL) AS following_post
				FROM blog.mv_published_posts post
				JOIN blog.mv_published_surrounding_posts ref_surrounding ON ref_surrounding.id = post.id
				LEFT JOIN blog.mv_published_posts preceding_post ON preceding_post.id = ref_surrounding.previous_post_id
				LEFT JOIN blog.mv_published_posts following_post ON following_post.id = ref_surrounding.next_post_id
				WHERE post.id = :post_id
				GROUP BY preceding_post.id, preceding_post.title, following_post.id, following_post.title';
     	$stmt	= $this->dbAdapter->createStatement($sql);
     	$stmt->prepare();
     	
     	$posts	= $stmt->execute(array('post_id' => $postId));
     	
     	return $posts->current();
     }
     

     
     public function savePost($values)
     {
     	$values	= $this->hydrator->extract($values);
     	$encodedValue	= json_encode($values);
     	
     	$sql	= 'SELECT * FROM blog.post_save_new(:data::json)';
     	$stmt = $this->dbAdapter->createStatement($sql);
		$result	= $stmt->execute(array('data'	=> $encodedValue));
		
		$resultRow		= $result->current();
		$newPostArray	= json_decode($resultRow['post_save_new'], true);
		$newPostObject	= $this->hydrator->hydrate($newPostArray, new Post());
// 		$newPostObject	= $this->hydrator->hydrate($values, $this->postPrototype);
		
		return $newPostObject;
     }

     
     public function updatePost($values, $postId)
     {
     	$values	= $this->hydrator->extract($values);
     	$encodedValue	= json_encode($values);

     	$sql	= 'SELECT * FROM blog.post_update(:data::json)';
     	$stmt = $this->dbAdapter->createStatement($sql);
		$result	= $stmt->execute(array('data'	=> $encodedValue));
		
		$resultRow		= $result->current();

		$newPostArray	= json_decode($resultRow['post_update'], true);
		$newPostObject	= $this->hydrator->hydrate($newPostArray, new Post());
// 		$newPostObject	= $this->hydrator->hydrate($values, $this->postPrototype);
		
		return $newPostObject;
     }
     
     
     public function publishPost($postId, $userId) {
     	$sql	= 'SELECT * FROM blog.post_publish(:postId::bigint, :userId::bigint)';
     	$stmt = $this->dbAdapter->createStatement($sql);
		$result	= $stmt->execute(array('postId'	=> $postId,
										'userId' => $userId
		));
		
		$resultRow		= $result->current();
		return $resultRow['post_publish'];
     }
     
     
     public function unpublishPost($postId, $userId) {
     	$sql	= 'SELECT * FROM blog.post_unpublish(:postId::bigint, :userId::bigint)';
     	$stmt = $this->dbAdapter->createStatement($sql);
		$result	= $stmt->execute(array('postId'	=> $postId,
										'userId' => $userId
		));
		
		$resultRow		= $result->current();
		return $resultRow['post_unpublish'];
     }
     
     
     public function removePost($postId, $userId) {
     	$sql	= 'SELECT * FROM blog.post_remove(:postId::bigint, :userId::bigint)';
     	$stmt = $this->dbAdapter->createStatement($sql);
		$result	= $stmt->execute(array('postId'	=> $postId,
										'userId' => $userId
		));
		
		$resultRow		= $result->current();
		return $resultRow['post_remove'];
     }
     
     
     public function getCategories($categoryId = null) {
     	$sql	= 'SELECT * FROM blog.view_posts_categories';
     	if($categoryId != null)
     		$sql .= ' WHERE id = :id LIMIT 1';
     	$stmt = $this->dbAdapter->createStatement($sql);

     	
     	$params	= array();
     	if($categoryId != null)
     		$params['id']	= $categoryId;

     	$result = $stmt->execute($params);
     	
		return iterator_to_array($result);
     }
 }