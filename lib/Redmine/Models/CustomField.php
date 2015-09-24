<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Redmine\Models;

/**
 * Description of CustomField
 *
 * @author romain
 */
class CustomField {
    /**
     *
     * @var int 
     */
    public $id;
    
    /**
     *
     * @var string 
     */
    public $name;
    
    /**
     *
     * @var string 
     */
    public $value;
    
    public function __construct($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->value = $data['value'];
    }
}
