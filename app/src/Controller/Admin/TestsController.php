<?php
namespace App\Controller\Admin;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class TestsController extends AppController
{
    public $components = array('NekoUtil', 'GoogleApi');
    
    public function ocrTest(){
        
    }
    
    public function postShelteredTest(){
        
    }
    
    public function setVisibilityTest(){
        
    }
    
    public function videoUpload(){
    }
    
    /***
     * Vision APIで猫が写っているか確認するサンプル
     * 
     */
    public function visionTest(){
         if ($this->request->is('post')) {
             
            $data = $this->request->data;
            $err = "";

            $time = time();
            
            if ($err === "") {
                $image_url = "";
                
                if (isset($data["image"]) /*&& $data["image"].length > 0*/ && is_uploaded_file($data["image"]["tmp_name"])) {
                    // アップロード処理
                    $file = $data["image"];
                    
                    $savePath = $this->NekoUtil->safeImage($file["tmp_name"], TMP);
                    if ($savePath === "") {
                        die("不正な画像がuploadされました");
                    }
                    
                    $json = $this->GoogleApi->detectObjects($savePath);
                    $this->set(compact('json', 'json'));
                    
                    //GCPにアップが必要かと思ったがいらなかった
                    // $this->GoogleApi->gcpUpload($savePath, basename($savePath), "nekoderu-vision");
                }
            }
            
            // return $this->redirect('/');
        }
    }
    
    public function googleConnect(){
        $this->GoogleApi->googleConnect();
    }
    
     public function oauth2callback(){
        $this->GoogleApi->oauth2callback();
    }
    
    public function mail(){
        $email = new Email();
        $res = $email->template('default')
            ->emailFormat('html')
            ->to('stagesp1@gmail.com')
            ->from('izumi@cis.sojo-u.ac.jp')
            ->subject('Title')
            ->send('This is a test mail');
        
        print_r($res);
        exit;
            
        return null;
    }
    
    // public function beforeFilter(Event $event)
    // {
    //     $this->Auth->allow();
    // }
}
