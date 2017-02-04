<?php
namespace App\Controller\Admin;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class FunctionsController extends AppController
{
    public $components = array('NekoUtil', 'GoogleApi');
  
    public function index(){
        
    }
    
    public function connectGoogle(){
         if ($this->request->is('post')) {
             
            $post = $this->request->data;
            $secret = $post['secret'];
            
            $this->GoogleApi->connectGoogle($secret);
         }
    }
    
     // 1Fe51bs5nsHACjgLtkl6PI5sXKanfuwA9dZ-DWkZhyCI 0By-brejWDbtQLWNqRUxVYlVPRzA 100
    public function getTnrData(){
         if ($this->request->is('post')) {
             
            $post = $this->request->data;
            
            $sheetId = $post['sheetId'];
            $folderId = $post['folderId'];
            $num = $post['num'];
             
            $memoryLimit = ini_get('memory_limit');
            ini_set('memory_limit', '512M');

            $this->GoogleApi->inputTNRDataFromGoogleDrive($sheetId,$folderId,$num);
            
            ini_set('memory_limit', $memoryLimit);
            
            exit;
             
        }
    }
}
