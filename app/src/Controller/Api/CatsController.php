<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class CatsController extends AppController
{
    
    public $components = ['RequestHandler', 'CatsCommon' ];
    
    public $paginate = [
        'page' => 1,
        'limit' => 5,
        'maxLimit' => 15,
        'sortWhitelist' => [
            'id', 'name'
        ]
    ];
    
    public function add()
    {
        debug("dummy");
        exit;
    }
    
    public function setVisibility(){
        
        if ($this->request->is('post')) {
            
            $data = $this->request->data;
            
            $cat_id = $data['target'];
            $cat = $this->Cats->get($cat_id);
            
            
            if(!empty($this->Auth->user()['id'])){
                $uid = $this->Auth->user()['id'];
            }
            
            if($uid !== $cat->users_id){
                $error = ['description' => 'you are not the owner of this post'];
                $this->set(compact('error'));
                $this->set('_serialize', ['error']);
                return;
            }
            
            
            $cat->hidden = (0 === $data['visibility']);
            
            if ($this->Cats->save($cat)) {
                $result = ['cat' => $cat];
                $this->set(compact('$result'));
                $this->set('_serialize', ['$result']);
            }
            
        }
    }
    
    public function addSheltered()
    {
        $this->CatsCommon->add(2, "#保護してます"); // 2 - 保護してます
    }
    
    public function addSearching()
    {
        $this->CatsCommon->add(1, "#迷子猫探してます"); // 1 - 迷子猫探してます
    }
}