<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * CatImageAnalyses Controller
 *
 * @property \App\Model\Table\CatImageAnalysesTable $CatImageAnalyses
 */
class CatImageAnalysesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['CatImages']
        ];
        $catImageAnalyses = $this->paginate($this->CatImageAnalyses);

        $this->set(compact('catImageAnalyses'));
        $this->set('_serialize', ['catImageAnalyses']);
    }

    /**
     * View method
     *
     * @param string|null $id Cat Image Analysis id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $catImageAnalysis = $this->CatImageAnalyses->get($id, [
            'contain' => ['CatImages']
        ]);

        $this->set('catImageAnalysis', $catImageAnalysis);
        $this->set('_serialize', ['catImageAnalysis']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $catImageAnalysis = $this->CatImageAnalyses->newEntity();
        if ($this->request->is('post')) {
            $catImageAnalysis = $this->CatImageAnalyses->patchEntity($catImageAnalysis, $this->request->data);
            if ($this->CatImageAnalyses->save($catImageAnalysis)) {
                $this->Flash->success(__('The cat image analysis has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The cat image analysis could not be saved. Please, try again.'));
            }
        }
        $catImages = $this->CatImageAnalyses->CatImages->find('list', ['limit' => 200]);
        $this->set(compact('catImageAnalysis', 'catImages'));
        $this->set('_serialize', ['catImageAnalysis']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Cat Image Analysis id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $catImageAnalysis = $this->CatImageAnalyses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $catImageAnalysis = $this->CatImageAnalyses->patchEntity($catImageAnalysis, $this->request->data);
            if ($this->CatImageAnalyses->save($catImageAnalysis)) {
                $this->Flash->success(__('The cat image analysis has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The cat image analysis could not be saved. Please, try again.'));
            }
        }
        $catImages = $this->CatImageAnalyses->CatImages->find('list', ['limit' => 200]);
        $this->set(compact('catImageAnalysis', 'catImages'));
        $this->set('_serialize', ['catImageAnalysis']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Cat Image Analysis id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $catImageAnalysis = $this->CatImageAnalyses->get($id);
        if ($this->CatImageAnalyses->delete($catImageAnalysis)) {
            $this->Flash->success(__('The cat image analysis has been deleted.'));
        } else {
            $this->Flash->error(__('The cat image analysis could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
