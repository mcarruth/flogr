    <?php
    $p = new Profiler('Photo page load', 0, 2);
    ?>

    <?php
    $src = $photo->get_img_src(null, FLOGR_PHOTO_QUALITY, FLOGR_MAIN_PHOTO_SIZE);
    if (!$photo->paramPhotoId) {
        $next = $photo->get_next_page_href($photo->photoList);
        $prev = $photo->get_previous_page_href($photo->photoList);
    }
    ?>

    <script type='text/javascript'>
        $(document).ready(function() {
            $("#title").html("<?php echo $photo->photopage_link(null, $photo->get_title()); ?>");
            $("#info-panel-title").html("<h3><?php echo $photo->photopage_link(null, $photo->get_title()); ?></h3>");
            $("#info-panel").html("<blockquote><p><?php $photo->description() != null?$photo->description():$photo->title(); ?></p></blockquote><table class='table table-striped'><tr> <td>Owner</td> <td><?php $photo->userlink(); ?></td> </tr> <tr> <td>Date</td> <td><?php $photo->dateposted(); ?></td> </tr> <tr> <td>Views</td> <td><?php $photo->views_count(); ?></td> </tr> <tr> <td>Comments</td> <td><?php $photo->comments_count(); ?></td> </tr> <tr> <td>License</td> <td><?php $photo->license_link(); ?></td> </tr> <tr> <td>Links</td> <td> <?php $photo->photopage_link(null, 'Flickr'); echo ', '; $photo->permalink(null, 'Permalink'); ?> </td> </tr> <tr> <td>EXIF</td> <td><?php $photo->exif(); ?></td> </tr> <tr><td>Tags</td> <td><?php $photo->tags_list(null, '', ' '); ?></td> </tr> <tr> <td>Comments</td> <td><?php $photo->comments_list(null, '', ' says:<dl/><dt>', '</dt><br/>', false); $photo->photopage_link(null, 'Leave a comment'); ?> </td> </tr> </table>");

        });
    </script>

    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

        <img width='100%' src='<?php echo $src["url"]; ?>' class='img-responsive'>

        <!-- Controls -->
        <a class="left carousel-control" href="<?php echo $prev; ?>" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>

        <a class="right carousel-control" href="<?php echo $next; ?>" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>