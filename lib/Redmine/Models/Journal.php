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
class Journal {
    
    /** @var int*/
    public $id;
    /** @var string */
    public $notes;
    /** @var string */
    public $user_id;
    /** @var string */
    public $user_name;
    /** @var array */
    public $created_on;
    /** @var JournalDetail[] */
    public $details = [];
    
    
    public function __construct($data) {
        $this->id = $data['id'];
        $this->notes = @$data['notes'];
        $this->user_id = $data['user']['id'];
        $this->user_name = $data['user']['name'];
        $this->created_on = $data['created_on'];
        
        foreach ($data['details'] as $detail){
            $this->details[] = new \Redmine\Models\JournalDetail($detail);
        }
    }
}
