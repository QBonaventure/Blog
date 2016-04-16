<?php
namespace Blog\Model;


class Author extends \Users\Model\User implements AuthorInterface {

	protected $posts;
	
	public function getPosts() {
		return $this->posts;
	}
	
	public function setPosts($posts) {
		$this->posts = $posts;
		return $this;
	}
}