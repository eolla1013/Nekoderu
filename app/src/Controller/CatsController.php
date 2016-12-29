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
class CatsController extends AppController
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
        }else{
            $this->Auth->allow(['add', 'add2', 'view', 'data', 'grid', 'tag', 'photoGrid', 'comments', 'readNotification']);    
        }
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
    
    
    
    /**
     * Grid method
     *
     * @return \Cake\Network\Response|null
     */
    public function grid()
    {
        
        $q = $this->request->query;
        if(array_key_exists('order', $q)){
            $order = $q['order'];
        }else{
            $order = null;
        }
        
        
        $data = $this->CatsCommon->listCats(null, $order);
        $cats = $this->paginate($data);
        
        $session = $this->request->session();
        if($session->read('Last.Submit.Cat.Data') != null){
            $shown = $session->read('Last.Submit.Cat.Shown');
            if(!empty($shown) && $shown){
                $session->delete('Last.Submit.Cat.Shown');
                $session->delete('Last.Submit.Cat.Data');
            }else{
                $session->write('Last.Submit.Cat.Shown', false);
                $suggestRegistration = true;
                $this->set(compact('suggestRegistration'));
                $this->set('_serialize', ['suggestRegistration']);
            }
        }

        $this->set(compact('cats'));
        $this->set('_serialize', ['cats']);
    }
    
     /**
     * Tag method
     *
     * @return \Cake\Network\Response|null
     */
    public function tag($tag)
    {
        
        $data = $this->CatsCommon->listCatsByTag($tag);
        $cats = $this->paginate($data);
       
        $this->set(compact('cats'));
        $this->set('_serialize', ['cats']);
        
        $this->set(compact('tag'));
        $this->set('_serialize', ['tag']);
    }
    
    public function photoGrid()
    {
        $this->CatImages = TableRegistry::get("CatImages");
        $images = $this->CatImages->find('all')
            ->contain(['Cats'])
            ->where([
                'Cats.hidden =' => 0
            ])
            ->order(['Cats.created' => 'DESC']);
       
        $images = $this->paginate($images);
        
        $this->set(compact('images'));
        $this->set('_serialize', ['images']);
    }
    
    /**
     * Data method
     *
     * @return \Cake\Network\Response|null
     */
    public function data()
    {
        
        $q = $this->request->query;
        
        $data = $this->Cats->find('all')->contain([
                'CatImages', 
                'Comments' => function($q) {
                    return $q
                        ->order('created DESC')
                        ->limit(5);
                }
        ]);
        if($q != null){
            $data = $data
                ->where(['created >' => new \DateTime($q['map_start'])])
                ->where(['Cats.created <' => new \DateTime($q['map_end'])]);
        }
        $cats = $this->paginate($data);

        $this->set(compact('cats'));
        $this->set('_serialize', ['cats']);
    }
    
    public function view($id = null)
    {
        
        $cat = $this->Cats->get($id, [
            'contain' => [
                'CatImages', 
                'Comments'=> function ($q) {
                    return $q->order(['Comments.created' => 'DESC']);
                },  
                'Users', 'ResponseStatuses', 'Tags', 
                'Eyewitnesses' => function ($q) {
                    return $q
                        ->order(['Eyewitnesses.created' => 'DESC']);
                },
                'Eyewitnesses.Users',
                'Eyewitnesses.EyewitnessImages'
            ]
        ]);
        
        
        if($cat->hidden){
            return $this->redirect('/');
        }

        $this->set('cat', $cat);
        $this->set('_serialize', ['cat']);
        
        $answer = $this->Cats->Answers->find('all')
            ->contain(['Questions'])
            ->where([
                'Questions.name = ' => 'name',
                'Answers.cats_id = ' => $id
            ])
            ->first();
        if(!is_null($answer)){
            $name = $answer->value;
            
            $this->set('name', $name);
            $this->set('_serialize', ['name']);
        }
            
    }

    public function favorite($cats_id){
        
        if ($this->request->is('ajax')) {
            
            if(!empty($this->Auth->user()['id'])){
                $users_id = $this->Auth->user()['id'];
            } else {
                $this->Flash->error('You must login to favorite a cat');
                return;
            }
            
            $fav = $this->Cats->Favorites
                ->find('all')
                ->where(['cats_id =' => $cats_id, 'users_id =' => $users_id])
                ->first();
            if(is_null($fav)){
          
                $fav = $this->Cats->Favorites->newEntity();
                $fav->cats_id = $cats_id;
                $fav->users_id = $users_id;
                if ($this->Cats->Favorites->save($fav)) {
                    // $this->Flash->success('お気に入りに登録しました');
                    
                    //通知処理
                    $cat = $this->Cats->get($cats_id);
                    $u = $this->CatsCommon->currentUser();
                    $this->NotificationManager->notify($cat->users_id, 
                        'あなたの猫ちゃんに「いいね」がありました！', 
                        "@".$u->username."さんが「いいね」してくれました！", 
                        Router::url(["controller" => "Cats","action" => "view", $cats_id])
                    );
                    
                    $users_ids = $this->Cats->Comments->find()
                        ->select(['users_id'])
                        ->where(['cats_id = ' => $cats_id])
                        ->group('users_id')
                        ->having(['users_id !=' => 0, 'users_id !=' => $u->id]);
                        
                    foreach($users_ids as $users_id){
                        $this->NotificationManager->notify($users_id->users_id, 
                            'あなたが「コメント」した猫ちゃんに「いいね」がありました！', 
                            "@".$u->username."さんが「いいね」しました！", 
                            Router::url(["controller" => "Cats","action" => "view", $cats_id])
                        );
                    }
                    
                    $users_ids = $this->Cats->Favorites->find()
                        ->select(['users_id'])
                        ->where(['cats_id = ' => $cats_id])
                        ->group('users_id')
                        ->having(['users_id !=' => 0, 'users_id !=' => $u->id]);
                        
                    foreach($users_ids as $users_id){
                        $this->NotificationManager->notify($users_id->users_id, 
                            'あなたが「いいね」した猫ちゃんに新しい「いいね」がありました！', 
                            "@".$u->username."さんが「いいね」しました！", 
                            Router::url(["controller" => "Cats","action" => "view", $cats_id])
                        );
                    }
                }
            
            }
            
            $cat = $this->Cats->get($cats_id, [
                'contain' => ['Favorites']
            ]);
            
            $this->set(compact('cat'));
            $this->set('_serialize', ['cat']);
        }
    }
    
     /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function addSheltered()
    {
        $this->CatsCommon->add(2, "#保護してます"); // 2 - 保護してます
    }
    
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function addLost()
    {
        $this->CatsCommon->add(1, "#迷子猫探してます"); //1 - 迷子ネコ
    }
    
    public function add()
    {
        $this->CatsCommon->add(0, null);
    }
    
    public function add2(){
        return $this->add();
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
            'contain' => ['CatImages', 'Comments', 'Users', 'Answers']
        ]);
        
        
        if(!$this->CatsCommon->isCurrentUser($cat->users_id)){
            return $this->redirect('/');
        }
        
        $this->Questions = TableRegistry::get('Questions');
        $questions = $this->Questions->find('all');
        $this->set(compact('questions'));
        $this->set('_serialize', ['questions']);
        
       if ($this->request->is('post')) {

            $data = $this->request->data;
            
            $this->log($this->request->data);

            $locate = (string)$data['locate'];
            $address = (string)$data['address'];
            $ear_shape = $data['ear_shape'];
            
            $query = [
                "latlng" => h($locate),
                "language" => "ja",
                "sensor" => false
            ];
            
            $cat = $this->Cats->get($id, [
                'contain' => ['Answers']
            ]);
            $cat->locate = $locate;
            $cat->address = $address;
            $cat->ear_shape = $ear_shape;
            $cat->flg = 4;
            
            if ($this->Cats->save($cat)) {
                $this->Flash->success('猫を保存しました。');
            }
            
            $this->Questions = TableRegistry::get('Questions');
            $questions = $this->Questions->find('all');
            foreach($questions as $question){
                foreach($cat->answers as $answer){
                    if($answer->questions_id == $question->id){
                        $answer->value = (string)$data[$question->name];
                        if ($this->Cats->Answers->save($answer)) {
                        }
                    }
                }
            }
            
            return $this->redirect([ 'controller' => 'Cats', 'action' => 'view', $cat->id]);
        }
        
        $this->set(compact('cat', 'cat'));
        $this->set('_serialize', ['cat']);
        
    //   $this->putOptions();
    }
    
    public function comments($cats_id){
        
        if ($this->request->is('ajax')) {
            if(isset($this->request->data['limit'])){
                $limit = $this->request->data['limit'];
            }else{
                $limit = 20;
            }
            
            $comments = $this->Cats->Comments
                ->find('all', ['order' => ['Comments.created' => 'DESC']])
                ->contain(['Users', 'Reports'])
                ->where(['Comments.cats_id =' => $cats_id])
                ->limit(20)
                ->all();
                
            
            //不適切報告があったメッセージは投稿者以外の場合は空にする
            $comments = $comments->toArray();
            $uid = $this->Auth->user('id');
            foreach($comments as $key=>$comment){
                if(count($comment->reports) && $comment->users_id !== $uid){
                    $comment->comment = "";
                    unset($comments[$key]);
                }
            }
            // array_values($comments);
            
            $this->set(compact('comments'));
            $this->set('_serialize', ['comments']);
            
        }
        
    }
    
    public function addPhoto(){
        
        $response = 'url';
        
        if ($this->request->is('ajax')) {
            
            $data = $this->request->data;
            
            $cat_id = $data['cat_id'];
            
            // ユーザーIDを付与
            $uid = 0;
            if(!is_null($this->Auth->user('id'))){
                $uid = $this->Auth->user('id');
                
                $data = $this->request->data;
                if(is_uploaded_file($data["image"]["tmp_name"])){
                    $file = $data["image"];
                    $catImage = $this->saveCatImage($file, $cat_id, $uid);
                    
                    $response = $catImage;
                }
            }
        }
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
    
    public function addComment(){
            
        if ($this->request->is('ajax')) {
            
            $data = $this->request->data;
            
            $comment = $data['comment'];
            $cat_id = $data['cat_id'];
            
            // ユーザーIDを付与
            $uid = 0;
            if(!is_null($this->Auth->user('id'))){
                $uid = $this->Auth->user('id');
            }else{
                return;
            }
            
            $commentAdd = [];
            if (isset($data["image"])) {
                for($i=0; $i<count($data["image"]); $i++){
                    if(is_uploaded_file($data["image"][$i]["tmp_name"])){
                        // アップロード処理
                        $file = $data["image"][$i];
                        $catImage = $this->saveCatImage($file, $cat_id, $uid);
                        $commentAdd[] = $catImage->thumbnail;
                    }
                }
            }
            if(count($commentAdd) > 0){
                $comment = json_encode(array('comment' => $comment, 'media' => $commentAdd));
            }
            
            $this->CatsCommon->addComment($comment, $cat_id, $uid);
            
            $comments = $this->Cats->Comments
                ->find('all', ['order' => ['Comments.created' => 'DESC']])
                ->where(['Comments.cats_id =' => $cat_id])
                ->contain(['Users'])
                ->limit(20)
                ->all();
                
            $this->set(compact('comments'));
            $this->set('_serialize', ['comments']);
        }
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
        $uid = $this->Auth->user()['id'];
        if(!empty($uid)){
            $this->request->allowMethod(['post', 'delete']);
            $cat = $this->Cats->get($id);
            if ($this->Cats->delete($cat)) {
                $this->Flash->success(__('The cat has been deleted.'));
            } else {
                $this->Flash->error(__('The cat could not be deleted. Please, try again.'));
            }
        }

        return $this->redirect(['action' => 'grid', 'controller' => 'Cats']);
    }
    
    
    public function deleteComment($id = null)
    {
        $response = false;
        if ($this->request->is('ajax')) {
            
            $uid = $this->Auth->user()['id'];
            if(!empty($uid)){
                 
                $comment = $this->Cats->Comments->get($id);
                if($comment->users_id === $uid){
                    if ($this->Cats->Comments->delete($comment)) {
                        $response = true;
                    }
                }
            }
        }
        
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
    
    
    public function readNotification($sessionkey){
        $this->Cookie->write($sessionkey, true);
        // $this->request->session()->write($sessionkey, true);
    }
    
    public function report() {
        $this->Report = TableRegistry::get("Reports");
        $report = $this->Report->newEntity();
   
        if ($this->request->is('post')) {
            
            $data = $this->request->data;
            // debug($data);
            // exit;
            
            $report->description = $data['description'];
            $report->cat_id = $data['cat_id'];
            $report->comment_id = $data['comment_id'];
            $report->user_id = $this->Auth->user()['id'];

            if($this->Report->save($report)) {
                
                if($report->comment_id === 0){
                    $cat = $this->Cats->get($report->cat_id);
                    $cat->hidden = true;
                    if($this->Cats->save($cat)){
                    }
                }else{
                    
                }
            }
        }
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
