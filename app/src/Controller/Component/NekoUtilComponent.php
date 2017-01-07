<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Aws\S3\S3Client;

class NekoUtilComponent extends Component {
   
    /**
     *
     * CurlでAPIを叩きます。
     * @param            $method
     * @param            $url
     * @param bool|false $data
     *
     * @return mixed
     */
    function callApi($method, $url, $data = false){
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);
    }

    // 拡張子を取得する
    function extension($filename) {
        $str = strrchr($filename, '.');
        if ($str === FALSE) {
            return NULL;
        } else {
            return substr($str, 1);
        }
    }
    
    function s3Upload($file, $s3Dir) {
        $ext = $this->extension($file);
        $srcPath = $file;

        $timestamp = uniqid();
        $name = $timestamp . "_file." . $ext;
        
        $s3 = new S3Client([
            'version'     => getenv('AWS_BUCKET_VERSION'),
            'region'      => getenv('AWS_BUCKET_REGION'),
            'credentials' => [
                'key'    => getenv('AWS_BUCKET_KEY'),
                'secret' => getenv('AWS_BUCKET_SECRET')
            ]
        ]);

        try {
            // Upload a file.
            $result = $s3->putObject(array(
                'Bucket'       => getenv('AWS_BUCKET_NAME'),
                'Key'        => $name,
                'SourceFile' => $srcPath,
                'ACL'          => 'public-read',
            ));

            return $result;
        }catch (\RuntimeException $e){
            throw $e;
        }

    }
    
    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    function expandHomeDirectory($path) {
      $homeDirectory = getenv('HOME');
      if (empty($homeDirectory)) {
        $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
      }
      return str_replace('~', realpath($homeDirectory), $path);
    }
    
    /**
     * 画像を回転する
     * @param $orgFilePath
     * @param $exportFilePath
     * @param $ang
     *
     * @return string
     */
    function rotateImage($orgFilePath, $exportFilePath, $ang) {
        // 書き出しファイル名を生成
        $outputFilePath = $exportFilePath . "/" . $this->generateUniqueFileName();

        // 元画像情報を取得
        $size = getimagesize($orgFilePath);
        if ($size === false) {
            // 画像として認識できなかった
            return "";
        }
        list($w, $h, $type) = $size;
        list($width, $height) = $this->getSaveFileSize($w, $h);

        // 1回最初にリサイズする
        $res = new \Imagick($orgFilePath);
        $res->rotateImage(new \ImagickPixel(), $ang); 
        if (!$res->writeImage($outputFilePath)) {
            // 書き込み失敗
            return "";
        }

        return $outputFilePath;
    }
    
    /**
     * 動画をGifにして保存する
     * TODO: @utsumi-k PHP Parserを使うかは場合は別途用意する
     * @param $orgFilePath
     * @param $exportFilePath
     *
     * @return string
     */
    function saveGif($orgFilePath, $exportFilePath) {
        // 書き出しファイル名を生成
        $outputFilePath = $exportFilePath . "/" . $this->generateUniqueFileName(".gif");

        
        // $thumb_stdout;
        // $errors;
        // $retval = 0;

        // Delete the file if it already exists
        if (file_exists($outputFilePath)) { 
            unlink($outputFilePath); 
        }
        // $cmd  = "ffmpeg -i $in -ar 22050 -acodec libmp3lame -ab 32K -r 25 -s 320x240 -vcodec gif $outputFilePath";
        $cmd  = "ffmpeg -i $orgFilePath -vf 'scale=480:-1' $outputFilePath";
        
        exec(escapeshellcmd($cmd));
        unlink($orgFilePath);
        
        return $outputFilePath;

    }
   
    /**
     * 一度画像を別の形式に変換し、jpg形式に変換する
     * TODO: @utsumi-k PHP Parserを使うかは場合は別途用意する
     * @param $orgFilePath
     * @param $exportFilePath
     *
     * @return string
     */
    function safeImage($orgFilePath, $exportFilePath) {
        // 書き出しファイル名を生成
        $outputFilePath = $exportFilePath . "/" . $this->generateUniqueFileName();

        // 元画像情報を取得
        $size = getimagesize($orgFilePath);
        if ($size === false) {
            // 画像として認識できなかった
            return "";
        }
        list($w, $h, $type) = $size;
        list($width, $height) = $this->getSaveFileSize($w, $h);

        // 1回最初にリサイズする
        $res = new \Imagick($orgFilePath);
        if (!$res->thumbnailImage($width, $height, true, true)) {
            // リサイズ失敗
            return "";
        }

        if ($type == IMG_JPEG) {
            // 一度pngにする
            if (!$res->setImageFormat('png')) {
                // 1回PNGに出来なかった
                return "";
            }
        }
        // 問題なかったのでjpgにしましょう。
        if (!$res->setImageFormat("jpg")) {
            // JPGへの変換失敗
            return "";
        }
        if (!$res->writeImage($outputFilePath)) {
            // 書き込み失敗
            return "";
        }

        return $outputFilePath;
    }
    
    /**
     * 画像を別の形式に変換し、jpg形式のサムネイルに変換する
     * TODO: @utsumi-k PHP Parserを使うかは場合は別途用意する
     * @param $orgFilePath
     * @param $exportFilePath
     *
     * @return string
     */
    function createThumbnail($orgFilePath, $exportFilePath) {
        // 書き出しファイル名を生成
        $outputFilePath = $exportFilePath . "/" . $this->generateUniqueFileName();

        // 元画像情報を取得
        $size = getimagesize($orgFilePath);
        if ($size === false) {
            // 画像として認識できなかった
            return "";
        }
        list($w, $h, $type) = $size;
        list($width, $height) = $this->getSaveFileSize($w, $h);
        
        $ww = 200;
        $height = $height/$width * $ww;

        // 1回最初にリサイズする
        $res = new \Imagick($orgFilePath);
        if (!$res->thumbnailImage($ww, $height, true, true)) {
            // リサイズ失敗
            return "";
        }

        if (!$res->writeImage($outputFilePath)) {
            // 書き込み失敗
            return "";
        }

        return $outputFilePath;
    }

    /**
     * Uniqueなファイル名を生成する
     * TODO: 絶対かぶらない保証もないし、非同期で保存されるけど、今んとこそこは危惧するレベルではないと判断
     * @return string
     */
    function generateUniqueFileName($ext = ".jpg") {
        return md5(uniqid(rand(),1)) . $ext;
    }


    /**
     * 保存するサイズを計算する
     * 960*540 までのサイズにする
     * @param $orgWidth
     * @param $orgHeight
     *
     * @return array
     */
    function getSaveFileSize($orgWidth, $orgHeight) {
        $maxWidth = 960;
        $maxHeight = 540;
        $w = $orgWidth;
        $h = $orgHeight;

        if ($orgWidth > $maxWidth || $orgHeight > $maxHeight) {
            // リサイズ必要
            if ($orgWidth > $orgHeight) {
                // 横長
                $rate = $maxWidth / $orgWidth;

            } elseif ($orgHeight > $orgWidth) {
                // 縦長
                $rate = $maxHeight / $orgHeight;
            } else {
                // 正方形
                $rate = $maxHeight / $orgHeight;
            }
            $w = (int)($orgWidth * $rate);
            $h = (int)($orgHeight * $rate);
        }
        return [$w,$h];
    }
}