<?php

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Log\Log;

class CatsCommonComponent extends Component {
    
    // public $components = ["Cookie"];
    
    public $components = ['NotificationManager', 'NekoUtil'];
    
    public function initialize(array $config) {
        $this->Controller = $this->_registry->getController();
    }
    
    
    public function add($flg = 0, $appendTag = null){
        $this->Controller->CatImages = TableRegistry::get('CatImages');
        $this->Controller->Questions = TableRegistry::get('Questions');
        
        $questions = $this->Controller->Questions->find('all');
        $this->Controller->set(compact('questions'));
        $this->Controller->set('_serialize', ['questions']);
        
        if ($this->Controller->request->is('post')) {
            
            $data = $this->Controller->request->data;
            
            $this->Controller->log($this->request->data);

            $time = time();
            $locate = (string)$data['locate'];
            $address = (string)$data['address'];
            $comment = (string)$data['comment'];
            
            // ユーザーIDを付与
            $uid = 0;
            if(!empty($this->Controller->Auth->user()['id'])){
                $uid = $this->Controller->Auth->user()['id'];
            }

            $cat = $this->Controller->Cats->newEntity();
            $cat->locate = $locate;
            $cat->address = $address;
            if(array_key_exists ('ear_shape', $data)){
                $cat->ear_shape = $data['ear_shape'];
            } else {
                $cat->ear_shape = 0;
            }
            $cat->flg = $flg; 
            $cat->users_id = $uid;
            
            if ($this->Controller->Cats->save($cat)) {
                if($this->Controller->Flash){
                    $this->Controller->Flash->success('ねこを登録しました。');
                }
                
                $session = $this->Controller->request->session();
                $session->delete('Last.Submit.Cat.Data');
                $session->delete('Last.Submit.Cat.Shown');
                if($uid == 0){
                    $session->write('Last.Submit.Cat.Data', $cat);
                }
            }
            
            $this->Controller->Questions = TableRegistry::get('Questions');
            $questions = $this->Controller->Questions->find('all');
            foreach($questions as $question){
                if(array_key_exists ($question->name, $data)){
                    $answer = $this->Controller->Cats->Answers->newEntity();
                    $answer->cats_id = $cat->id;
                    $answer->questions_id = $question->id;
                    $answer->value = $data[$question->name];
                    if ($this->Controller->Cats->Answers->save($answer)) {
                    }
                }
            }
            
            if(mb_strlen($comment) > 0){
                $this->addComment($comment, $cat->id, $uid);
            }
            
            if (isset($data['file'])){
                $file = $data['file'];
                $savePath = $this->NekoUtil->saveGif($file["tmp_name"], TMP, $data['begin'], $data['end']);
                $this->saveCatMovie($savePath, $cat->id, $uid, false);
            }
                
            if (isset($data["image"])) {
                
                for($i=0; $i<count($data["image"]); $i++){
                    if(is_uploaded_file($data["image"][$i]["tmp_name"])){
                        // アップロード処理
                        $file = $data["image"][$i];
                        $this->saveCatImage($file["tmp_name"], $cat->id, $uid);
                    }
                }
            }
            
            if(!is_null($appendTag)){
                $this->addComment($appendTag, $cat->id, $uid);
            }
            
            return $this->Controller->redirect('/');
        }
    }
    
    public function saveImageOnAWS($file, $cat_id, $uid, $thumbnailing = true) {
        $this->Cats = TableRegistry::get('Cats');
        
        $result = $this->NekoUtil->s3Upload($file, '');
        
        if($thumbnailing){
            //サムネイルを作成
            $savePath = $this->NekoUtil->createThumbnail($file, TMP);
            if ($savePath === "") {
                Log::error("不正な画像がuploadされました");
                // die("不正な画像がuploadされました");
                return;
            }
            $thumbnail = $this->NekoUtil->s3Upload($savePath, '');
            // 書きだした画像を削除
            @unlink($savePath);
        }
        
        // 書きだした画像を削除
        @unlink($file);

        if ($result) {
            
            $catImage = $this->Cats->CatImages->newEntity();
            $catImage->url = $result['ObjectURL'];
            if($thumbnailing){
                $catImage->thumbnail = $thumbnail['ObjectURL'];
            }else{
                $catImage->thumbnail = $result['ObjectURL'];
            }
            $catImage->users_id = $uid;
            $catImage->cats_id = $cat_id;
            if ($this->Cats->CatImages->save($catImage)) {
                // $this->Flash->success('画像を保存しました。');
                return $catImage;
            }
        }
    }
    
    public function saveCatMovie($file, $cat_id, $uid=null, $thumbnailing = true){
        return $this->saveImageOnAWS($file, $cat_id, $uid, false);
    }
    
    public function saveCatImage($file, $cat_id, $uid=null, $thumbnailing = true){
        
        $savePath = $this->NekoUtil->safeImage($file, TMP);
        if ($savePath === "") {
            Log::error("不正な動画がuploadされました");
            // die("不正な画像がuploadされました");
        }
        return $this->saveImageOnAWS($savePath, $cat_id, $uid, true);
    }
    
    public function addTag($value){
        $this->Cats = TableRegistry::get('Cats');
        
        $tag = $this->Cats->Tags->find('all')->where(['tag =' => $value])->first();
        if($tag == null){
            $tag = $this->Cats->Tags->newEntity($tag);
            $tag->tag = $value;
            if($this->Cats->Tags->save($tag)){
            }
        }
        return $tag;
    }
    
    public function addComment($comment, $cat_id, $uid){
        
        $this->Cats = TableRegistry::get('Cats');
        // $this->Comments = TableRegistry::get("Comments");
        // $this->Tags = TableRegistry::get("Tags");
         
        //ハッシュタグを処理
        
        $hash = '#＃';
        $tag = 'A-Za-z〃々ぁ-ゖ゛-ゞァ-ヺーヽヾ一-龥Ａ-Ｚａ-ｚｦ-ﾟ';
        // $tag = 'a-zÀ-ÖØ-öø-ÿĀ-ɏɓ-ɔɖ-ɗəɛɣɨɯɲʉʋʻ̀-ͯḀ-ỿЀ-ӿԀ-ԧⷠ-ⷿꙀ-֑ꚟ-ֿׁ-ׂׄ-ׇׅא-תװ-״﬒-ﬨשׁ-זּטּ-לּמּנּ-סּףּ-פּצּ-ﭏؐ-ؚؠ-ٟٮ-ۓە-ۜ۞-۪ۨ-ۯۺ-ۼۿݐ-ݿࢠࢢ-ࢬࣤ-ࣾﭐ-ﮱﯓ-ﴽﵐ-ﶏﶒ-ﷇﷰ-ﷻﹰ-ﹴﹶ-ﻼ‌ก-ฺเ-๎ᄀ-ᇿ㄰-ㆅꥠ-꥿가-힯ힰ-퟿ﾡ-ￜァ-ヺー-ヾｦ-ﾟｰＡ-Ｚａ-ｚぁ-ゖ゙-ゞ㐀-䶿一-鿿꜀-뜿띀-렟-﨟〃々〻'; // 全言語対応
        $digit = '0-9０-９';
        $underscore = '_';
        
        $pattern = "/(?:^|[^ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z0-9&_\/]+)"
        ."[#＃]("
        ."[ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z0-9_]*"
        ."[ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z]+"
        ."[ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z0-9_]*"
        .")/u";
        
        preg_match_all($pattern, $comment, $matches, PREG_PATTERN_ORDER);
        
        $tags = [];
        foreach($matches[1] as $value){
            $tag = $this->addTag($value);
            if(!is_null($tag)){
                $tags[] = $tag;
            }
        }
        
        $commentDO = $this->Cats->Comments->newEntity([
            'associated' => ['Tags']
        ]);
        
        $commentDO->comment = $comment;
        $commentDO->cats_id = $cat_id;
        $commentDO->users_id = $uid;
        $commentDO->tags = $tags;
        
        if ($this->Cats->Comments->save($commentDO)) {
            // $this->Flash->success('コメントを保存しました。');
            
            $cat = $this->Cats->get($cat_id,[
                'contain' => ['Tags']
            ]);
            
            //タグの追加
            $this->Cats->Tags->link($cat, $tags);
            if($this->Cats->save($cat, ['associated' => ['Tags']])){
                //
            }
            
            //通知処理
            if(!$this->isCurrentUser($cat->users_id)){
                $u = $this->currentUser();
                
                $this->NotificationManager->notify($cat->users_id, 
                    'あなたの猫ちゃんに新しい「コメント」がありました！', 
                    "@".$u->username."さんが「コメント」してくれました！", 
                    Router::url(["controller" => "Cats","action" => "view", $cat_id])
                );
                
                $users_ids = $this->Cats->Comments->find()
                    ->select(['users_id'])
                    ->where(['cats_id = ' => $cat_id])
                    ->group('users_id')
                    ->having(['users_id !=' => 0, 'users_id !=' => $u->id]);
                    
                foreach($users_ids as $users_id){
                    $this->NotificationManager->notify($users_id->users_id, 
                        'あなたがコメントした猫ちゃんに新しい「コメント」がありました！', 
                        "@".$u->username."さんが「コメント」しました！", 
                        Router::url(["controller" => "Cats","action" => "view", $cat_id])
                    );
                }
                
                $users_ids = $this->Cats->Favorites->find()
                    ->select(['users_id'])
                    ->where(['cats_id = ' => $cat_id])
                    ->group('users_id')
                    ->having(['users_id !=' => 0, 'users_id !=' => $u->id]);
                    
                foreach($users_ids as $users_id){
                    $this->NotificationManager->notify($users_id->users_id, 
                        'あなたが「いいね」した猫ちゃんに新しい「コメント」がありました！', 
                        "@".$u->username."さんが「コメント」しました！", 
                        Router::url(["controller" => "Cats","action" => "view", $cat_id])
                    );
                }
                
            }
        }
    }
    
    public function listCats($users_id = null, $order = null, $show_hidden = false){
        $this->Cats = TableRegistry::get('Cats');
        
        // $this->Cookie->write("Order.Preference", $order);
        if($order == null)
            $order = "recent";
        
        if($order === "popular"){
            $query = $this->Cats->find('all');
            $query ->leftJoinWith('Favorites')
                ->select($this->Cats)
                ->select($this->Cats->Users)
                ->select(['count' => $query->func()->count('Favorites.id')])
                ->contain([
                    'CatImages', 'Users', 'Favorites', 'Comments.Reports',
                    'Comments'=> function ($q) {
                        return $q
                            ->leftJoinWith('Reports')
                            ->select($this->Cats->Comments)
                            ->select(['report_counts' => $q->func()->count('Reports.id')])
                            ->group('Comments.id')
                            ->having(['report_counts <' => 1])
                            ->order(['Comments.created' => 'DESC']);
                    },
                    'Answers'=> function ($q) {
                       return $q
                            ->where([
                                'Questions.name =' => 'name'
                            ])
                            ->contain(['Questions']);
                }]);
                if(!$show_hidden){
                    $query->where([
                        'Cats.hidden =' => 0
                    ]);
                }
                $query->group('Cats.id')
                ->order(['count' => 'DESC'])
                ;
                
        } else if($order === "commented") {
            $query = $this->Cats->find('all');
            $query 
                // ->leftJoinWith('Favorites')
                ->leftJoinWith('Comments')
                ->select($this->Cats)
                ->select($this->Cats->Users)
                // ->select(['count' => $query->func()->count('Favorites.id')])
                ->select(['last' => $query->func()->max('Comments.created')])
                ->contain(['CatImages', 'Comments.Reports',
                'Comments'=> function ($q) {
                    return $q
                            ->leftJoinWith('Reports')
                            ->select($this->Cats->Comments)
                            ->select(['report_counts' => $q->func()->count('Reports.id')])
                            ->group('Comments.id')
                            ->having(['report_counts <' => 1])
                            ->order(['Comments.created' => 'DESC']);
                }, 
                'Users', 'Favorites',
                'Answers'=> function ($q) {
                   return $q
                        ->where([
                            'Questions.name =' => 'name'
                        ])
                        ->contain(['Questions']);
                }]);
                if(!$show_hidden){
                    $query->where([
                        'Cats.hidden =' => 0
                    ]);
                }
                $query->group('Cats.id')
                ->order(['last' => 'DESC'])
                ;
                
                // debug($query->toArray());
        } else if($order === "recent")  { 
            $query = $this->Cats->find('all')
                ->contain(['CatImages', 'Comments.Reports',
                'Comments'=> function ($q) {
                    return $q
                            ->leftJoinWith('Reports')
                            ->select($this->Cats->Comments)
                            ->select(['report_counts' => $q->func()->count('Reports.id')])
                            ->group('Comments.id')
                            ->having(['report_counts <' => 1])
                            ->order(['Comments.created' => 'DESC']);
                }, 
                'Users', 'Favorites', 
                'Answers'=> function ($q) {
                   return $q
                        ->where([
                            'Questions.name =' => 'name'
                        ])
                        ->contain(['Questions']);
                }]);
                if(!$show_hidden){
                    $query->where([
                        'Cats.hidden =' => 0
                    ]);
                }
                $query->group('Cats.id')
                ->order(['Cats.created' => 'DESC']);
                
        }
            
        if(!is_null($users_id)){ 
            $query = $query ->where([
                'Users.id =' => $users_id
            ]);
        }
        
        return $query;
    }
    
     public function listCatsByTag($tag){
        $this->Cats = TableRegistry::get('Cats');
        
        $this->Tags = TableRegistry::get('Tags');
            
        $data = $this->Cats->find('all')
        ->contain(['CatImages', 'Comments.Reports',
        'Comments'=> function ($q) {
            return $q->order(['Comments.created' => 'DESC']);
        },  
        'Users', 'Favorites',
        'Answers'=> function ($q) {
          return $q
                ->where([
                    'Questions.name =' => 'name'
                ])
                ->contain(['Questions']);
        }])
        ->where([
            'Cats.hidden =' => 0
        ])
        ->matching('Tags', function ($q) use ($tag) {
            return $q->where(['Tags.tag =' => $tag]);
        })
        ->group('Cats.id')
        ->order(['Cats.created' => 'DESC']);
        
        return $data;
    }
    
     public function currentUser(){
        $uid = $this->Controller->Auth->user()['id'];
        if(is_null($uid))
            return null;
            
        $this->Users = TableRegistry::get('Users');
        $user = $this->Users->get($uid);
        return $user;
    }
    
    public function isCurrentUser($users_id){
        $uid = $this->Controller->Auth->user()['id'];
        if(is_null($uid))
            return false;
        
        return $uid == $users_id;
    }
}