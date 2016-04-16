<?php
namespace Blog\Mapper;

use Blog\Model\PostInterface;

interface PostMapperInterface {
	
     /**
      * @return array|PostInterface[]
      */
     public function findAll();

     /**
      * @param int|string $id
      * @return PostInterface
      * @throws \InvalidArgumentException
      */
     public function find($id);

     /**
      * @param PostInterface $post
      * @return PostInterface
      * @throws \InvalidArgumentException
      */
     public function savePost($values);
 }