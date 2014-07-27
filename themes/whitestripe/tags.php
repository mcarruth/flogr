<?php $p = new Profiler('Tags page load', 0, 2); ?>
<?php
require_once( 'user.php' );
?>
      <div id="leftcontent"></div>

      <div id="centercontent">
        <div id='page_header'>
          <span id='page_title'>
          	Tags
          </span>
        </div>

        <div id='page'>

            <!-- Show the photo and link the photo to the previous photo -->
            <div id='thumbnail_container'>
          	<?php $user->tag_list_popular(null, '<span>', '</span'); ?>
            </div>
        </div>
      </div>

      <div id="rightcontent"></div>
      
      <script type='text/javascript'>
          $(document).ready(function(){
              $("#menuitem_tags").addClass("selected");
          });
      </script>