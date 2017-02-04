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
    
     // 1Fe51bs5nsHACjgLtkl6PI5sXKanfuwA9dZ-DWkZhyCI 0By-brejWDbtQLWNqRUxVYlVPRzA 100
    public function getTnrData(){
         if ($this->request->is('post')) {
             
            $post = $this->request->data;
            
            $sheetId = $post['sheetId'];
            $folderId = $post['folderId'];
            $num = $post['num'];
             
            $this->GoogleApi->inputTNRDataFromGoogleDrive($sheetId,$folderId,$num);
            exit;
             
        }
    }
}
