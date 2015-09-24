<?php

namespace Redmine\Models;

use Redmine\Client;

/**
 * Abstract class for Models classes.
 *
 * @author Romain Dunand <airmoi at gmail dot com>
 */
abstract class  AbstractModel {
    
    /**
     * The client.
     *
     * @var Client
     */
    protected $client;
    
    /**
     * @var array attribute values indexed by attribute names
     */
    private $_attributes = [];
    
    /**
     * @var CustomField[] list of CustomField indexed by ID
     */
    private $_customFields = [];
    
    /**
     * @var array|null old attribute values indexed by attribute names.
     * This is `null` if the record [[isNewRecord|is new]].
     */
    private $_oldAttributes;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, $data = [])
    {
        $this->client = $client;
        $this->populate($data);
    }
    
    
    /**
     * Returns the list of attribute names.
     * @return array list of attribute names.
     */
    abstract function attributes();
    
    /**
     * Returns the list of related Models indexed by relation name including relation params.
     * The syntax is like the following:
     * [
     *      'relationName' => [ 'ModelClass', 'foreignKey' ]
     * ]
     * 
     * @return array list of related names.
     */
    abstract function relations();
    
    /**
     * PHP getter magic method.
     * This method allow attributes to be accessed like properties.
     *
     * @param string $name property name
     * @throws \InvalidArgumentException Attribute doesn't exists
     * @return string|\stdClass
     */
    public function __get($name)
    {
        if (isset( $this->_attributes[$name] ) || array_key_exists( $name, $this->_attributes )) {
            return $this->_attributes[$name];
        } elseif ( $this->getCustomFieldId($name) !== false ) {
            return $this->_customFields[$this->getCustomFieldId($name)]->value;
        } elseif ($this->hasAttribute($name)) {
            return null;
        } elseif (method_exists($this, 'get' . $name)) {
            $getter = 'get' . $name;
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidArgumentException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new InvalidArgumentException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * PHP setter magic method.
     * This method is overridden so that AR attributes can be accessed like properties.
     * @param string $name property name
     * @param mixed $value property value
     */
    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } 
        else {
            $setter = 'set' . $name;
            if (method_exists($this, $setter)) {
                // set property
                $this->$setter($value);

                return;
            } elseif (method_exists($this, 'get' . $name)) {
                throw new InvalidArgumentException('Setting read-only property: ' . get_class($this) . '::' . $name);
            } else {
                throw new InvalidArgumentException('Setting unknown property: ' . get_class($this) . '::' . $name);
            }
        }
    }
    
    private function addCustomField($customField){
        $this->_customFields[$customField['id']] = new CustomField($customField);
    }

    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return boolean whether the model has an attribute with the specified name.
     */
    public function hasAttribute($name)
    {
        return isset($this->_attributes[$name]) || in_array($name, $this->attributes()) || isset($this->_customFields[$name]);
    }
    
    public function populate($data){
        $attributes =  $this->attributes();
        foreach($data as $attribute => $value) {
            if(in_array($attribute, $attributes)){
                $this->$attribute = $value;
            }
            elseif( $attribute == 'custom_fields') { //Handle custom fields
                foreach( $value as $customField ){
                    $this->addCustomField($customField);
                }
            }
            elseif ( is_array($value)) { //Split compound fields [id, name] to field_id, field_name attributes
                foreach ($value as $key => $subValue) {
                    $attributeName = $attribute . '_' . $key;
                    if(in_array($attributeName, $attributes)) {
                        $this->$attributeName = $subValue;
                    }
                }
            }
        }
    }
    
    /**
     * Returns the custom field ID matching given name 
     * @param type $name
     * @return mixed custom field ID , FALSE not exists
     */
    public function getCustomFieldId($name) {
        foreach( $this->_customFields as $customField ) {
            if($customField->name == $name){
                return $customField->id;
            }
        }
        return false;
    }
    
    public function customFields() {
        $customfields = [];
        foreach( $this->_customFields as $customField ) {
            $customfields[] = $customField->name;
        }
        return $customfields;
    }
    
}
