<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CatImageAnalyses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $CatImages
 *
 * @method \App\Model\Entity\CatImageAnalysis get($primaryKey, $options = [])
 * @method \App\Model\Entity\CatImageAnalysis newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CatImageAnalysis[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CatImageAnalysis|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CatImageAnalysis patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CatImageAnalysis[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CatImageAnalysis findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CatImageAnalysesTable extends Table
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

        $this->table('cat_image_analyses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CatImages', [
            'foreignKey' => 'catImage_id',
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
            ->requirePresence('analyzer', 'create')
            ->notEmpty('analyzer');

        $validator
            ->requirePresence('data', 'create')
            ->notEmpty('data');

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
        $rules->add($rules->existsIn(['catImage_id'], 'CatImages'));

        return $rules;
    }
}
