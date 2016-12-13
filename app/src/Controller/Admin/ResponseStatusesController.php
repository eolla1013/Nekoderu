<?php
namespace App\Controller\Admin;

/**
 * ResponseStatuses Controller
 *
 * @property \App\Model\Table\ResponseStatusesTable $ResponseStatuses
 */
class ResponseStatusesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $responseStatuses = $this->paginate($this->ResponseStatuses);

        $this->set(compact('responseStatuses'));
        $this->set('_serialize', ['responseStatuses']);
    }

    /**
     * View method
     *
     * @param string|null $id Response Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $responseStatus = $this->ResponseStatuses->get($id, [
            'contain' => []
        ]);

        $this->set('responseStatus', $responseStatus);
        $this->set('_serialize', ['responseStatus']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $responseStatus = $this->ResponseStatuses->newEntity();
        if ($this->request->is('post')) {
            $responseStatus = $this->ResponseStatuses->patchEntity($responseStatus, $this->request->data);
            if ($this->ResponseStatuses->save($responseStatus)) {
                $this->Flash->success(__('The response status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The response status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('responseStatus'));
        $this->set('_serialize', ['responseStatus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Response Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $responseStatus = $this->ResponseStatuses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $responseStatus = $this->ResponseStatuses->patchEntity($responseStatus, $this->request->data);
            if ($this->ResponseStatuses->save($responseStatus)) {
                $this->Flash->success(__('The response status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The response status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('responseStatus'));
        $this->set('_serialize', ['responseStatus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Response Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $responseStatus = $this->ResponseStatuses->get($id);
        if ($this->ResponseStatuses->delete($responseStatus)) {
            $this->Flash->success(__('The response status has been deleted.'));
        } else {
            $this->Flash->error(__('The response status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
