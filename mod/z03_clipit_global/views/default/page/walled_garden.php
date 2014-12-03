<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */
elgg_unregister_css("twitter-bootstrap"); // Quitamos el bootstrap por defecto y aplicamos el exclusivo del home
// Set the content type
header("Content-type: text/html; charset=UTF-8");
$plugin = elgg_get_plugin_from_id('z03_clipit_site');
$vars_plugin = $plugin->getAllSettings();
$vars_plugin['img_path'] = $CONFIG->wwwroot."mod/z03_clipit_global/graphics/";
$vars_plugin['bg_img'] = $vars_plugin['img_path']."icons/".$vars_plugin['bg_img'];
$vars_plugin['logo_img'] = $vars_plugin['img_path']."icons/".$vars_plugin['logo_img'];
$vars = array_merge($vars_plugin, $vars);

// Unregister default walled_garden js & css
elgg_unregister_css('elgg.walled_garden');
elgg_unregister_js('elgg.walled_garden');

elgg_load_css('animate');
elgg_load_js('jquery:appear');
elgg_load_js('jquery:cycle2');
elgg_load_js('main');

$images_dir = elgg_get_site_url() . "mod/z03_clipit_global/graphics";
$footer = elgg_view('page/elements/footer', $vars);
$header_top = elgg_view('page/elements/header_top', array('walled_garden' => true));
$header_account = elgg_view('page/elements/header_account', array('images_dir' => $images_dir));

$accounts = array(
    'facebook'  => 'https://www.facebook.com/jxl.juxtalearn',
    'twitter'   => 'https://twitter.com/juxtalearn_eu',
    'youtube'   => 'https://www.youtube.com/channel/UCAZfYqqx1pGvpyUaxikBlTw',
    'vimeo'     => 'http://vimeo.com/jxl',
    'linkedin'  => 'https://www.linkedin.com/groups?gid=4841898',
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, maximum-scale=1">
    <?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
    <div class="elgg-page elgg-page-walledgarden">
        <div class="elgg-page-messages">
            <?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
        </div>
        <header>
            <?php echo $header_top; ?>
            <?php echo $header_account; ?>
        </header>
        <div id="wrap">
            <?php echo elgg_view("walled_garden/body", array('accounts' => $accounts, 'images_dir' => $images_dir));?>
        </div>
    </div>
    <?php echo elgg_view('page/elements/footer'); ?>
</body>
</html>