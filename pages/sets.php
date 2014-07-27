<?php
require_once('page.php');

class Flogr_Set extends Flogr_Page {
	
	var $setInfo = null;
	
    function get_user_sets( $userId = null ) {
        $p = new Profiler();
    	$userId = $userId ? $userId : FLICKR_USER_ID;
        $this->setList = $this->phpFlickr->photosets_getList( $userId );
        return $this->setList;
    }        
    
    function get_set_list( $userId = null, $before = '', $inner = '', $after = '' ) {
        $p = new Profiler();
    	$setList = $this->get_user_sets( $userId );
        foreach( $setList['photoset'] as $photoset ) {
            if ( !FLOGR_PHOTOSETS_INCLUDE || stristr(FLOGR_PHOTOSETS_INCLUDE, $photoset['title']) ) {
                $photosetList .= $this->get_set_link($photoset, $this->get_primary_photo_img($photoset, "square"));
            }
        }
        return $photosetList;
    }
    
    function set_list( $userId = null, $before = '', $after = '' ) {
        $p = new Profiler();
    	echo $this->get_set_list( $userId, $before, $after );
    }
    
    function get_set_photos(
        $photosetId = null,
        $extras = null,
        $privacyFilter = null,
        $perPage = null,
        $page = null) {            
        $p = new Profiler();
        	$photosetId = $photosetId ? $photosetId : $this->paramSetId;
            
            return $this->phpFlickr->photosets_getPhotos(
                $photosetId,
                $extras ? $extras : 'original_format,date_taken,date_upload',
                $privacyFliter,
                $perPage ? $perPage : $this->paramPerPage,
                $page ? $page : $this->paramPage);
    }
    
    function set_photos(
        $photosetId = null,
        $extras = null,
        $privacyFilter = null,
        $perPage = null,
        $page = null) {
        	$p = new Profiler();
        	$photos = $this->get_set_photos($photosetId, $extras, $privacyFilter, $perPage, $page);
            $this->slideshow_thumbnails( $photos );
    }    
    
    function get_info( $id = null ) {
    	$p = new Profiler();
    	$id = $id ? $id : $this->paramSetId;
    	if ( !$setInfo || $setInfo['id'] != $id ) {
    		$this->setInfo = $this->phpFlickr->photosets_getInfo( $id );
    	}
    	return $this->setInfo;
    }
    
    function get_primary_photo_id( $id = null ) {
        $p = new Profiler();
        
        $info = $this->getInfo( $id );
        return $info['primary'];        
    }
    
    function get_primary_photo_img( $set, $size = null ) {
        $p = new Profiler();
        
        $sizes = array(
            "square" => "_s",
            "thumbnail" => "_t",
            "small" => "_m",
            "medium" => "",
            "large" => "_b",
            "original" => "_o"
        );
        
        $size = strtolower($size);
        if (!array_key_exists($size, $sizes)) {
            $size = "medium";
        }
        
        $title = htmlspecialchars($set['title'], ENT_QUOTES);
        $desc  = htmlspecialchars($set['description'], ENT_QUOTES);
        
        return "<img class='thumbnail' title='{$title}' rel='{$desc}' src='http://farm" . $set['farm'] . ".static.flickr.com/" . $set['server'] . "/" . $set['primary'] . "_" . $set['secret'] . $sizes[$size] . ".jpg'/>";
    }
    
    function get_photo_count( $id = null ) {
        $p = new Profiler();
        
        $info = $this->get_info( $id );
        return $info['photos'];      
    }
        
    function get_title( $id = null ) {
        $p = new Profiler();
        
        $info = $this->get_info( $id );
        return $info['title'];                
    }
    
    function title( $id = null ) {
        $p = new Profiler();
    	echo $this->get_title( $id );        
    }
    
    function get_description( $id = null ) {
        $p = new Profiler();
        
        $info = $this->get_info( $id );
        return $info['description'];              
    }
    
    function description( $id = null ) {
        $p = new Profiler();
    	echo $this->get_description( $id );        
    }
    
    function get_set_link( $set, $inner = '' ) {
        $p = new Profiler();
        $title = htmlspecialchars($set['title'], ENT_QUOTES);
    	$inner = $inner ? $inner : $title; 
        $desc = htmlspecialchars($set['description'], ENT_QUOTES);
        $id = $set['id'];
        $url = $this->phpFlickr->buildPhotoURL($set['id'], "Square");
        $string =  "<a href='" . $_SERVER['PHP_SELF'] . "?type=sets&setId={$id}' title='' rev='{$title} | {$desc}'>{$inner}</a>";
        
        return $string;
    }    
    
    function set_link( $photosetId = '', $inner = '' ) {
        $p = new Profiler();
    	echo $this->get_set_link( $photosetId, $inner );
    }
}

$set = new Flogr_Set();
?>
