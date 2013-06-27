<?php
namespace Application\Form;

use Zend\Form\Form;




class PaymentForm extends Form
{


    
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('account');
        $this->setAttribute('method', 'post');
        
        $this->add(array(     
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'account_id',
            'attributes' =>  array(
                'id' => 'account_id',                
                'options' => array(
                    '0' => 'Error! Cannot load Accounts',
                ),
            ),
            'options' => array(
                'label' => 'Account',
            ),
        ));    

        
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Description',
                'autocomplete'=>'off',
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
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'frequency',
            'attributes' =>  array(
                'id' => 'frequency_id',                
                'options' => array(
                    '0' => 'None',
                    '1' => 'Weekly',
                    '2' => 'Monthly',
                ),
                'value'=>'0',
            ),
            'options' => array(
                'label' => 'Frequency',
            ),
        ));  
        
        $this->add(array(
            'name' => 'day',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Day number (1-7 using weekly) (1-31 using monthly)',
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