<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 

/**
 * An account.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="account")
 * @property string $name
 * @property string $created
 * @property string $transactions
 * @property int $id
 */
class Tags implements InputFilterAwareInterface 
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
     * @ORM\Column(name="name",type="string")
     */
    protected $name;
        
    /** 
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @ORM\ManyToMany(targetEntity="Transaction", mappedBy="tags", cascade={"persist", "remove"}) 
     */    
    protected $transactions;
    
    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;
    
 /*
    public function __construct(array $options = null) {
        
        $this->setComments(new \Doctrine\Common\Collections\ArrayCollection());
        
        return parent::__construct($options);
    }*/
    
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
    
    public function setName($name = 'Anonymous'){
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
        
    
    public function setTransactions($transactions){
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
        $transaction->setAlbum($this);
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
        $this->getCreated(); // makes sure we have a default time set
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
                'name'     => 'artist',
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
                'name'     => 'title',
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