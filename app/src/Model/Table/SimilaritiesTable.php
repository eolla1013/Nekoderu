<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Similarities Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Cat1s
 * @property \Cake\ORM\Association\BelongsTo $Cat2s
 *
 * @method \App\Model\Entity\Similarity get($primaryKey, $options = [])
 * @method \App\Model\Entity\Similarity newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Similarity[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Similarity|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Similarity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Similarity[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Similarity findOrCreate($search, callable $callback = null)
 */
class SimilaritiesTable extends Table
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

        $this->table('similarities');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('CatImages', [
            'foreignKey' => 'image1_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('CatImages', [
            'foreignKey' => 'image2_id',
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
            ->boolean('answer')
            ->requirePresence('answer', 'create')
            ->notEmpty('answer');

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
        $rules->add($rules->existsIn(['image1_id'], 'CatImages'));
        $rules->add($rules->existsIn(['image1_id'], 'CatImages'));

        return $rules;
    }
}
