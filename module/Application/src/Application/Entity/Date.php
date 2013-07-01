<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface; 

/**
 * A Date.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="date")
 * @property int $id
 * @property string $date
 * @property string $transaction
 */
class Date 
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
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;
    
    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    

    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="date", cascade={"persist", "remove"}) 
     */  
    protected $transactions;


    
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
    
    public function setDate(\DateTime $date = null){
        
        if ($date==null){
            $date = new \DateTime("now");
        }
        $this->date = $date;
        return $this;
    }
    
    public function getDate(){
                
        if (!isset($this->date)){
            $this->setDate();
        }
        return $this->date;
        //return $this->date->format('Y-m-d H:i');
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
        return $this->created;
        //return $this->created->format('Y-m-d H:i');
    }
    
    public function setTransactions($transactions = array()){
        $this->transactions = $transactions;
        return $this;
    }
    
    public function getTransactions(){
        
        if (!isset($this->transactions)){            
            $this->setTransactions();
        }
        return $this->transactions;
    }
     
    public function removeTransaction(Transaction $transaction) {
        
        throw new \Exception('Not implemented'); // deleted by the entity manager
    }
 
    public function addTransaction(Transaction $transaction) {
        $transaction->setDate($this);
        $transactions = $this->getTransactions();        
        $transactions[] = $transaction;
        $this->setTransactions($transactions);
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
                'name'     => 'date',
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