<?php
namespace Blog\Model;

interface AuthorInterface {

	public function getPosts();
	
	public function setPosts($id);
}