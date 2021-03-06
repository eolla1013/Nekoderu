<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ResponseStatuses Model
 *
 * @method \App\Model\Entity\ResponseStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\ResponseStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ResponseStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ResponseStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResponseStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ResponseStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ResponseStatus findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResponseStatusesTable extends Table
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

        $this->table('response_statuses');
        $this->displayField('title');
        $this->primaryKey('id');
        
        $this->hasMany('Cats', [
            'foreignKey' => 'response_statuses_id'
        ]);

        $this->addBehavior('Timestamp');
        
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        return $validator;
    }
}
