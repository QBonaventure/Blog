<?php
namespace Blog\Form\Posts;

use Zend\Form\Element;
use Zend\Form\Form;
use Blog\Hydrator\PostFormHydrator;
use Zend\InputFilter\InputFilter;
use Zend\Db\Adapter\AdapterInterface;
 
class CreateForm extends Form
{
	
	protected $dbAdapter;
	
    public function __construct(AdapterInterface $dbAdapter)
    {
        parent::__construct('Blog\forms\posts');
        
        $this->dbAdapter	= $dbAdapter;
        
        $hydrator	= new PostFormHydrator();
        $this->setAttributes(array('method' => 'post',
        							'id'	=> 'create-post',
        							'name'	=> 'create-post',
        ))
        	 ->setHydrator($hydrator)
        	 ->setInputFilter(new InputFilter());



        $this->add(array(
        		'type' => 'Blog\Form\Fieldset\PostFieldset',
        		'options' => array(
        				'use_as_base_fieldset' => true,
        		),
        ));
        
        $this->get('post')->get('category_id')->setValueOptions($this->getOptionsForCategorySelect());


        $this->add(array(
        	'name' => 'preview',
        	'type' => 'Zend\Form\Element\Button',
        	'attributes' => array(
        		'onClick'	=> 'return preview_post()',
            ),
            'options' => array(
            	'label'	=> 'Preview',
            ),
        ));
 
        $this->add(
            array(
                'name' => 'submit',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => 'Save',
                    'class' => 'btn',
                    'id' => 'submit',
                ),
            )
        );
 
    }
	
	
	private function getOptionsForCategorySelect() {
		$sql       = 'SELECT id, name  FROM blog.ref_posts_categories';
		$statement = $this->dbAdapter->query($sql);
		$result    = $statement->execute();
		
		$selectData = array();
		
		foreach ($result as $res) {
			$selectData[$res['id']] = ucfirst($res['name']);
		}
		return $selectData;
	}
}