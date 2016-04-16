<?php
namespace Blog\Service;

// use Site\Model\ArticleInterface;


interface PostServiceInterface {
	
	public function getAllposts();
	
	public function getPost($postId);
}