<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Aws\S3\S3Client;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

define('APPLICATION_NAME', 'Drive API PHP Quickstart');
define('CREDENTIAL_DIR', '~/.credentials/');
define('CREDENTIALS_PATH', CREDENTIAL_DIR.'auth_token.json');
define('REFRESH_TOKEN_PATH', CREDENTIAL_DIR.'refresh_token.json');
define('TMP_DIR', '~/workspace/app/tmp/');

/**
 * You need to get a credeintial file from Google Dev Console
 */
define('CLIENT_SECRET_PATH', '~/.credentials/client_secret.json');

class GoogleApiComponent extends Component {
   
    public $components = ['NotificationManager', 'NekoUtil', 'CatsCommon'];
    
    public function initialize(array $config) {
        if(!file_exists($this->NekoUtil->expandHomeDirectory(CREDENTIAL_DIR))){
            mkdir($this->NekoUtil->expandHomeDirectory(CREDENTIAL_DIR), 0755, true);
        }
        if(!file_exists($this->NekoUtil->expandHomeDirectory(TMP_DIR))){
            mkdir($this->NekoUtil->expandHomeDirectory(TMP_DIR), 0755, true);
        }
    }
   
     /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient() {
      
        $client = new \Google_Client();
        $client->setApplicationName(APPLICATION_NAME);
        $client->setAuthConfig($this->NekoUtil->expandHomeDirectory(CLIENT_SECRET_PATH));
        
        $client->addScope(\Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->addScope(\Google_Service_Drive::DRIVE_READONLY);
        $client->addScope(\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
        
        if(isset($_SERVER['HTTP_HOST'])){
            $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/admin/tests/oauth2callback');
        }
        $client->setApprovalPrompt("force");
        $client->setAccessType("offline");
        
        if(isset($_GET['code'])){
            return $client;
        }
        
         // Load previously authorized credentials from a file.
        $credentialsPath = $this->NekoUtil->expandHomeDirectory(CREDENTIALS_PATH);
        
        if (file_exists($credentialsPath)) {
            $accessToken = file_get_contents($credentialsPath);
        } else {
            // Request authorization from the user.
            $auth_url = $client->createAuthUrl();
            debug($auth_url);
            return $this->_registry->getController()->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
        }
        $client->setAccessToken($accessToken);
        
        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $refreshTokenPath = $this->NekoUtil->expandHomeDirectory(REFRESH_TOKEN_PATH);
            $refreshToken = file_get_contents($refreshTokenPath);
            $client->refreshToken($refreshToken);
            file_put_contents($credentialsPath, $client->getAccessToken());
        }
        
        return $client;
    }
    
    function googleConnect(){
        $this->getClient();
        return $this->_registry->getController()->redirect('/');
    }
    
    function googleRevoke(){
        $this->getClient()->revoke();
    }
    
    function oauth2callback(){
        $client = $this->getClient();
        $client->authenticate($_GET['code']);
        
        // $client->setIncludeGrantedScopes(true);
         // Exchange authorization code for an access token.
        $accessToken = $client->getAccessToken()['access_token'];
        $refreshToken = $client->getRefreshToken();
        
        // $google_token= json_decode($accessToken);
        // $refreshToken  = $google_token->refresh_token;

        $credentialsPath = $this->NekoUtil->expandHomeDirectory(CREDENTIALS_PATH);
        $refreshTokenPath = $this->NekoUtil->expandHomeDirectory(REFRESH_TOKEN_PATH);
        // Store the credentials to disk.
        if(!file_exists(dirname($credentialsPath))) {
          mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, $accessToken);
        file_put_contents($refreshTokenPath, $refreshToken);
        debug("Credentials saved to ". $credentialsPath);
        
        return $this->_registry->getController()->redirect('/');
    }
    
    function detectObjects($image_path){
        $api_key = env("GOOGLE_API_KEY") ;
        
    	// リクエスト用のJSONを作成
    	$json = json_encode( array(
    		"requests" => array(
    			array(
    				"image" => array(
    					"content" => base64_encode( file_get_contents( $image_path ) ) ,
    				) ,
    				"features" => array(
    					array(
    						"type" => "LABEL_DETECTION" ,
    						"maxResults" => 10 ,
    					) 
    				) ,
    			) ,
    		) ,
    	) ) ;

    	// リクエストを実行
    	$curl = curl_init() ;
    	curl_setopt( $curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . $api_key ) ;
    	curl_setopt( $curl, CURLOPT_HEADER, true ) ; 
    	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" ) ;
    	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" ) ) ;
    	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;
    	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
    	if( isset($referer) && !empty($referer) ) curl_setopt( $curl, CURLOPT_REFERER, $referer ) ;
    	curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;
    	curl_setopt( $curl, CURLOPT_POSTFIELDS, $json ) ;
    	$res1 = curl_exec( $curl ) ;
    	$res2 = curl_getinfo( $curl ) ;
    	curl_close( $curl ) ;
    
    	// 取得したデータ
    	$json = substr( $res1, $res2["header_size"] ) ;				// 取得したJSON
    	$header = substr( $res1, 0, $res2["header_size"] ) ;		// レスポンスヘッダー
    
    // 	// 出力
    // 	echo "<h2>JSON</h2>" ;
    // 	echo $json ;
    
    // 	echo "<h2>ヘッダー</h2>" ;
    // 	echo $header ;
    	
    	return $json;
    }
    
    function num2alpha($n)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return $r;
    }
    
    function inputTNRDataFromGoogleDrive($spreadsheetId, $photoFolder, $maxnum_at_once = 1) {
        
        $this->Cats = TableRegistry::get('Cats');
        $this->CatImages = TableRegistry::get('CatImages');
        
        $counter = 0;
        
        $data = $this->readDataFromTNRSpreadsheet($spreadsheetId);
        foreach($data as $d){
            if($counter >= $maxnum_at_once){
                break;
            }
            
            $d['number'] = sprintf('%04d', $d['number']);
            $cuid = str_replace("/","_",$d['date'])."_".$d['number'];
            $d['cuid'] = $cuid;
            
            $cat = $this->Cats->Notes->find('all')->where(['value' => $cuid])->first();
            if($cat != null){
                continue;
            }
            $counter++;
            
            debug($cuid);
            $cat = $this->Cats->newEntity();
            $cat->address = $d['address'];
            $cat->flg = 10; //TNR記録
            $cat->hidden = true;
            
            //ネコの保存
            if($this->Cats->save($cat)){
                
                //タグの追加
                $tags = [];
                $tag = $this->CatsCommon->addTag("TNR");
                $tags[] = $tag;
                $this->Cats->Tags->link($cat, $tags);
                if($this->Cats->save($cat, ['associated' => ['Tags']])){
                }
                
                //追加情報の保存
                foreach($d as $key => $value){
                    if(strlen($value) > 0){
                        $note = $this->Cats->Notes->newEntity();
                        $note->cat_id = $cat->id;
                        $note->name = $key;
                        $note->value = $value;
                        if($this->Cats->Notes->save($note)){
                            
                        }
                    }
                }
                
                //写真の取得と保存
                $client = $this->getClient();
                $this->Drive = new \Google_Service_Drive($client);
                
                try {
                    $parameters = [
                        'q' => " '{$photoFolder}' in parents and name = '".$d['number'] ."'", 
                        'fields' => 'files',
                        'pageSize' => '1'];
                    
                    $list = $this->Drive->files->listFiles($parameters);
                    $folderId = $list->getFiles()[0]['id'];
                    
                    $parameters = [
                        'q' => " '{$folderId}' in parents", 
                        'fields' => 'files',
                        'pageSize' => '100'];
                    $photos = $this->Drive->files->listFiles($parameters);
                    
                    foreach($photos->getFiles() as $photo){
                        
                        $content = $this->Drive->files->get($photo['id'], array('alt' => 'media'));
                        $path = $this->NekoUtil->expandHomeDirectory(TMP_DIR.$photo->getName());
                        file_put_contents($path, $content->getBody());
                        $this->CatsCommon->saveCatImage($path, $cat->id);
                        @unlink($path);
                    }
                }      
                catch (Exception $e)
                {
                	print $e->getMessage();
                	exit;
                }
            }
        }
        
        debug("Done");
    }
    
    
    function gcpUpload($file_path, $file_name, $gcp_bucket) {
        
        $client = $this->getClient();
        
        $storage = new \Google_Service_Storage($client);
        
        /***
         * Write file to Google Storage
         */
        try {
        	$postbody = array( 
        			'name' => $file_name, 
        			'data' => file_get_contents($file_path),
        			'uploadType' => "media"
        			);
        	$gsso = new \Google_Service_Storage_StorageObject();
        	$gsso->setName( $file_name );
        	
        	$result = $storage->objects->insert( $gcp_bucket, $gsso, $postbody );
        	debug($result);
        	$id = $result['id'];
        	debug($id);
        	exit;
        	
        }      
        catch (Exception $e)
        {
        	print $e->getMessage();
        	exit;
        }
    
        
    }

    
    function readDataFromTNRSpreadsheet($spreadsheetId) {
        
        $client = $this->getClient();
        $this->Sheets = new \Google_Service_Sheets($client);
        
            
        $spreadsheet = $this->Sheets->spreadsheets->get($spreadsheetId);
        $sheets = $spreadsheet->getSheets();
        
        {
            $sheet = $sheets[0]; //カゴタグ
            $title = $sheet->getProperties()->title;
            // debug($sheet->getProperties()->getGridProperties());
            $columnCount = $sheet->getProperties()->getGridProperties()->columnCount;
            $frozenRowCount = $sheet->getProperties()->getGridProperties()->frozenRowCount;
            $rowCount = $sheet->getProperties()->getGridProperties()->rowCount;
            
            $range = $title.'!A'.'1'.':'.$this->num2alpha($columnCount).$rowCount;
            $response = $this->Sheets->spreadsheets_values->get($spreadsheetId, $range);
            $kagoTagValues = $response->getValues();
        }
        
        {
            $sheet = $sheets[1]; //ネコタグ
            $title = $sheet->getProperties()->title;
            // debug($sheet->getProperties()->getGridProperties());
            $columnCount = $sheet->getProperties()->getGridProperties()->columnCount;
            $frozenRowCount = $sheet->getProperties()->getGridProperties()->frozenRowCount;
            $rowCount = $sheet->getProperties()->getGridProperties()->rowCount;
            
            $range = $title.'!A'.'1'.':'.$this->num2alpha($columnCount).$rowCount;
            $response = $this->Sheets->spreadsheets_values->get($spreadsheetId, $range);
            $nekoTagValues = $response->getValues();
        }
        
        {
            $sheet = $sheets[2]; //同意書
            $title = $sheet->getProperties()->title;
            // debug($sheet->getProperties()->getGridProperties());
            $columnCount = $sheet->getProperties()->getGridProperties()->columnCount;
            $frozenRowCount = $sheet->getProperties()->getGridProperties()->frozenRowCount;
            $rowCount = $sheet->getProperties()->getGridProperties()->rowCount;
            
            $range = $title.'!A'.'1'.':'.$this->num2alpha($columnCount).$rowCount;
            $response = $this->Sheets->spreadsheets_values->get($spreadsheetId, $range);
            $doishoValues = $response->getValues();
            
            $doishoValues[0][0] = 'number';
            
           
            for($i=2; $i<count($doishoValues); $i++){
                if(count($doishoValues[$i])<=0){
                    continue;
                }
                $nums = split(',', $doishoValues[$i][0]);
                foreach($nums as $num){
                    if(intval($num) > 0){
                        $doishoList[intval($num)] = array_slice($doishoValues[$i], 1);
                        array_splice($doishoList[intval($num)], 0, 0, intval($num));
                    }
                }
            }
        }
        
        //カゴタグ
        $data = [];
        for($i=2; $i<count($kagoTagValues); $i++){
            $row = [];
            
            for($j=0; $j<count($kagoTagValues[0]); $j++){
                if($j >= count($kagoTagValues[$i])){
                    $row[$kagoTagValues[0][$j]] = "";
                }else{
                    $row[$kagoTagValues[0][$j]] = $kagoTagValues[$i][$j];
                }
            }
            $data[$row['number']] = $row;
        }
        
        //ネコタグ
        for($i=2; $i<count($nekoTagValues); $i++){
            $row = [];
            
            for($j=0; $j<count($nekoTagValues[0]); $j++){
                if($j >= count($nekoTagValues[$i])){
                    $row[$nekoTagValues[0][$j]] = "";
                }else{
                    $row[$nekoTagValues[0][$j]] = $nekoTagValues[$i][$j];
                }
            }
            if(!array_key_exists($row['number'], $data)){
                debug("ERROR:Missing Nekotag:".$row['number']);
                $data[$row['number']] = [];
                $data[$row['number']]['number'] = $row['number'];
                $data[$row['number']]['remarks'] = "";
            }
            
            $remarks = $data[$row['number']]['remarks'].",".$row['remarks'];
            foreach ($row as $key => $value){
                if(array_key_exists($key, $data[$row['number']])){
                    if(strlen(trim($data[$row['number']][$key])) <= 0){
                        $data[$row['number']][$key] = $value;    
                    }
                }else{
                    $data[$row['number']][$key] = $value;
                }
            }
            $data[$row['number']]['remarks'] = $remarks;
        }
    
        //同意書
        foreach($doishoList as $doisho) {
            $row = [];
            
            for($j=0; $j<count($doishoValues[0]); $j++){
                 if($j >= count($doisho)){
                    $row[$doishoValues[0][$j]] = "";
                }else{
                    $row[$doishoValues[0][$j]] = $doisho[$j];
                }
            }
            if(!array_key_exists($row['number'], $data)){
                debug("ERROR:Missing Kagotag and Nekotag:".$row['number']);
                $data[$row['number']] = [];
                $data[$row['number']]['number'] = $row['number'];
                $data[$row['number']]['remarks'] = "";
            }
            
            $remarks = $data[$row['number']]['remarks'].",".$row['remarks'];
            foreach ($row as $key => $value){
                if(array_key_exists($key, $data[$row['number']])){
                    if(strlen(trim($data[$row['number']][$key])) <= 0){
                        $data[$row['number']][$key] = $value;    
                    }
                }else{
                    $data[$row['number']][$key] = $value;
                }
            }
            $data[$row['number']]['remarks'] = $remarks;
            
        }
        
        // debug($data);
        // // debug(count($data));
        // exit;
        return $data;
    }
}