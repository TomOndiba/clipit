<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */
// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
    if (get_input('handler') != 'admin') {
        elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
    }
    elgg_admin_add_plugin_settings_menu();
    elgg_unregister_css('elgg');
    echo elgg_view('page/admin', $vars);
    return true;
}

// render content before head so that JavaScript and CSS can be loaded. See #4032
$plugin = elgg_get_plugin_from_id('z03_clipit_theme');
$vars_plugin = $plugin->getAllSettings();
$vars_plugin['img_path'] = $CONFIG->wwwroot."mod/z03_clipit_global/graphics/";
$vars_plugin['bg_img'] = $vars_plugin['img_path']."icons/".$vars_plugin['bg_img'];
$vars_plugin['logo_img'] = $vars_plugin['img_path']."icons/".$vars_plugin['logo_img'];
$vars = array_merge($vars_plugin, $vars);

$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$images_dir = elgg_get_site_url() . "mod/z03_clipit_global/graphics/";
$header_top = elgg_view('page/elements/header_top', $vars);
$header_account = elgg_view('page/elements/header_account', array('images_dir' => $images_dir));
$body = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/footer', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");
$lang = get_current_language();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet/less" href="<?php echo $CONFIG->wwwroot; ?>mod/clipit_theme/bootstrap/less/components_clipit.less" />-->
    <?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body style="background: #fff">
<div id="wrap" class="team-section">
    <div class="elgg-page-messages">
        <?php echo $messages; ?>
    </div>
    <header>
        <?php echo $header_top; ?>
        <?php echo $header_account; ?>
    </header>
    <div class="container">
        <?php echo $body; ?>
    </div>
    <div class="overflow-hidden" style="background: #f1f2f7 !important;">
        <div class="container margin-bottom-20">
            <?php echo $vars['internships'];?>
        </div>
    </div>
</div>
<?php echo elgg_view('page/elements/footer'); ?>
</body>
</html>