<?php
namespace Application\Form;

use Zend\Form\Form;




class AccountForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('account');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Name',
                'autocomplete'=>'off',
            ),
        ));
        $this->add(array(
            'name' => 'startBalance',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Start Balance',
            ),
        )); 
        $this->add(array(     
            'type' => 'Zend\Form\Element\Checkbox',       
            'name' => 'active',
            'attributes' =>  array(             
                'options' => array(
                    '1' => '1',
                ),
                'value'=>'1',
            ),
            'options' => array(
                'label' => 'Active',
                'autocomplete'=>'off',                
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}