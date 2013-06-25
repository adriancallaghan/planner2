<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A Date.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="date")
 * @property int $id
 * @property string $date
 * @property string $activity
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
     * @param \Doctring\Common\Collections\ArrayCollection $property
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="date", cascade={"persist", "remove"}) 
     */  
    protected $activities;


    
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
    
    public function setDate($date = null){
        
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
        return $this->date->format('Y-m-d H:i');
    }

    public function setActivities($activities){
        $this->activities = $activities;
        return $this;
    }
    
    public function getActivities(){
        
        if (!isset($this->activities)){            
            $this->setActivities();
        }
        return $this->activities;
    }
     
    public function removeActivity(Activity $activity) {
        
        throw new \Exception('Not implemented'); // deleted by the entity manager
    }
 
    public function addActivity(Activity $activity) {
        $activity->setDate($this);
        $activities = $this->getActivities();        
        $activities[] = $activity;
        $this->setActivities($activities);
        return $this;
    }
    
    
    /** 
    *  @ORM\PrePersist 
    */
    public function prePersist()
    {
        $this->toArray(); // makes sure we have all default values set
    }

}