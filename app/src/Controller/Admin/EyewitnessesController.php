<?php
namespace App\Controller\Admin;

/**
 * Eyewitnesses Controller
 *
 * @property \App\Model\Table\EyewitnessesTable $Eyewitnesses
 */
class EyewitnessesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Cats']
        ];
        $eyewitnesses = $this->paginate($this->Eyewitnesses);

        $this->set(compact('eyewitnesses'));
        $this->set('_serialize', ['eyewitnesses']);
    }

    /**
     * View method
     *
     * @param string|null $id Eyewitness id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $eyewitness = $this->Eyewitnesses->get($id, [
            'contain' => ['Users', 'Cats', 'EyewitnessImages']
        ]);

        $this->set('eyewitness', $eyewitness);
        $this->set('_serialize', ['eyewitness']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $eyewitness = $this->Eyewitnesses->newEntity();
        if ($this->request->is('post')) {
            $eyewitness = $this->Eyewitnesses->patchEntity($eyewitness, $this->request->data);
            if ($this->Eyewitnesses->save($eyewitness)) {
                $this->Flash->success(__('The eyewitness has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The eyewitness could not be saved. Please, try again.'));
            }
        }
        $users = $this->Eyewitnesses->Users->find('list', ['limit' => 200]);
        $cats = $this->Eyewitnesses->Cats->find('list', ['limit' => 200]);
        $this->set(compact('eyewitness', 'users', 'cats'));
        $this->set('_serialize', ['eyewitness']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Eyewitness id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $eyewitness = $this->Eyewitnesses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $eyewitness = $this->Eyewitnesses->patchEntity($eyewitness, $this->request->data);
            if ($this->Eyewitnesses->save($eyewitness)) {
                $this->Flash->success(__('The eyewitness has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The eyewitness could not be saved. Please, try again.'));
            }
        }
        $users = $this->Eyewitnesses->Users->find('list', ['limit' => 200]);
        $cats = $this->Eyewitnesses->Cats->find('list', ['limit' => 200]);
        $this->set(compact('eyewitness', 'users', 'cats'));
        $this->set('_serialize', ['eyewitness']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Eyewitness id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $eyewitness = $this->Eyewitnesses->get($id);
        if ($this->Eyewitnesses->delete($eyewitness)) {
            $this->Flash->success(__('The eyewitness has been deleted.'));
        } else {
            $this->Flash->error(__('The eyewitness could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
