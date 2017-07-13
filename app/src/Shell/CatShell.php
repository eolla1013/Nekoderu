<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\GoogleApiComponent;

class CatShell extends Shell
{
    
     public function initialize()
    {
        parent::initialize();
        $this->loadModel('Cats');
        $this->loadModel('CatImages');
        $this->GoogleApi = new GoogleApiComponent(new ComponentRegistry());
    }
    
    public function main()
    {
        $this->out('Hello world.');
    }
    
    
    public function inputTNRDataFromGoogleDrive($sheetId, $folderId, $maxnum){
        debug($sheetId);
        debug($folderId);
        debug(intval($maxnum));
        $this->GoogleApi->inputTNRDataFromGoogleDrive(
            $sheetId, $folderId, $maxnum
        );
    }
    
    public function setImageAnalysis(){
     
        $catImages = $this->CatImages->find()->contain(['CatImageAnalyses']);
        
        foreach($catImages as $catImage){
            
            if(count($catImage->cat_image_analyses) <= 0){
                $json = $this->GoogleApi->detectObjects($catImage->url);
                $cia = $this->CatImages->CatImageAnalyses->newEntity();
                
                $cia->catImage_id = $catImage->id;
                $cia->analyzer = "vision.googleapis.com/v1/images:annotate";
                $cia->data = $json;
                if($this->CatImages->CatImageAnalyses->save($cia)){
                    $catImage->catImageAnalyses = [$cia];
                    
                    $this->out($catImage->url);
                    $this->out($json);
                }
            }
        }
        
    }
    
    public function setLocation()
    {
        $this->out('Hey there ');
        
        $cats = $this->Cats->find();
        $cats = $cats->select(['id', 'locate', 'address']);
        $cats = $cats->all()->toArray();
        
        foreach($cats as $cat){
            if($cat->address && !$cat->locate){
                
                $this->out(print_r($cat->address, true));
                $result = $this->toLatLng($cat->address);
                
                if($result){
                    $cat->locate = "".$result['lat'].",".$result['lng'];
                    $this->out(print_r($cat->locate, true));
                    $this->Cats->save($cat);
                }
            }
        }
    }
    
    function toLatLng($address = NULL){
    	$returnArray = array();
    	if($address === NULL) return false;
    
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&language=ja&address='.urlencode($address);
        
    	$googleMapsApiData = json_decode(@file_get_contents($url), true);
    	if($googleMapsApiData['status'] !== 'OK') return false;

    	$returnArray['lat'] = $googleMapsApiData['results'][0]['geometry']['location']['lat'];
    	$returnArray['lng'] = $googleMapsApiData['results'][0]['geometry']['location']['lng'];
    
    	return $returnArray;
    }

}