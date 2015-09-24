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
class JournalDetail {
    
    /** @var string */
    public $property;
    /** @var string */
    public $name;
    /** @var string */
    public $old_value;
    /** @var string */
    public $old_new;
    
    public function __construct($data) {
        $this->property = $data['property'];
        $this->name = $data['name'];
        $this->old_value = @$data['old_value'];
        $this->new_value = @$data['new_value'];
    }
}
