<?php
namespace App\Model\Table;

use App\Model\Entity\Cat;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Search\Manager;

/**
 * Cats Model
 *
 */
class CatsTable extends Table
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

        $this->table('cats');
        $this->displayField('id');
        $this->primaryKey('id');
        
        $this->hasMany('CatImages', [
            'foreignKey' => 'cats_id'
        ]);
        $this->hasMany('Favorites', [
            'foreignKey' => 'cats_id'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'cats_id',
        ]);
        $this->hasMany('Answers', [
            'foreignKey' => 'cats_id',
        ]);
        $this->hasMany('Eyewitnesses', [
            'foreignKey' => 'cat_id',
        ]);
        $this->belongsTo('ResponseStatuses', [
            'foreignKey' => 'response_statuses_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'users_id',
        ]);
        
        $this->belongsToMany('Tags', [
            'joinTable' => 'CatsTags',
            'foreignKey'  => 'cats_id',
            'targetForeignKey' => 'tags_id'
        ]);

        // Add the behaviour to your table
        $this->addBehavior('Search.Search');
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
            ->integer('time')
            ->allowEmpty('time');

        $validator
            ->allowEmpty('locate');

        $validator
            ->allowEmpty('image_url');

        $validator
            ->integer('flg')
            ->allowEmpty('flg');

        $validator
            ->allowEmpty('address');

        $validator
            ->integer('status')
            ->allowEmpty('status');

        return $validator;
    }

    // Configure how you want the search plugin to work with this table class
    public function searchConfiguration()
    {
        $search = new Manager($this);
        $search
            ->value('author_id', [
                'field' => $this->aliasField('author_id')
            ])
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->compare('from_time', [
                'operator' => '>',
                'field' => [$this->aliasField('time')]
            ])
            ->compare('end', [
                'operator' => '<',
                'field' => [$this->aliasField('time')]
            ])
            ->callback('flgs', [
                'callback' => function ($query, $args, $manager) {
                    return $query->where([$this->aliasField('flg') . " IN" => explode(',', $args['flgs'])]);
                }
            ]);

        return $search;
    }
}
