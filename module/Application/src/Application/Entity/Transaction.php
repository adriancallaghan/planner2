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
 * @property string $amount
 * @property string $reference
 * @property string $dated
 * @property int $id
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
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="transaction")
     */
    protected $account;
    
    /**
     * @ORM\Column(name="amount",type="string")
     */
    protected $amount;

    
    
    /**
     * @ORM\Column(name="reference",type="string")
     */
    protected $reference;

    
    /**
     * @ORM\Column(name="dated", type="datetime")
     */
    protected $dated;
        
    
    public function setId($id = 0){
        $this->id = $id;
        return $this;
    }
    
    public function getId(){
        
        if (!isset($this->id)){
            $this->setId();
        }
        return $this->id;
    }
    
    public function setAmount($amount = ''){
        $this->amount = $amount;
        return $this;
    }
    
    public function getAmount(){
        
        if (!isset($this->amount)){
            $this->setAmount();
        }
        return $this->amount;
    }        
    
    public function setReference($reference = 'none'){
        $this->reference = $reference;
        return $this;
    }
    
    public function getReference(){
        
        if (!isset($this->reference)){
            $this->setReference();
        }
        return $this->reference;
    }  
    
    public function setDated($dated = null){
        
        if ($dated==null){
            $dated = new \DateTime("now");
        }
        $this->dated = $dated;
        return $this;
    }
    
    public function getDated(){
                
        if (!isset($this->dated)){
            $this->setDated();
        }
        return $this->dated->format('Y-m-d H:i');
    }

    public function setAccount(Account $account = null){
        $this->account = $account;
        return $this;
    }
    
    public function getAccount(){
        
        if (!isset($this->account)){
            $this->setAccount();
        }
        return $this->account;
    }
    
    
    
    /** 
    *  @ORM\PrePersist 
    */
    public function prePersist()
    {
        $this->getDated(); // makes sure we have a default time set
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
                'name'     => 'message',
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
                'name'     => 'author',
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
                'name'     => 'email',
                'required' => false,
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