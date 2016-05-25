<?php
namespace Blog\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Blog\Model\Post;
use Blog\Hydrator\PostHydrator;


class PostFieldset extends Fieldset implements InputFilterProviderInterface
{
	
	public function __construct()
	{
		parent::__construct('post');
		
		$this->setHydrator(new PostHydrator())
			 ->setObject(new Post());
		
		
		$this->add(array(
			'type' => 'Zend\Form\Element\Collection',
			'name' => 'keywords',
				'options' => array(
					'label' => 'Keywords',
					'count' => 1,
					'should_create_template' => true,
// 					'template_placeholder' => '__placeholder__',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'Blog\Form\Fieldset\KeywordFieldset',
					),
				),
		));
		
		
	
		
 
        $this->add(array(
        	'name' => 'add_keyword',
        	'type' => 'Zend\Form\Element\Button',
        	'attributes' => array(
        		'onClick'	=> 'return add_keyword()',
            ),
            'options' => array(
            	'label'	=> 'Add a keyword',
            ),
        ));
        
        $this->add(array(
                'name' => 'category_id',
                'type' => 'Zend\Form\Element\Select',
                'tabindex' => 2,
                'options' => array(
                        'label' => 'Category',
                        'empty_option' => 'Please select a category',
                )
        ));
        
//         $this->add(array(
//                 'name' => 'status_id',
//                 'type' => 'Zend\Form\Element\Select',
//                 'tabindex' =>2,
//                 'options' => array(
//                         'label' => 'Status',
//                         'empty_option' => 'Please select a status',
//                 )
//         ));
        
        
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'placeholder' => 'Title',
                    'required' => 'required',
                ),
                'options' => array(
                    'label' => 'Title',
                    'label_attributes' => array(
                        'class' => 'control-label'
                    ),
                ),
            )
        );
 
        $this->add(
            array(
                'name' => 'lead',
                'type' => 'Zend\Form\Element\Textarea',
                'attributes' => array(
                    'placeholder' => 'Lead',
                    'required' => 'required',
                ),
                'options' => array(
                    'label' => 'Lead',
                    'label_attributes' => array(
                        'class' => 'control-label'
                    ),
                ),
            )
        );
 
        $this->add(
            array(
                'name' => 'body',
                'type' => 'Zend\Form\Element\Textarea',
                'attributes' => array(
//                 	'data-adaptheight' => null,
                    'placeholder' => 'Body',
                    'required' => 'required',
//                 	'onkeyup'	=> 'textAreaAdjust(this)',
                ),
                'options' => array(
                    'label' => 'Body',
                    'label_attributes' => array(
                        'class' => 'control-label'
                    ),
                ),
            )
        );
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see \Zend\InputFilter\InputFilterProviderInterface::getInputFilterSpecification()
	 */
	
	public function getInputFilterSpecification()
	{
		return array(
			'category_id' => array(
	            'filters'  => array(
	                array('name' => 'ToInt'),
	            ),
			),
// 			'status_id' => array(
// 	            'filters'  => array(
// 	                array('name' => 'ToInt'),
// 	            ),
// 			),
			'title' => array(
				'required' => true,
			),
             'lead' => array(
                 'required' => true,
             ),
             'body' => array(
                 'required' => true,
             ),
		);
	}
}