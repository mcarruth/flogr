<?php
require_once( 'page.php' );

class Flogr_Group extends Flogr_Page {
    
    var $info;
    
    function get_info( $nsid = null ) {
        if (!$nsid) $nsid = $this->getconst('FLICKR_GROUP_ID');
        
        if (!$this->info || $this->info['id'] != $nsid) {
            $this->info = $this->phpFlickr->groups_getInfo( $nsid );
        }
        return $this->info;
    }

    function get_name( $nsid = null ) {
        $info = $this->get_info( $nsid );
        return $info['name'];
    }
    
    function name( $nsid = null ) {
        echo $this->get_name( $nsid );
    }
    
    function get_description( $nsid = null ) {
        $info = $this->get_info( $nsid );
        return $info['description'];
    }
    
    function description( $nsid = null ) {
        echo $this->get_description( $nsid );
    }
    
    function get_members( $nsid = null ) {
        $info = $this->get_info( $nsid );
        return $info['members'];
    }
    
    function members( $nsid = null ) {
        echo $this->get_members( $nsid );
    }
    
    function get_privacy( $nsid = null ) {
        $info = $this->get_info( $nsid );
        return $info['privacy'];
    }
    
    function privacy( $nsid = null ) {
        echo $this->get_privacy( $nsid );
    }
    
    function get_icon( $nsid = null ) {
       if (!$nsid) $nsid = $this->getconst('FLICKR_GROUP_ID');
        
       $info = $this->get_info( $nsid );
       $iconserver = $info['iconserver'];
       $iconfarm = $info['iconfarm'];
       $iconsrc = '';
       
       if ( 0 < $iconserver ) {
          $iconsrc = "http://farm{$iconfarm}.static.flickr.com/{$iconserver}/buddyicons/{$nsid}.jpg";
       } else { 
          $iconsrc = "http://www.flickr.com/images/buddyicon.jpg";
       }
       
       return "<img src='{$iconsrc}' />";
    }
    
    function icon( $nsid = null ) {
        echo $this->get_icon( $nsid );
    }

    function get_grouplink( $groupId = null ) {
        $p = new Profiler();
        $groupId = $groupId ? $groupId : FLICKR_GROUP_ID;
        return "http://www.flickr.com/people/{$groupId}/";
    }

    function grouplink( $groupId = null, $inner = null ) {
        $p = new Profiler();
        $inner = $inner ? $inner : $this->get_name($groupId);
        echo "<a href='{$this->get_grouplink($groupId)}'>{$inner}</a>";
    }

}
?>