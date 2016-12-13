<?php
namespace App\Controller\Admin;

use Cake\Event\Event;

/**
 * CatImages Controller
 *
 * @property \App\Model\Table\CatImagesTable $CatImages
 */
class CatImagesController extends AppController
{
    
    public $components = ['NekoUtil'];
    
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
        $catImages = $this->paginate($this->CatImages);
        
        $this->set(compact('catImages'));
        $this->set('_serialize', ['catImages']);
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
        $catImage = $this->CatImages->get($id, [
            'contain' => ['Cats']
        ]);

        $this->set('catImage', $catImage);
        $this->set('_serialize', ['catImage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $catImage = $this->CatImages->newEntity();
        if ($this->request->is('post')) {
            $catImage = $this->CatImages->patchEntity($catImage, $this->request->data);
            if ($this->CatImages->save($catImage)) {
                $this->Flash->success(__('The cat image has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The cat image could not be saved. Please, try again.'));
            }
        }
        $cats = $this->CatImages->Cats->find('list', ['limit' => 200]);
        $this->set(compact('catImage', 'cats'));
        $this->set('_serialize', ['catImage']);
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
        $catImage = $this->CatImages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $catImage = $this->CatImages->patchEntity($catImage, $this->request->data);
            if ($this->CatImages->save($catImage)) {
                $this->Flash->success(__('The cat image has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The cat image could not be saved. Please, try again.'));
            }
        }
        $cats = $this->CatImages->Cats->find('list', ['limit' => 200]);
        $this->set(compact('catImage', 'cats'));
        $this->set('_serialize', ['catImage']);
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
        $catImage = $this->CatImages->get($id);
        if ($this->CatImages->delete($catImage)) {
            $this->Flash->success(__('The cat image has been deleted.'));
        } else {
            $this->Flash->error(__('The cat image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function rotate($id = null)
    {
        $this->request->allowMethod(['post']);
        
        $catImage = $this->CatImages->get($id);
        $path = $this->NekoUtil->rotateImage($catImage->url,TMP, 90);
        
        $savePath = $this->NekoUtil->safeImage($path, TMP);
        
        if ($savePath === "") {
            die("不正な画像がuploadされました");
        }
        $result = $this->NekoUtil->s3Upload($savePath, '');
        // 書きだした画像を削除
        @unlink($savePath);
        
        //サムネイルを作成
        $savePath = $this->NekoUtil->createThumbnail($path, TMP);
        if ($savePath === "") {
            die("不正な画像がuploadされました");
        }
        $thumbnail = $this->NekoUtil->s3Upload($savePath, '');
        // 書きだした画像を削除
        @unlink($savePath);
        
        $catImage->url = $result['ObjectURL'];
        $catImage->thumbnail = $thumbnail['ObjectURL'];
        if ($this->CatImages->save($catImage)) {
            // $this->Flash->success('画像を保存しました。');
        }
        
        return $this->redirect(['action' => 'edit', $id]);
        
    }
}
