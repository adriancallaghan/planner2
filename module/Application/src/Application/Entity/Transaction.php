<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 

/**
 * A Comment associated with an Album.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="transaction")
 * @property int $id
 * @property int $date
 * @property int $payment 
 * @property text $comment
 * @property float $amount
 * @property boolean $active
 * @property datetime $created
 */
class Transaction implements InputFilterAwareInterface 
{
    
    use \Application\Traits\ReadOnly;
    
    
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @ORM\ManyToOne(targetEntity="Date", inversedBy="transactions")
     */
    protected $date;

    /** 
     * @ORM\ManyToOne(targetEntity="Payment", inversedBy="transactions")
     */
    protected $payment;

    /**
     * @ORM\Column(name="comment",type="text")
     */
    protected $comment;
    
    /**
     * @ORM\Column(name="amount",type="float")
     */
    protected $amount;
    
    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
        
    
    public function setId($id = 0){
        $this->id = (int) $id;
        return $this;
    }
    
    public function getId(){
        
        if (!isset($this->id)){
            $this->setId();
        }
        return $this->id;
    }     
    
    public function setDate(Date $date = null){
        $this->date = $date;
        return $this;
    }
    
    public function getDate(){
        
        if (!isset($this->date)){
            $this->setDate();
        }
        return $this->date;
    }
        
    public function setPayment(Payment $payment = null){
        $this->payment = $payment;
        return $this;
    }
    
    public function getPayment(){
        
        if (!isset($this->payment)){
            $this->setPayment();
        }
        return $this->payment;
    }
    
    
    public function setComment($comment = ''){
        $this->comment = (string) $comment;
        return $this;
    }
    
    public function getComment(){
        
        if (!isset($this->comment)){
            $this->setComment();
        }
        return $this->comment;
    }  
    
    public function setAmount($amount = '0'){
        $this->amount = (float) $amount;
        return $this;
    }
    
    public function getAmount(){
        
        if (!isset($this->amount)){
            $this->setAmount();
        }
        return $this->amount;
    } 
    
    public function setActive($active = true){
        $this->active = (bool) $active;
        return $this;
    }
    
    public function getActive(){
        
        if (!isset($this->active)){
            $this->setActive();
        }
        return $this->active;
    } 
    
    public function setCreated(\DateTime $created = null){
        
        if ($created==null){
            $created = new \DateTime("now");
        }
        $this->created = $created;
        return $this;
    }
    
    public function getCreated(){
                
        if (!isset($this->created)){
            $this->setCreated();
        }
        return $this->created->format('Y-m-d H:i');
    }

    
    /** 
    *  @ORM\PrePersist 
    */
    public function prePersist()
    {
        $this->toArray(); // makes sure we have all default values set
    }
    

    public function setInputFilter(InputFilterInterface $inputFilter = null)
    {
        
        if ($inputFilter==null){
            
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'       => 'id',
                'required'   => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'date_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'payment_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'amount',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
           
        }
        
        $this->inputFilter = $inputFilter;
        
        return $this;
    }

    public function getInputFilter()
    {
        
        if (!isset($this->inputFilter)) {
            $this->setInputFilter();        
        }
        
        return $this->inputFilter;
    } 
}