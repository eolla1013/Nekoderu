<?php

namespace App\Controller\Admin;

use Cake\Controller\Controller;
use App\Controller\AppController;
use Cake\Event\Event;

class CatsController extends AdminAppController
{
    public $components = array('NekoUtil');
    
    private function putOptions(){
        $statuses = $this->Cats->ResponseStatuses->find('list', [
            'keyField' => 'id',
            'valueField' => 'title'
        ])->toArray();
        $this->set(compact('statuses', 'statuses'));
        $this->set('_serialize', ['statuses']);
        
        $users = $this->Cats->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'username'
        ])->toArray();
        $this->set(compact('users', 'users'));
        $this->set('_serialize', ['users']);
    }
    
    public function index()
    {
        
        $data = $this->Cats->find('all')
            ->contain(['CatImages', 'Comments', 'Users', 'ResponseStatuses']);
            
        $cats = $this->paginate($data);
        
        $this->set(compact('cats'));
        $this->set('_serialize', ['cats']);
    }
    
    
    /**
     * View method
     *
     * @param string|null $id Cat Image id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cat = $this->Cats->get($id, [
            'contain' => ['CatImages', 'Comments', 'Users', 'ResponseStatuses']
        ]);

        $this->set('cat', $cat);
        $this->set('_serialize', ['cat']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cat = $this->Cats->newEntity();
        if ($this->request->is('post')) {
            $catImage = $this->Cat->patchEntity($cat, $this->request->data);
            if ($this->Cats->save($cat)) {
                $this->Flash->success(__('The cat image has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The cat image could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('cat', 'cat'));
        $this->set('_serialize', ['cat']);
        
        $this->putOptions();
    }

    /**
     * Edit method
     *
     * @param string|null $id Cat Image id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cat = $this->Cats->get($id, [
            'contain' => ['CatImages', 'Comments',  'Users', 'ResponseStatuses']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cat = $this->Cats->patchEntity($cat, $this->request->data);
            if ($this->Cats->save($cat)) {
                $this->Flash->success(__('The cat has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The cat could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('cat', 'cat'));
        $this->set('_serialize', ['cat']);
        
       $this->putOptions();
    }

    /**
     * Delete method
     *
     * @param string|null $id Cat Image id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cat = $this->Cats->get($id);
        if ($this->Cats->delete($cat)) {
            $this->Flash->success(__('The cat image has been deleted.'));
        } else {
            $this->Flash->error(__('The cat image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    



}