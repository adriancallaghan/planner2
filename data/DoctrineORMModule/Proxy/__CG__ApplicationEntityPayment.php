<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Payment extends \Application\Entity\Payment implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }

    /**
     * {@inheritDoc}
     * @param string $name
     */
    public function __get($name)
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__get', array($name));

        return parent::__get($name);
    }

    /**
     * {@inheritDoc}
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__set', array($name, $value));

        return parent::__set($name, $value);
    }



    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'inputFilter', 'id', 'description', 'transactions', 'account', 'frequency', 'day', 'amount', 'created', 'active');
        }

        return array('__isInitialized__', 'inputFilter', 'id', 'description', 'transactions', 'account', 'frequency', 'day', 'amount', 'created', 'active');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Payment $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function setId($id = 0)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', array($id));

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription($description = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescription', array($description));

        return parent::setDescription($description);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', array());

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setAccount(\Application\Entity\Account $account = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAccount', array($account));

        return parent::setAccount($account);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAccount', array());

        return parent::getAccount();
    }

    /**
     * {@inheritDoc}
     */
    public function setTransactions($transactions = array (
))
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTransactions', array($transactions));

        return parent::setTransactions($transactions);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactions()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTransactions', array());

        return parent::getTransactions();
    }

    /**
     * {@inheritDoc}
     */
    public function removeTransaction(\Application\Entity\Transaction $transaction)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTransaction', array($transaction));

        return parent::removeTransaction($transaction);
    }

    /**
     * {@inheritDoc}
     */
    public function addTransaction(\Application\Entity\Transaction $transaction)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTransaction', array($transaction));

        return parent::addTransaction($transaction);
    }

    /**
     * {@inheritDoc}
     */
    public function setFrequency($frequency = 0)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFrequency', array($frequency));

        return parent::setFrequency($frequency);
    }

    /**
     * {@inheritDoc}
     */
    public function getFrequency()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFrequency', array());

        return parent::getFrequency();
    }

    /**
     * {@inheritDoc}
     */
    public function setDay($day = 1)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDay', array($day));

        return parent::setDay($day);
    }

    /**
     * {@inheritDoc}
     */
    public function getDay()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDay', array());

        return parent::getDay();
    }

    /**
     * {@inheritDoc}
     */
    public function setAmount($amount = 0)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAmount', array($amount));

        return parent::setAmount($amount);
    }

    /**
     * {@inheritDoc}
     */
    public function getAmount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAmount', array());

        return parent::getAmount();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreated(\DateTime $created = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreated', array($created));

        return parent::setCreated($created);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', array());

        return parent::getCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setActive($active = true)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setActive', array($active));

        return parent::setActive($active);
    }

    /**
     * {@inheritDoc}
     */
    public function getActive()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getActive', array());

        return parent::getActive();
    }

    /**
     * {@inheritDoc}
     */
    public function prePersist()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'prePersist', array());

        return parent::prePersist();
    }

    /**
     * {@inheritDoc}
     */
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInputFilter', array($inputFilter));

        return parent::setInputFilter($inputFilter);
    }

    /**
     * {@inheritDoc}
     */
    public function getInputFilter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInputFilter', array());

        return parent::getInputFilter();
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOptions', array($options));

        return parent::setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toArray', array());

        return parent::toArray();
    }

}
