<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EyewitnessImages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Eyewitnesses
 *
 * @method \App\Model\Entity\EyewitnessImage get($primaryKey, $options = [])
 * @method \App\Model\Entity\EyewitnessImage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EyewitnessImage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EyewitnessImage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EyewitnessImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EyewitnessImage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EyewitnessImage findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EyewitnessImagesTable extends Table
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

        $this->table('eyewitness_images');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Eyewitnesses', [
            'foreignKey' => 'eyewitness_id',
            'joinType' => 'INNER'
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
            ->requirePresence('url', 'create')
            ->notEmpty('url');

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
        $rules->add($rules->existsIn(['eyewitness_id'], 'Eyewitnesses'));

        return $rules;
    }
}
