<?php
require_once('page.php');

class Flogr_User extends Flogr_Page {

    var $info;

    function get_info( $userId = null ) {
        $p = new Profiler();
    	if (!$userId) $userId = FLICKR_USER_ID;

        if (!$this->info || $this->info['nsid'] != $userId) {
            $this->info = $this->phpFlickr->people_getInfo( $userId );
        }
        return $this->info;
    }

    function get_username( $userId = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       return $info['username'];
    }

    function username( $userId = null ) {
        $p = new Profiler();
    	echo $this->get_username( $userId );
    }

    function get_userlink( $userId = null ) {
        $p = new Profiler();
        $userId = $userId ? $userId : FLICKR_USER_ID;
        return "http://www.flickr.com/people/{$userId}/";
    }

    function userlink( $userId = null, $inner = null ) {
        $p = new Profiler();
        $userId = $userId ? $userId : FLICKR_USER_ID;
        $inner = $inner ? $inner : $this->get_username($userId);
        echo "<a href='{$this->get_userlink($photoId)}'>{$inner}</a>";
    }

    function get_realname( $userId = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       return $info['realname'];
    }
    
    function realname( $userId = null ) {
        $p = new Profiler();
    	echo $this->get_realname( $userId );
    }
    
    function get_location( $userId = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       return $info['location'];
    }
    
    function location( $userId = null ) {
        $p = new Profiler();
    	echo $this->get_location( $userId );
    }
    
    function get_photoslink( $userId = null, $inner = null) {
		$p = new Profiler();
    	$info = $this->get_info( $userId );
       	$url = $info['photosurl'];
       	$inner = $inner ? $inner : $url;
       
       	return "<a href='{$url}'>{$inner}</a>";
    }
    
    function photoslink( $userId = null, $inner = null ) {
        $p = new Profiler();
    	echo $this->get_photoslink( $userId, $inner );
    }
    
    function get_profilelink( $userId = null, $inner = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       	$url = $info['profileurl'];
		$inner = $inner ? $inner : $url;
       
       	return "<a href='{$url}'>{$inner}</a>";
    }
    
    function profilelink( $userId = null, $inner = null ) {
		$p = new Profiler();
    	echo $this->get_profilelink( $userId, $inner );
    }
    
    function get_mobilelink( $userId = null, $inner = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       	$url = $info['mobileurl'];
       	$inner = $inner ? $inner : $url;
       
       	return "<a href='{$url}'>{$inner}</a>";
    }
    
    function mobilelink( $userId = null, $inner = null ) {
        $p = new Profiler();
    	echo $this->get_mobilelink( $userId, $inner );
    }
    
    function get_firstdate( $userId = null, $format = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       $format = $format ? $format : FLOGR_DATE_FORMAT;
       return date( $format, $info['photos']['firstdate'] );
    }
    
    function firstdate( $userId = null, $format = null ) {
        $p = new Profiler();
    	echo $this->get_firstdate( $userId, $format );
    }
    
    function get_photos_firstdatetaken( $userId = null, $format = null ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       $format = $format ? $format : FLOGR_DATE_FORMAT;
       return date( $format, strtotime($info['photos']['firstdatetaken']) );
    }
    
    function photos_firstdatetaken( $userId = null, $format = null ) {
        $p = new Profiler();
    	echo $this->get_photos_firstdatetaken( $userId, $format );
    }
    
    function get_photos_count( $userId = null ) {
    	$p = new Profiler();
    	$info = $this->get_info( $userId );
       return $info['photos']['count'];
    }
    
    function photos_count( $userId = null ) {
        $p = new Profiler();
    	echo $this->get_photos_count( $userId );
    }
    
    function get_isadmin( $userId = null, $truetext = 'Y', $falsetext = 'N' ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       return $info['isadmin'] ? $truetext : $falsetext;
    }
    
    function isadmin( $userId = null, $truetext = 'Y', $falsetext = 'N' ) {
        $p = new Profiler();
    	echo $this->get_isadmin( $userId, $truetext, $falsetext );
    }
    
    function get_ispro( $userId = null, $truetext = 'Y', $falsetext = 'N' ) {
        $p = new Profiler();
    	$info = $this->get_info( $userId );
       return $info['ispro'] ? $truetext : $falsetext;
    }
    
    function ispro( $userId = null, $truetext = 'Y', $falestext = 'N' ) {
        $p = new Profiler();
    	echo $this->get_ispro( $userId, $truetext, $falsetext );
    }
    
    function get_buddyicon( $userId = null ) {
        $p = new Profiler();
    	if (!$userId) $userId = FLICKR_USER_ID;
        
       $info = $this->get_info( $userId );
       $iconserver = $info['iconserver'];
       $iconfarm = $info['iconfarm'];
       $iconsrc = '';
       
       if ( 0 < $iconserver ) {
          $iconsrc = "http://farm{$iconfarm}.static.flickr.com/{$iconserver}/buddyicons/{$userId}.jpg";
       } else { 
          $iconsrc = "http://www.flickr.com/images/buddyicon.jpg";
       }
       
       return "<img src='{$iconsrc}' />";
    }
    
    function buddyicon( $userId = null ) {
        $p = new Profiler();
    	echo $this->get_buddyicon( $userId );
    }

    function get_blog_list($before = '<li>', $after = '</li>') {
        $p = new Profiler();
    	$blogs = $this->phpFlickr->blogs_getList();
        $blogList = null;
        if (!$blogs) return;
        
        foreach( $blogs['blog'] as $blog ) {
            $blogList .= "{$before}<a href='" . $blog['url'] . "'>" . 
                $blog['name'] . "</a>{$after}";
        }
        return $blogList;
    }
    
    function blog_list($before = null, $after = null) {
        $p = new Profiler();
    	echo $this->get_blog_list($before, $after);
    }
    
    function get_tag_list($userId = null, $before = '<li>', $after = '</li>') {
        $p = new Profiler();
    	$userId = $userId ? $userId : FLICKR_USER_ID;
        $tags = $this->phpFlickr->tags_getListUser( $userId );
        $tagList = null;
        if (!$tags) return;
        
        foreach( $tags as $tag ) {
            $tagList .= "{$before}<a href='index.php?type=recent&tags=" . $tag . "'>" .
                $tag . "</a>{$after}";
        }
        return $tagList;
    }
    
    function tag_list($userId = null, $before = '<li>', $after = '</li>') {
        $p = new Profiler();
    	echo $this->get_tag_list($userId, $before, $after);
    }
    
    function get_tag_list_popular($userId = null, $before = '<li>', $after = '</li>') {
        $p = new Profiler();
    	$userId = $userId ? $userId : FLICKR_USER_ID;
        $tags = $this->phpFlickr->tags_getListUserPopular( $userId, FLOGR_TAGS_COUNT );
        $tagList = null;
        $tagClass = 0;
        if (!$tags) return;
        
        //
        // Find the maximum occurence of the 100 most frequently used tag
        //
        $tagTotal = 0;
        foreach ($tags as $tag)
        {
           $tagTotal += $tag['count'];
        }

        //
        // Build the tag cloud by wrapping the tag name into a span styled: tag_xsmall,
        // tag_small, tag_medium, tag_large, tag_xlarge.
        //        
        $tag_xsmall = ($tagTotal / count($tags)) / 2;
        $tag_small  = $tag_xsmall * 2;
        $tag_medium = $tag_xsmall * 3;
        $tag_large  = $tag_xsmall * 4;
        
        foreach ($tags as $tag)
        {
           if ( FLOGR_TAGS_INCLUDE_HIDE && stristr(FLOGR_TAGS_INCLUDE, $tag['_content']) )
           {
               continue;
           }
           
           $tagList .= "{$before}<a href='index.php?type=recent&tags=" . $tag['_content'] . "'";
           $tagList .= " title='" . $tag['_content'] . "=" . $tag['count'] . "'>";
           
           if (0 < $tag['count'] && $tag['count'] <= $tag_xsmall)
           {
              $tagList .= "<span class='tag_xsmall'>" . $tag['_content'] . "</span> ";
           }
           else if ($tag_xsmall < $tag['count'] && $tag['count'] <= $tag_small)
           {
              $tagList .= "<span class='tag_small'>" . $tag['_content'] . "</span> ";
           }
           if ($tag_small < $tag['count'] && $tag['count'] <= $tag_medium)
           {
              $tagList .= "<span class='tag_medium'>" . $tag['_content'] . "</span> ";
           }
           if ($tag_medium < $tag['count'] && $tag['count'] <= $tag_large)
           {
              $tagList .= "<span class='tag_large'>" . $tag['_content'] . "</span> ";
           }
           if ($tag_large < $tag['count'])
           {
              $tagList .= "<span class='tag_xlarge'>" . $tag['_content'] . "</span> ";
           }
           
           $tagList .= "</a>{$after}";
        }
        
        return $tagList;
    }
    
    function tag_list_popular($userId = null, $before = '<li>', $after = '</li>') {
        $p = new Profiler();
    	echo $this->get_tag_list_popular($userId, $before, $after);
    }
    
    function get_public_groups($userId = null) {
    	$p = new Profiler();
    	$userId = $userId ? $userId : FLICKR_USER_ID;
    	return $this->phpFlickr->people_getPublicGroups( $userId );	
    }
    
    function public_groups_list_links( $userId = null, $before = '<li>', $after = '</li>' ) {
    	$p = new Profiler();
    	$groups = $this->get_public_groups($userId, $before, $after);
    	$groupList = '';
    	foreach ( $groups as $group ) {
    		$groupList .= "{$before}<a href='http://www.flickr.com/groups/" . $group['nsid'] . "'>{$group['name']}</a>{$after}";
    	}
    	echo $groupList;
    }
    
}

$user = new Flogr_User();
?>