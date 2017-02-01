<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * Cats Controller
 *
 * @property \App\Model\Table\CatsTable $Cats
 */
class GamesController extends AppController
{
    
    public $paginate = [
        // その他のキーはこちら
        'maxLimit' => 20
    ];
    
    public $components = ['RequestHandler', 'CatsCommon', 'NekoUtil', 'NotificationManager'];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event); 
        
        //TODO: きっとやり方違う
        if($this->Auth->user()){
            $this->Auth->allow();
        }
        // else{
        //     $this->Auth->allow(['add', 'add2', 'view', 'data', 'grid', 'tag', 'photoGrid', 'comments', 'readNotification']);    
        // }
    }
    
    public function isAuthorized($user)
    {
        // Check that the $user is equal to the current user.
        $id = $this->request->params['pass'][0];
        if ($id == $user['id']) {
            return true;
        }
        return false;
    }
    
    public function similarity($cat_id)
    {
        $this->Similarities = TableRegistry::get('Similarities');
        $similarity = $this->Similarities->newEntity();
       
        if ($this->request->is('post')) {
            $similarity = $this->Similarities->patchEntity($similarity, $this->request->data);
            if ($this->Similarities->save($similarity)) {
            } else {
                $this->Flash->error(__('The answer could not be saved. Please, try again.'));
            }
        }
    
        $this->CatImages = TableRegistry::get('CatImages');
    
        $images = $this->CatImages->find('all')
        ->order('rand()')
        ->limit(2);
    
        $this->set(compact('images'));
        $this->set('_serialize', ['images']);
        
        $this->set(compact('similarity'));
        $this->set('_serialize', ['similarity']);
    }
    
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->layout('nekoderu');
    }
    

}
