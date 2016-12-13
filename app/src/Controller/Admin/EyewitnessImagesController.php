<?php
namespace App\Controller\Admin;

/**
 * EyewitnessImages Controller
 *
 * @property \App\Model\Table\EyewitnessImagesTable $EyewitnessImages
 */
class EyewitnessImagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Eyewitnesses']
        ];
        $eyewitnessImages = $this->paginate($this->EyewitnessImages);

        $this->set(compact('eyewitnessImages'));
        $this->set('_serialize', ['eyewitnessImages']);
    }

    /**
     * View method
     *
     * @param string|null $id Eyewitness Image id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $eyewitnessImage = $this->EyewitnessImages->get($id, [
            'contain' => ['Eyewitnesses']
        ]);

        $this->set('eyewitnessImage', $eyewitnessImage);
        $this->set('_serialize', ['eyewitnessImage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $eyewitnessImage = $this->EyewitnessImages->newEntity();
        if ($this->request->is('post')) {
            $eyewitnessImage = $this->EyewitnessImages->patchEntity($eyewitnessImage, $this->request->data);
            if ($this->EyewitnessImages->save($eyewitnessImage)) {
                $this->Flash->success(__('The eyewitness image has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The eyewitness image could not be saved. Please, try again.'));
            }
        }
        $eyewitnesses = $this->EyewitnessImages->Eyewitnesses->find('list', ['limit' => 200]);
        $this->set(compact('eyewitnessImage', 'eyewitnesses'));
        $this->set('_serialize', ['eyewitnessImage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Eyewitness Image id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $eyewitnessImage = $this->EyewitnessImages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $eyewitnessImage = $this->EyewitnessImages->patchEntity($eyewitnessImage, $this->request->data);
            if ($this->EyewitnessImages->save($eyewitnessImage)) {
                $this->Flash->success(__('The eyewitness image has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The eyewitness image could not be saved. Please, try again.'));
            }
        }
        $eyewitnesses = $this->EyewitnessImages->Eyewitnesses->find('list', ['limit' => 200]);
        $this->set(compact('eyewitnessImage', 'eyewitnesses'));
        $this->set('_serialize', ['eyewitnessImage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Eyewitness Image id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $eyewitnessImage = $this->EyewitnessImages->get($id);
        if ($this->EyewitnessImages->delete($eyewitnessImage)) {
            $this->Flash->success(__('The eyewitness image has been deleted.'));
        } else {
            $this->Flash->error(__('The eyewitness image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
