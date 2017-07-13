<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CatImages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Cats
 *
 * @method \App\Model\Entity\CatImage get($primaryKey, $options = [])
 * @method \App\Model\Entity\CatImage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CatImage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CatImage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CatImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CatImage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CatImage findOrCreate($search, callable $callback = null)
 */
class CatImagesTable extends Table
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

        $this->table('cat_images');
        $this->displayField('id');
        $this->primaryKey('id');
        
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'users_id'
        ]);
        $this->belongsTo('Cats', [
            'foreignKey' => 'cats_id',
            'joinType' => 'INNER'
        ]);
        
        $this->hasMany('CatImageAnalyses', [
            'foreignKey' => 'catImage_id',
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
        $rules->add($rules->existsIn(['cats_id'], 'Cats'));

        return $rules;
    }
}
