<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
	
	public function indexAction() {
		
		$viewModel = new ViewModel();
		return $viewModel;
	}
	
	public function lireAction() {
		
	}
}