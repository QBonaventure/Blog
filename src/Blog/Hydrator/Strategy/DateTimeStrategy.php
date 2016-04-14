<?php
namespace Blog\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateTimeStrategy extends DefaultStrategy
{
	/**
	 * {@inheritdoc}
	 *
	 * Convert a string value into a DateTime object
	 */
	public function hydrate($value)
	{
		if (is_string($value)) {
			$value = new DateTime($value);
		}

		return $value;
	}
}