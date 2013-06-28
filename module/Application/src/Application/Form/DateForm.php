<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\FormInterface as FormInterface;



class DateForm extends Form 
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('date');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'date',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Date',
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
    
    
    /**
     * Retrieve the validated data
     *
     * By default, retrieves normalized values; pass one of the
     * FormInterface::VALUES_* constants to shape the behavior.
     *
     * @param  int $flag
     * @return array|object
     * @throws Exception\DomainException
     */
    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {        
        $data = parent::getData($flag);
        
        // date should be a date object
        if (isset($data['date'])){
            $data['date'] = new \DateTime($data['date']);
        }
        return $data;
    }
}