<?php
namespace Blog\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class PostListener implements ListenerAggregateInterface
{
	/**
	 * @var \Zend\Stdlib\CallbackHandler[]
	 */
	protected $listeners = array();

	/**
	 * {@inheritDoc}
	 */
	public function attach(EventManagerInterface $events)
	{
		$sharedEvents      = $events->getSharedManager();
		$this->listeners[] = $sharedEvents->attach('Blog\Service\PostService', 'postPublished', array($this, 'onPostPublished'), 100);
	}

	public function detach(EventManagerInterface $events)
	{
		foreach ($this->listeners as $index => $listener) {
			if ($events->detach($listener)) {
				unset($this->listeners[$index]);
			}
		}
	}

	public function onPostPublished($e)
	{
// 		var_dump($e);
	}
}