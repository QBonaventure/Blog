<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;

use Blog\Service\PostServiceInterface;
use Zend\View\HelperPluginManager;

use Zend\View\Model\JsonModel;
use Zend\View\Renderer\TreeRendererInterface;

class PostsController extends AbstractActionController
{

	protected $postsService;
	protected $authService;
	protected $parser;
	protected $viewHelper;
	protected $viewRenderer;
	protected $cryptographyService;
	
	public function __construct(PostServiceInterface $postsService
								, AuthenticationService $authService
								, HelperPluginManager $viewHelper
								, TreeRendererInterface $viewRenderer
								, $cryptographyService
			)
	{
		$this->postsService	= $postsService;
		$this->authService	= $authService;
		$this->viewHelper	= $viewHelper;
		$this->viewRenderer	= $viewRenderer;
		$this->cryptographyService	= $cryptographyService;

		$this->viewHelper->get('headScript')->appendFile('/js/blog/posts.js', 'text/javascript');
		$this->viewHelper->get('headLink')->appendStylesheet('/css/blog/posts.css');
	}
	
	public function indexAction()
	{
		$posts				= $this->postsService->getPublishedPosts(array(), 6);
		$postsCategories	= $this->postsService->getCategories();
echo $this->cryptographyService->encrypt('hello world !');
		$viewModel = new ViewModel(
				array(
						'posts' => $posts,
						'categories' => $postsCategories,
				)
			);
		return $viewModel;
	}
	
	public function administrationIndexAction()
	{
		$request	= $this->getRequest();
		
		if($request->isPost())
		{
			$post	= $request->getPost();
			if(isset($post['action'])) {
				$action	= $post['action'];
				$result	= $this->postsService->{$action.'Post'}($post['post_id'], $this->authService->getStorage()->read()->id);

			    return new JsonModel(array('Foo' => $result));
			}
		}
		else
		{
			$this->postsService->publishPost(72, 3);
			$posts	= $this->postsService->getAllPosts();
			$viewModel = new ViewModel(
					array(
							'posts' => $posts,
					)
				);
			return $viewModel;
		}
	}
	
	
	public function categoryIndexAction() {
    	$categoryId		= $this->getEvent()->getRouteMatch()->getParam('categoryId');
		$category		= $this->postsService->getCategory($categoryId)[0];
		$posts			= $this->postsService->getPublishedPosts(array('category_id = '.$categoryId));

		$viewModel = new ViewModel(array(
						'posts'		=> $posts,
						'category'	=> $category,
				)
			);
		
		return $viewModel;
	}
	
	
	public function readAction() {
    	$postId	= $this->getEvent()->getRouteMatch()->getParam('id');
    	
		$post 				= $this->postsService->getPost($postId);
		$postsCategories	= $this->postsService->getCategories();
		$recentPosts		= $this->postsService->getPublishedPosts(array('category_id = '.$post->getCategoryId(),
																			'id != '.$post->getId()
		));
		$closestPosts		= $this->postsService->getClosestPosts($postId);
		
		return new ViewModel(array('post' => $post,
									'categories'	=> $postsCategories,
									'recentPosts'	=> $recentPosts,
									'closestPosts'	=> $closestPosts,
		));
	}
	

	public function createAction()
	{
		$form	= $this->postsService->getCreateForm();
    	$request = $this->getRequest();

    	if ($request->isPost())
    	{
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			$post	= $form->getData();
    			$post->setAuthorId($this->authService->getStorage()->read()->id);
    			$row =	$this->postsService->savePost($post);
    		}
    	}
		
		$viewModel = new ViewModel(
				array(
						'form' => $form,
// 						'errorMessages' => $this->flashMessenger()->getErrorMessages(),
// 						'successMessages' => $this->flashMessenger()->getCurrentSuccessMessages(),
				)
			);
		
		return $viewModel;
	}
	
	public function editAction()
	{
    	$request = $this->getRequest();
    	$postId	= $this->getEvent()->getRouteMatch()->getParam('id');

		$form	= $this->postsService->getCreateForm();
		
    	$originalPost	= $this->postsService->getPost($postId);

    	if ($request->isPost())
    	{
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			$post	= $form->getData();
    			$post->setId($postId);
    			$post->setAuthorId($originalPost->getAuthorId());

    			$row =	$this->postsService->updatePost($post, $postId);
    		}
    	}
    	else {
    		$form->bind($originalPost);
    	}
		
		$viewModel = new ViewModel(
				array(
						'postId'	=> $postId,
						'form' => $form,
				)
			);
		
		return $viewModel;
	}
	
	
	public function previewAction() {
		$post	= $this->postsService->createPost($this->getRequest()->getPost()->toArray());
		$partialView	= new ViewModel();
		$partialView->setTerminal(true)
					->setTemplate('blog/posts/partials/post-preview')
					->setVariables(array('post' => $post));
	
		return new JsonModel(array('preview' => $this->viewRenderer->render($partialView)));
	}
	
	
	
	protected function _getHelper($helper, $serviceLocator)
	{
		return $this->getServiceLocator()
		->get('viewhelpermanager')
		->get($helper);
	}
}