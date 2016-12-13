<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Eyewitnesses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Cats
 * @property \Cake\ORM\Association\HasMany $EyewitnessImages
 *
 * @method \App\Model\Entity\Eyewitness get($primaryKey, $options = [])
 * @method \App\Model\Entity\Eyewitness newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Eyewitness[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Eyewitness|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Eyewitness patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Eyewitness[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Eyewitness findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EyewitnessesTable extends Table
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

        $this->table('eyewitnesses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Cats', [
            'foreignKey' => 'cat_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('EyewitnessImages', [
            'foreignKey' => 'eyewitness_id'
        ]);
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
            ->allowEmpty('content');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['cat_id'], 'Cats'));

        return $rules;
    }
}
