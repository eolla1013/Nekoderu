<?php
namespace App\Model\Table;

use CakeDC\Users\Model\Table\UsersTable;
use Cake\ORM\RulesChecker;

/**
 * Users Model
 */
class MyUsersTable extends UsersTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        
        parent::initialize($config);
        $this->displayField('name');
        
        $this->hasMany('Cats', [
            'foreignKey' => 'users_id'
        ]);
        $this->hasMany('Favorites', [
            'foreignKey' => 'users_id'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'users_id'
        ]);
        $this->hasOne('Avatars', [
            'foreignKey' => 'users_id'
        ]);
        $this->hasMany('Similarities', [
            'foreignKey' => 'user_id'
        ]);
    }
    
     // In a table class
    public function buildRules(RulesChecker $rules)
    {
        parent::buildRules($rules);
        $rules->add($rules->isUnique(['email']), '_isUnique', [
            'errorField' => 'email',
            'message' => __d('CakeDC/Users', 'Email already exists')
        ]);
    
        return $rules;
    }
    
}