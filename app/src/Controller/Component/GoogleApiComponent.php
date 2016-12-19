<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Aws\S3\S3Client;

define('APPLICATION_NAME', 'Drive API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/auth_token.json');
define('REFRESH_TOKEN_PATH', '~/.credentials/refresh_token.json');

/**
 * You need to get a credeintial file from Google Dev Console
 */
define('CLIENT_SECRET_PATH', '~/.credentials/client_secret.json');

class GoogleApiComponent extends Component {
   
    public $components = array('NekoUtil');
   
     /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient() {
      
        $client = new \Google_Client();
        $client->setApplicationName(APPLICATION_NAME);
        $client->setAuthConfig($this->expandHomeDirectory(CLIENT_SECRET_PATH));
        
        $client->addScope(\Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->addScope(\Google_Service_Drive::DRIVE_READONLY);
        $client->addScope(\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);
        
        if(isset($_SERVER['HTTP_HOST'])){
            $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/tests/oauth2callback');
        }
        $client->setApprovalPrompt("force");
        $client->setAccessType("offline");
        
        if(isset($_GET['code'])){
            return $client;
        }
        
         // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory($this->expandHomeDirectory(CREDENTIALS_PATH));
        
        // print_r($credentialsPath);
        // exit;
        
        if (file_exists($credentialsPath)) {
            $accessToken = file_get_contents($credentialsPath);
        } else {
            // Request authorization from the user.
            $auth_url = $client->createAuthUrl();
            return $this->_registry->getController()->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
        }
        $client->setAccessToken($accessToken);
        
        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $refreshTokenPath = $this->expandHomeDirectory($this->expandHomeDirectory(REFRESH_TOKEN_PATH));
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

        $credentialsPath = $this->expandHomeDirectory(CREDENTIALS_PATH);
        $refreshTokenPath = $this->expandHomeDirectory(REFRESH_TOKEN_PATH);
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
    
        
        // file_put_contents("gs://$gcp_bucket/".basename($file), $file);
        // AIzaSyBoBnKV2kj2wPfsDuBnBtjj1RGT9po11ng
    }

    // function s3Upload($file, $s3Dir) {
    //     $ext = $this->extension($file);
    //     $srcPath = $file;

    //     $timestamp = uniqid();
    //     $name = $timestamp . "_file." . $ext;
        
    //     $s3 = new S3Client([
    //         'version'     => getenv('AWS_BUCKET_VERSION'),
    //         'region'      => getenv('AWS_BUCKET_REGION'),
    //         'credentials' => [
    //             'key'    => getenv('AWS_BUCKET_KEY'),
    //             'secret' => getenv('AWS_BUCKET_SECRET')
    //         ]
    //     ]);

    //     try {
    //         // Upload a file.
    //         $result = $s3->putObject(array(
    //             'Bucket'       => getenv('AWS_BUCKET_NAME'),
    //             'Key'        => $name,
    //             'SourceFile' => $srcPath,
    //             'ACL'          => 'public-read',
    //         ));

    //         return $result;
    //     }catch (\RuntimeException $e){
    //         throw $e;
    //     }

    // }
    
    // /**
    //  * Expands the home directory alias '~' to the full path.
    //  * @param string $path the path to expand.
    //  * @return string the expanded path.
    //  */
    // function expandHomeDirectory($path) {
    //   $homeDirectory = getenv('HOME');
    //   if (empty($homeDirectory)) {
    //     $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
    //   }
    //   return str_replace('~', realpath($homeDirectory), $path);
    // }
   
    // /**
    //  * 一度画像を別の形式に変換し、jpg形式に変換する
    //  * TODO: @utsumi-k PHP Parserを使うかは場合は別途用意する
    //  * @param $orgFilePath
    //  * @param $exportFilePath
    //  *
    //  * @return string
    //  */
    // function safeImage($orgFilePath, $exportFilePath) {
    //     // 書き出しファイル名を生成
    //     $outputFilePath = $exportFilePath . "/" . $this->generateUniqueFileName();

    //     // 元画像情報を取得
    //     $size = getimagesize($orgFilePath);
    //     if ($size === false) {
    //         // 画像として認識できなかった
    //         return "";
    //     }
    //     list($w, $h, $type) = $size;
    //     list($width, $height) = $this->getSaveFileSize($w, $h);

    //     // 1回最初にリサイズする
    //     $res = new \Imagick($orgFilePath);
    //     if (!$res->thumbnailImage($width, $height, true, true)) {
    //         // リサイズ失敗
    //         return "";
    //     }

    //     if ($type == IMG_JPEG) {
    //         // 一度pngにする
    //         if (!$res->setImageFormat('png')) {
    //             // 1回PNGに出来なかった
    //             return "";
    //         }
    //     }
    //     // 問題なかったのでjpgにしましょう。
    //     if (!$res->setImageFormat("jpg")) {
    //         // JPGへの変換失敗
    //         return "";
    //     }
    //     if (!$res->writeImage($outputFilePath)) {
    //         // 書き込み失敗
    //         return "";
    //     }

    //     return $outputFilePath;
    // }

    // /**
    //  * Uniqueなファイル名を生成する
    //  * TODO: 絶対かぶらない保証もないし、非同期で保存されるけど、今んとこそこは危惧するレベルではないと判断
    //  * @return string
    //  */
    // function generateUniqueFileName() {
    //     return md5(uniqid(rand(),1)) . ".jpg";
    // }


    // /**
    //  * 保存するサイズを計算する
    //  * 960*540 までのサイズにする
    //  * @param $orgWidth
    //  * @param $orgHeight
    //  *
    //  * @return array
    //  */
    // function getSaveFileSize($orgWidth, $orgHeight) {
    //     $maxWidth = 960;
    //     $maxHeight = 540;
    //     $w = $orgWidth;
    //     $h = $orgHeight;

    //     if ($orgWidth > $maxWidth || $orgHeight > $maxHeight) {
    //         // リサイズ必要
    //         if ($orgWidth > $orgHeight) {
    //             // 横長
    //             $rate = $maxWidth / $orgWidth;

    //         } elseif ($orgHeight > $orgWidth) {
    //             // 縦長
    //             $rate = $maxHeight / $orgHeight;
    //         } else {
    //             // 正方形
    //             $rate = $maxHeight / $orgHeight;
    //         }
    //         $w = (int)($orgWidth * $rate);
    //         $h = (int)($orgHeight * $rate);
    //     }
    //     return [$w,$h];
    // }
}