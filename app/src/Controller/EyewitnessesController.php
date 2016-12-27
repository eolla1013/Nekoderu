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
class EyewitnessesController extends AppController
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
    
    public function add($cat_id)
    {
        
        $cat = $this->Eyewitnesses->Cats->get($cat_id);
        
        
        if ($this->request->is('post')) {
            
            // $this->EyewitnessImages = TableRegistry::get('EyewitnessImages');
            
            $data = $this->request->data;
            
            $comment = (string)$data['comment'];
            $uid = $this->Auth->user()['id'];
            $user = $this->Eyewitnesses->Users->get($uid);

            $witness = $this->Eyewitnesses->newEntity();
            $witness->user_id = $uid;
            $witness->cat_id = $cat_id;
            $witness->content = $comment;
            
            
            if ($this->Eyewitnesses->save($witness)) {
                if($this->Flash){
                    $this->Flash->success('目撃情報を登録しました。');
                }
                
                $this->NotificationManager->notify($cat->users_id, 
                    '目撃情報が報告されました！', 
                    "@".$user->username."さんが目撃情報を報告してくれました！", 
                    Router::url(["controller" => "Cats","action" => "view", $cat_id])
                );
            }
            
            if (isset($data["image"])) {
                
                
                for($i=0; $i<count($data["image"]); $i++){
                    if(is_uploaded_file($data["image"][$i]["tmp_name"])){
                    
                        // アップロード処理
                        $file = $data["image"][$i];
                        $this->saveWitnessImage($file, $cat->id, $uid, $witness);
                    }
                }
            }
            
            return $this->redirect('/');
        }
        
        $this->set(compact('cat'));
        $this->set('_serialize', ['cat']);
    }
    
     public function saveWitnessImage($file, $cat_id, $uid, $witness){
        
        $savePath = $this->NekoUtil->safeImage($file["tmp_name"], TMP);
        if ($savePath === "") {
            die("不正な画像がuploadされました");
        }
        $result = $this->NekoUtil->s3Upload($savePath, '');
        // 書きだした画像を削除
        @unlink($savePath);
        
        //サムネイルを作成
        $savePath = $this->NekoUtil->createThumbnail($file["tmp_name"], TMP);
        if ($savePath === "") {
            die("不正な画像がuploadされました");
        }
        $thumbnail = $this->NekoUtil->s3Upload($savePath, '');
        // 書きだした画像を削除
        @unlink($savePath);
     
         
        if ($result) {
            
            $catImage = $this->Eyewitnesses->EyewitnessImages->newEntity();
            $catImage->url = $result['ObjectURL'];
            $catImage->thumbnail = $thumbnail['ObjectURL'];
            $catImage->users_id = $uid;
            $catImage->cats_id = $cat_id;
            $catImage->eyewitness_id = $witness->id;
            
            if ($this->Eyewitnesses->EyewitnessImages->save($catImage)) {
                // $this->Flash->success('画像を保存しました。');
                
                return $catImage;
            }
        }
        return null;
        
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
