<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title><?php echo SITE_TITLE; ?></title>
        <link href='<?php echo SITE_URL;?>/<?php echo SITE_THEME_PATH;?>css/<?php echo SITE_THEME; ?>.css' rel='stylesheet' type='text/css' />
        <link rel='alternate' type='application/rss+xml' title='<?php echo SITE_TITLE; ?>' href='<?php echo SITE_URL; ?>/index.php?type=rss' />
    </head>
    <body>