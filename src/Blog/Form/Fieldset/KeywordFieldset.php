<?php
namespace Blog\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Blog\Model\Keyword;
use Blog\Hydrator\KeywordFormHydrator;
use Blog\Hydrator\KeywordHydrator;


class KeywordFieldset extends Fieldset implements InputFilterProviderInterface
{
	
	public function __construct()
	{
		parent::__construct('keyword');
		
		$this->setHydrator(new KeywordHydrator())
			 ->setObject(new Keyword());


		 $this->add(array(
		 	'name'	=> 'id',
		 	'type' => 'Zend\Form\Element\Hidden',
			'attributes'	=> array(
				'required'	=> false,
			),
		 ));
		
		$this->add(array(
			'name'	=> 'word',
            'type' => 'Zend\Form\Element\Text',
			'attributes'	=> array(
                'placeholder' => 'Keyword',
				'required'	=> true,
			),
		));
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see \Zend\InputFilter\InputFilterProviderInterface::getInputFilterSpecification()
	 */
	
	public function getInputFilterSpecification()
	{
		return array(
			'word'	=> array(
				'required'	=> true,
			),
			'id' => array(
	            'filters'  => array(
	                array('name' => 'ToInt'),
	            ),
			),
		);
	}
}