<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface; 

/**
 * An Account.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="account")
 * @property int $id
 * @property string $name
 * @property string $created
 * @property boolean $active
 */
class Account 
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
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    
    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    
    
    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;
    
    
    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="account", cascade={"persist", "remove"}) 
     */ 
    protected $payments;
    
    
    
    public function setId($id = 0){
        $this->id = (int)$id;
        return $this;
    }
    
    public function getId(){
        
        if (!isset($this->id)){
            $this->setId();
        }
        return $this->id;
    }
    
    public function setName($name = ''){
        $this->name = $name;
        return $this;
    }
    
    public function getName(){
        
        if (!isset($this->name)){
            $this->setName();
        }
        return $this->name;
    }   
    
    public function setCreated($created = null){
        
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
    
    public function setPayments($payment = array()){
        $this->payments = $payment;
        return $this;
    }
    
    public function getPayments(){
        
        if (!isset($this->payments)){
            $this->setPayments();
        }
        return $this->payments;
    }
    
    public function removePayment(Payment $payment) {
        
        throw new \Exception('Not implemented'); // deleted by the entity manager
    }
 
    public function addPayment(Payment $payment) {
        $payment->setAccount($this);
        $payments = $this->getPayments();        
        $payments[] = $payment;
        $this->setPayments($payments);
        return $this;
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
                'name'     => 'name',
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