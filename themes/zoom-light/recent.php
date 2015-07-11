    <?php $p = new Profiler('Recent page load', 0, 2); ?>

      <div id="leftcontent"></div>

      <div id="centercontent">
        <div id='page_header'>
          <span id='page_title'>
          	<?php
          		if ( $photo->paramTags ) {
          			echo $photo->paramTags;
          		} else {
          			echo "Recent";
          		}
          	?>
          </span>
            <span id='page_nav'>
            <?php
            $photos = $photo->get_photos();
            $photo->previous_page_link($photos, "<span style='float:left' class='ui-icon ui-icon-triangle-1-w'></span>prev");
            echo "&nbsp;";
            $photo->next_page_link($photos, "next<span style='float:right' class='ui-icon ui-icon-triangle-1-e'></span>");
            ?>
            </span>
        </div>

        <div id='page'>

            <!-- Show the photo and link the photo to the previous photo -->
            <div id='thumbnail_container'>
            	<?php
            		$photo->slideshow_thumbnails( $photos );
            	?>
            </div>
        </div>
      </div>
      
      <div id="rightcontent"></div>

      <script type='text/javascript'>
          $(document).ready(function(){
              $("#menuitem_recent").addClass("selected");
          });
      </script>