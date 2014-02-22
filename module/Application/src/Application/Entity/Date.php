<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
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
 * @property int $account
 * @property string $transaction
 */
class Date 
{
    
    //use \Application\Traits\ReadOnly;
    
    
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    
    /**
     * @ORM\Column(name="date", type="date")
     */
    protected $date;
    
    
    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="date", cascade={"persist", "remove"}) 
     */  
    protected $transactions;
    

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $transactionTotal;
    
    
    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    

     /** 
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="date")
     */
    protected $account;
    
    
    
    public function __construct() {

        $this->date = new \DateTime();
        $this->transactionTotal = 0;
        $this->transactions = new Collections\ArrayCollection(); // no setter for this
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
    
    public function getId(){        
        return $this->id;
    }

    public function setDate(\DateTime $dateTime){
        $this->date = $dateTime;
        return $this;
    }
    
    public function getDate(){                
        return $this->date;
    }
    
    public function getTransactionTotal(){
        return $this->transactionTotal;
    }
    
    public function setTransactionTotal()
    {
        $this->transactionTotal = array_sum($this->getTransactions()->map(function($v){ 
            return $v->active ? $v->amount : 0;
            })->getValues());

        return;
    }
    
    public function getCreated(){                
        return $this->created;
    }  
    
    public function getTransactions(){
        return $this->transactions;
    }
     
    public function removeTransaction(Transaction $transaction) {

        $this->transactions->removeElement($transaction);
        $this->setTransactionTotal();
        return $this;
    }
 
    public function addTransaction(Transaction $transaction) {

        // check if this has a date, and if the date is the same, 
        // if not it has been moved call remove transaction on the old date
        // this is imperative, to maintain the "totalTransaction" amount intergity, which is then managed by the em
        if (!is_null($transaction->date) && $transaction->date!==$this){
            $transaction->date->removeTransaction($transaction);
        }
        
        $transaction->setDate($this); 
        
        // stop duplication
        if ($this->getTransactions()->contains($transaction)){            
            $this->removeTransaction($transaction);
        } 
        
        // now add
        $this->getTransactions()->add($transaction);
        
        // update the transaction total
        $this->setTransactionTotal();
        
        return $this;
    }
    
    
    /** 
    *  @ORM\PrePersist 
    */
    public function prePersist()
    {
        $this->created = new \DateTime("now");
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