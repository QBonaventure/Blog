<?php
namespace Blog\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AdministrationMenu extends AbstractHelper {
	
	public function __invoke($subMenu = null) {
		if($subMenu) 
			return $this->getView()->render('blog/administration/partials/'.$subMenu.'-menu');
		return $this->getView()->render('blog/administration/partials/menu');
	}
}