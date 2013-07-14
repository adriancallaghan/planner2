<?php
namespace Application\Form;

use Zend\Form\Form;




class TransactionForm extends Form
{


    
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('payment');
        $this->setAttribute('method', 'post');
        
        $this->add(array(     
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'date_id',
            'attributes' =>  array(
                'id' => 'date_id',                
                'options' => array(
                    '0' => 'Error! Cannot load Dates',
                ),
            ),
            'options' => array(
                'label' => 'Date',
            ),
        ));
        
        $this->add(array(     
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'payment_id',
            'attributes' =>  array(
                'id' => 'payment_id',                
                'options' => array(
                    '0' => 'Error! Cannot load Payments',
                ),
            ),
            'options' => array(
                'label' => 'Payment',
            ),
        ));    

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'amount',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Amount',
            ),
        )); 
                
        $this->add(array(
            'name' => 'comment',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Comment',
                'autocomplete'=>'off',
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