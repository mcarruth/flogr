<ul>
    <li>
        <a id="menuitem_home" href="index.php">Home</a>&nbsp;
    </li>
    <li>
        <a id="menuitem_recent" href="index.php?type=recent">Recent</a>&nbsp;
    </li>
<?php if (defined('FLICKR_USER_ID')) { ?>
    <li>
        <a id="menuitem_sets" href="index.php?type=sets">Sets</a>&nbsp;
    </li>
    <li>
        <a id="menuitem_tags" href="index.php?type=tags">Tags</a>&nbsp;
    </li>
    <li>
        <a id="menuitem_map" href="index.php?type=map">Map</a>&nbsp;
    </li>
    <li>
        <a id="menuitem_favorites" href="index.php?type=favorites">Favorites</a>&nbsp;
    </li>
    <li>
        <a id="menuitem_about" href="index.php?type=about">About</a>
    </li>
<?php } ?>
</ul>
