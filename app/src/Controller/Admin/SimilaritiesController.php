<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Similarities Controller
 *
 * @property \App\Model\Table\SimilaritiesTable $Similarities
 */
class SimilaritiesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Cats']
        ];
        $similarities = $this->paginate($this->Similarities);

        $this->set(compact('similarities'));
        $this->set('_serialize', ['similarities']);
    }

    /**
     * View method
     *
     * @param string|null $id Similarity id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $similarity = $this->Similarities->get($id, [
            'contain' => ['Cats']
        ]);

        $this->set('similarity', $similarity);
        $this->set('_serialize', ['similarity']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $similarity = $this->Similarities->newEntity();
        if ($this->request->is('post')) {
            $similarity = $this->Similarities->patchEntity($similarity, $this->request->data);
            if ($this->Similarities->save($similarity)) {
                $this->Flash->success(__('The similarity has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The similarity could not be saved. Please, try again.'));
            }
        }
        $cat1s = $this->Similarities->Cat1s->find('list', ['limit' => 200]);
        $cat2s = $this->Similarities->Cat2s->find('list', ['limit' => 200]);
        $this->set(compact('similarity', 'cat1s', 'cat2s'));
        $this->set('_serialize', ['similarity']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Similarity id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $similarity = $this->Similarities->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $similarity = $this->Similarities->patchEntity($similarity, $this->request->data);
            if ($this->Similarities->save($similarity)) {
                $this->Flash->success(__('The similarity has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The similarity could not be saved. Please, try again.'));
            }
        }
        $cat1s = $this->Similarities->Cat1s->find('list', ['limit' => 200]);
        $cat2s = $this->Similarities->Cat2s->find('list', ['limit' => 200]);
        $this->set(compact('similarity', 'cat1s', 'cat2s'));
        $this->set('_serialize', ['similarity']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Similarity id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $similarity = $this->Similarities->get($id);
        if ($this->Similarities->delete($similarity)) {
            $this->Flash->success(__('The similarity has been deleted.'));
        } else {
            $this->Flash->error(__('The similarity could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
