<?php
namespace App\Controller\Admin;

use Cake\Event\Event;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 */
class AppController extends \App\Controller\AppController
{
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        $user = $this->Auth->user();
        
        if($user && $user['role'] === 'admin'){
            return false;
        }
        return true;
        
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
