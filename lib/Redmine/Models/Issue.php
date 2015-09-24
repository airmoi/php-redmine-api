<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Redmine\Models;

/**
 * Description of Issue
 *
 * @author romain
 * 
 * @property int $id
 * @property string $subject
 * @property string $description
 * @property int $project_id
 * @property string $project_name
 * @property int $category_id
 * @property string $category_name
 * @property int $priority_id
 * @property string $priority_name
 * @property int $status_id
 * @property string $status_name
 * @property int $tracker_id
 * @property string $tracker_name
 * @property int $assigned_to_id
 * @property string $assigned_name
 * @property int $author_id
 * @property string $author_name
 * @property string $due_date
 * @property string $start_date
 * @property array $watcher_user_ids
 * @property int $fixed_version_id
 * @property string $fixed_version_name
 * @property string $created_on
 * @property string $updated_on
 * @property int $done_ratio
 */
class Issue extends AbstractModel {
    /** @var Journal[] */
    public $journals = [];
    
    public function attributes() {
        return [
            'id',
            'subject',
            'description',
            'project_id',
            'project_name',
            'category_id',
            'category_name',
            'priority_id',
            'priority_name',
            'status_id',
            'status_name',
            'tracker_id',
            'tracker_name',
            'assigned_to_id',
            'assigned_to_name',
            'author_id',
            'author_name',
            'due_date',
            'start_date',
            'watcher_user_ids',
            'fixed_version_id',
            'fixed_version_name',
            'created_on',
            'updated_on',
            'done_ratio',
        ];
    }
    
    public function relations() {
        return [
            'project' => ['Project', 'project_id']
        ];
    }
    
    public function populate($data) {
        parent::populate($data);
        
        // populate journal
        if(isset($data['journals'])){
            foreach ( $data['journals'] as $record ) {
                $this->journals[] = new Journal($record);
            }
        }
    }  
}
