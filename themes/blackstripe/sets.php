    <?php $p = new Profiler('Sets page load', 0, 2); ?>
      <div id="leftcontent"></div>

      <div id="centercontent">
        <div id='page_header'>
          <span id='page_title'>
          <?php
          if ( $set->paramSetId ) {
          	$set->title();
          } else {
           	echo "Sets";
          }
           ?>
          </span>
            <span id='page_nav'>
            <?php
            if ($set->paramSetId) {
                $setPhotos = $set->get_set_photos();
                $set->previous_page_link($setPhotos, "<span style='float:left' class='ui-icon ui-icon-triangle-1-w'></span>prev");
                echo "&nbsp;";
                $set->next_page_link($setPhotos, "next<span style='float:right' class='ui-icon ui-icon-triangle-1-e'></span>");
            }
            ?>
            </span>
        </div>

        <div id='page'>

            <!-- Show the photo and link the photo to the previous photo -->
            <div id='thumbnail_container'>
            	<?php
            		if ( $set->paramSetId ) { 
            			$set->slideshow_thumbnails( $setPhotos );            			
            		} else {
            			$set->set_list();
            		}
            	?>
            </div>
        </div>
      </div>
      
      <div id="rightcontent"></div>

      <script type='text/javascript'>
          $(document).ready(function(){
              $("#menuitem_sets").addClass("selected");
          });
      </script>