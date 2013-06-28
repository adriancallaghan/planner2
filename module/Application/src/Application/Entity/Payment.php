<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 

/**
 * A Transaction associated with activity.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="payment")
 * @property int $id
 * @property text $description
 * @property integer $account
 * @property integer $tags
 * @property integer $frequency
 * @property integer $day
 * @property float $amount
 * @property string $created
 * @property boolean $active
 */
class Payment implements InputFilterAwareInterface 
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
     * @ORM\Column(name="description",type="string")
     */
    protected $description;
        
    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="payment", cascade={"persist", "remove"}) 
     */  
    protected $transactions;
    
    
    /** 
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="payment")
     */
    protected $account;
    
    /** 
     * HAS BEEN DISABLED IN THE COMMENTS LOOK!!!!
     * @--ORM\ManyToMany(targetEntity="Tags", inversedBy="payment")
     */
    //protected $tags;
    
    /**
     * @ORM\Column(name="frequency",type="integer")
     */
    protected $frequency;

    /**
     * @ORM\Column(name="day",type="integer")
     */
    protected $day;
    
    /**
     * @ORM\Column(name="amount",type="float")
     */
    protected $amount;
        
    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    
    /**
     * @ORM\Column(name="active",type="boolean")
     */
    protected $active;
    
        
    
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
    
    public function setDescription($description = ''){
        $this->description = $description;
        return $this;
    }
    
    public function getDescription(){
        
        if (!isset($this->description)){
            $this->setDescription();
        }
        return $this->description;
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
    
    public function setTags($tags = array()){
        $this->tags = $tags;
        return $this;
    }
    
    public function getTags(){
        
        if (!isset($this->tags)){            
            $this->setTags();
        }
        return $this->tags;
    }
     
    public function removeTag(Tag $tag) {
        
        throw new \Exception('Not implemented'); // deleted by the entity manager
    }
 
    public function addTag(Tag $tag) {
        $tag->setDate($this);
        $tags = $this->getTags();        
        $tags[] = $tag;
        $this->setTags($tags);
        return $this;
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
        $transaction->setPayment($this);
        $transactions = $this->getTransactions();        
        $transactions[] = $transaction;
        $this->setTransactions($transactions);
        return $this;
    }
    
    
    
    public function setFrequency($frequency = 0){
        $this->frequency = (int) $frequency;
        return $this;
    }
    
    public function getFrequency(){
        
        if (!isset($this->frequency)){
            $this->setFrequency();
        }
        return $this->frequency;
    } 
  
    public function setDay($day = 1){
        $this->day = (int) $day;
        return $this;
    }
    
    public function getDay(){
        
        if (!isset($this->day)){
            $this->setDay();
        }
        return $this->day;
    } 
    
    public function setAmount($amount = 0.00){
        $this->amount = (float) $amount;
        return $this;
    }
    
    public function getAmount(){
        
        if (!isset($this->amount)){
            $this->setAmount();
        }
        return $this->amount;
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
                'name'     => 'description',
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