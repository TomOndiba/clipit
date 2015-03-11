<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   22/04/14
 * Last update:     22/04/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */

// Load all translations
global $CONFIG;
$client_language = $_COOKIE['client_language'];
if (!elgg_is_logged_in()) {
    if (!empty($client_language)) {
        $CONFIG->language = $client_language;
    }
    reload_all_translations();
} else {
    if (!empty($client_language)) {
        $user_id = elgg_get_logged_in_user_guid();
        ClipitUser::set_properties($user_id, array('language' => $client_language));
        setcookie('client_language', '', time() - 60 * 60 * 24 * 30, '/'); // reset cookie
    }
}

elgg_register_event_handler('init', 'system', 'clipit_global_init');

function clipit_global_init(){
    global $CONFIG;
    $user_id = elgg_get_logged_in_user_guid();
    $user = array_pop(ClipitUser::get_by_id(array($user_id)));
    /**
     * Register menu footer
     */
    setup_footer_menus();
    $plugin_dir = elgg_get_plugins_path() . "z03_clipit_global";
    // Register & load libs
    elgg_register_library('clipit:functions', "{$plugin_dir}/lib/functions.php");
    elgg_load_library('clipit:functions');

    // Activity admin module ajax
    elgg_register_ajax_view('dashboard/modules/activity_admin/task_list');

    elgg_register_action("clipit_theme/settings", "{$plugin_dir}/actions/settings.php", 'admin');
    elgg_register_action('login', "{$plugin_dir}/actions/login.php", 'public');
    elgg_register_action('logout', "{$plugin_dir}/actions/logout.php");
    elgg_register_action('register', "{$plugin_dir}/actions/register.php", 'public');

    elgg_register_action('user/requestnewpassword', "{$plugin_dir}/actions/user/requestnewpassword.php", 'public');
    elgg_register_action('user/passwordreset', "{$plugin_dir}/actions/user/passwordreset.php", 'public');
    elgg_register_action("user/check", "{$plugin_dir}/actions/check.php", 'public');
    // Language selector
    elgg_register_action('language/set', "{$plugin_dir}/actions/language/set.php", 'public');
    // Register ajax view for timeline events
    elgg_register_ajax_view('navigation/pagination_timeline');
    // Register ajax view for activity group status
    elgg_register_ajax_view('dashboard/modules/activity_groups_status');
    // Register public pages
    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'actions_clipit_public_pages');
    function actions_clipit_public_pages($hook, $type, $return_value, $params)
    {
        $return_value[] = 'action/user/check';
        $return_value[] = 'action/language/set';
        // Clipit sections
        $return_value[] = 'clipit/team';
        $return_value[] = 'clipit/about';
        $return_value[] = 'clipit/developers';
        // Help sections
        $return_value[] = 'help/support_center';
        $return_value[] = 'help/basics';
        // Legal sections
        $return_value[] = 'legal/terms';
        $return_value[] = 'legal/privacy';
        $return_value[] = 'legal/community_guidelines';
        // Landing sections
        $return_value[] = 'videos';
        $return_value[] = 'connect';
        return $return_value;
    }

    // Footer links
    elgg_register_page_handler('clipit', 'clipit_footer_page');
    elgg_register_page_handler('help', 'help_footer_page');
    elgg_register_page_handler('legal', 'legal_footer_page');

    elgg_register_page_handler('videos', 'videos_section');
    elgg_register_page_handler('video', 'video_view');
    elgg_register_page_handler('connect', 'connect_section');
    elgg_register_page_handler('login', 'login_user_account_page_handler');

    $plugin_url = elgg_get_site_url() . "mod/z03_clipit_global";
    if (elgg_get_context() === "admin") {
        if ($user->role == ClipitUser::ROLE_TEACHER) {
            elgg_unregister_page_handler('admin');
            return false;
        }
        elgg_unregister_css("twitter-bootstrap");
        elgg_unregister_css("ui-lightness");
        elgg_unregister_css("clipit");
        elgg_unregister_js("jquery-migrate");
        elgg_unregister_js("twitter-bootstrap");
    } else {
        elgg_register_css("ui-lightness", "{$plugin_url}/vendors/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom.min.css");
        elgg_register_js("jquery", "{$plugin_url}/vendors/jquery/jquery-1.9.1.min.js", "head", 0);
        elgg_register_js("jquery-migrate", "{$plugin_url}/vendors/jquery/jquery-migrate-1.1.1.js", "head", 1);
        elgg_register_js("jquery-ui", "{$plugin_url}/vendors/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.min.js", "head", 2);
        // Waypoints
        elgg_register_js("jquery:waypoints", "{$plugin_url}/vendors/waypoints/waypoints.min.js");
        elgg_register_js("jquery:waypoints:sticker", "{$plugin_url}/vendors/waypoints/waypoints-sticky.min.js");
        elgg_register_js("jquery:waypoints:infinite", "{$plugin_url}/vendors/waypoints/waypoints-infinite.min.js");
        // TinyMCE
        elgg_register_js("jquery:tinymce", "{$plugin_url}/vendors/tinymce/jquery.tinymce.min.js");
        elgg_register_js("tinymce", "{$plugin_url}/vendors/tinymce/tinymce.min.js");

        // Bootbox
        elgg_register_js("jquery:bootbox", "{$plugin_url}/vendors/bootbox.js");
        // jQuery validate
        elgg_register_js("jquery:validate", "{$plugin_url}/vendors/jquery.validate.js");
        // jquery tokeninput (automcomplete)
        elgg_register_js("jquery:tokeninput", "{$plugin_url}/vendors/tokeninput.js");
        // NVD3 chart
        elgg_register_js("nvd3:d3_v2", "{$plugin_url}/vendors/nvd3/d3.v2.js");
        elgg_register_js("nvd3", "{$plugin_url}/vendors/nvd3/nv.d3.js");
        elgg_register_css("nvd3:css", "{$plugin_url}/vendors/nvd3/nv.d3.css");
        // ClipIt
        elgg_register_css("clipit", "{$plugin_url}/bootstrap/less/clipit/clipit_base.min.css");
        // FontAwesome
        elgg_register_css("fontawesome", "{$plugin_url}/vendors/fontawesome/fontawesome.min.css");
        // jQuery cycle2
        elgg_register_js("jquery:cycle2", "{$plugin_url}/vendors/jquery.cycle2.js");
        // jQuery appear
        elgg_register_js("jquery:appear", "{$plugin_url}/vendors/jquery.appear.js");
        // jQuery Isotope
        elgg_register_js("jquery:isotope", "{$plugin_url}/vendors/jquery.isotope.js");
        // Animate CSS
        elgg_register_css("animate", "{$plugin_url}/vendors/animate.css");
        // landing main js
        $main_js = elgg_get_simplecache_url('js', 'main');
        elgg_register_simplecache_view('js/main');
        elgg_register_js('main', $main_js);


        $clipit_js = elgg_get_simplecache_url('js', 'clipit');
        elgg_register_simplecache_view('js/clipit');
        elgg_register_js('clipit', $clipit_js);

        elgg_load_js("jquery");
        elgg_load_js("jquery-ui");
        elgg_load_js("jquery-migrate");
        elgg_load_js("jquery:waypoints");
        elgg_load_js("jquery:waypoints:sticker");
        elgg_load_js("jquery:waypoints:infinite");
        elgg_load_js("jquery:wysihtml5");
        elgg_load_js("jquery:tinymce");
        elgg_load_js("tinymce");
        elgg_load_js("jquery:bootbox");
        elgg_load_js("jquery:bootstrap:wysihtml5");
        elgg_load_css("wysihtml5:css");
        elgg_load_js("jquery:validate");
        elgg_load_js("jquery:tokeninput");
        elgg_load_js("clipit");
        //elgg_load_css("ui-lightness");
        elgg_load_css("twitter-bootstrap");
        elgg_unregister_js("twitter-bootstrap");
        elgg_load_css("fontawesome");
        elgg_load_css("clipit");
        elgg_unregister_css("elgg");
        elgg_unregister_css("elgg.walled_garden");
        elgg_register_js("clipit_theme_bootstrap", "{$plugin_url}/bootstrap/dist/js/bootstrap.js");
        elgg_register_js("elgg.walled_gaarden", "{$plugin_url}/bootstrap/dist/js/adadadaotstrap.js");
        elgg_load_js("clipit_theme_bootstrap");
    }
}

function connect_section($page)
{
    $edu_centers = ClipitActivity::get_all();
    $edu_centers = ClipitActivity::get_by_id(array(63));
    $params = array(
        'content' => elgg_view('connect/view', array('entities' => $edu_centers)),
        'filter' => '',
        'sidebar' => '',
    );
    $body = elgg_view_layout('one_column', $params);
    echo elgg_view_page('', $body);
}

function video_view($page)
{
    if ($id = $page[0]) {
        $video = array_pop(ClipitVideo::get_by_id(array((int)$id)));
        $activity = array_pop(ClipitActivity::get_by_id(array(ClipitVideo::get_activity($video->id))));
        $sidebar = elgg_view_module('aside', false, elgg_view('walled_garden/sidebar/edu_block', array('entity' => $activity)));
        $sidebar .= elgg_view_module('aside', false, elgg_view('walled_garden/sidebar/videos', array('videos' => ClipitVideo::get_all(5))));
        $params = array(
            'content' => elgg_view('videos/view', array('entity' => $video)),
            'filter' => '',
            'sidebar' => $sidebar,
        );
        $body = elgg_view_layout('content', $params);
        echo elgg_view_page('', $body);
        return true;
    }
    return false;
}

function videos_section($page)
{
    $sidebar = elgg_view_module('aside', elgg_echo('educational:centers'), elgg_view('walled_garden/sidebar/edu_list'));
    $edu = $page[0];
    if ($edu) {
        if ($entity = array_pop(ClipitActivity::get_from_search($edu, false))) {
            $videos = ClipitActivity::get_videos($entity->id);
            $videos = ClipitVideo::get_by_id($videos);
            $sidebar = elgg_view_module('aside', false, elgg_view('walled_garden/sidebar/edu_block', array('entity' => $entity)));

        }
    } else {
        $videos = ClipitVideo::get_all();
    }
    $sidebar .= elgg_view_module('aside', false, elgg_view('walled_garden/sidebar/videos', array('videos' => ClipitVideo::get_all(5))));
//    ClipitActivity::set_properties(63, array('description' => 'urjc'));
//    ClipitActivity::set_properties(64, array('description' => 'esas'));

//    var_dump(ClipitActivity::get_all());
//    ClipitActivity::create(array(
//        'name' => 'Universidad Rey Juan Carlos',
//        'url' => 'http://www.urjc.es/',
//    ));
//    ClipitActivity::create(array(
//        'name' => 'Escola Secundária Alberto Sampaio',
//        'url' => 'http://www.esas.pt/',
//    ));
//var_dump(ClipitVideo::create(array(
//    'name' => "Uma Bala No Preconceito",
//    'url' => 'https://www.youtube.com/watch?v=61qqwT1Bk7M',
//    'description' => 'Vídeo realizado no âmbito do I FESTIVAL DO VÍDEO EDUCATIVO ESAS.',
//)));

// Centros = Actividades
    // Logo = file array
    // url =  $activity->url
// Videos pertenecen a actividad
    $params = array(
        'content' => elgg_view('videos/list', array('entities' => $videos)),
        'filter' => '',
        'sidebar' => $sidebar,
    );
    $body = elgg_view_layout('content', $params);
    echo elgg_view_page('', $body);
}

function login_user_account_page_handler($page_elements, $handler)
{

    $base_dir = elgg_get_plugins_path() . 'z03_clipit_global/pages/account';
    switch ($handler) {
        case 'login':
            require_once("$base_dir/login.php");
            break;
        default:
            return false;
    }
    return true;
}

function setup_footer_menus(){
    /*elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'about',
            'href' => 'clipit/about',
            'text' => elgg_echo('about'),
            'priority' => 450,
            'section' => 'clipit',
        )
    );*/
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'team',
            'href' => 'clipit/team',
            'text' => elgg_echo('team'),
            'priority' => 455,
            'section' => 'clipit',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'developers',
            'href' => 'http://juxtalearn.github.io/clipit/',
            'text' => elgg_echo('developers'),
            'priority' => 460,
            'target' => true,
            'section' => 'clipit',
        )
    );
    // Tutorials section
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'student',
            'href' => 'http://clipit.es/tutorials/student_'.get_current_language().'.pdf',
            'text' => elgg_echo('student'),
            'target' => '_blank',
            'priority' => 460,
            'section' => 'tutorials',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'teacher',
            'href' => 'http://clipit.es/tutorials/teacher_'.get_current_language().'.pdf',
            'text' => elgg_echo('teacher'),
            'target' => '_blank',
            'priority' => 465,
            'section' => 'tutorials',
        )
    );
    // Legal section
    /*elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'terms',
            'href' => 'legal/terms',
            'text' => elgg_echo('terms'),
            'priority' => 460,
            'section' => 'legal',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'privacy',
            'href' => 'legal/privacy',
            'text' => elgg_echo('privacy'),
            'priority' => 465,
            'section' => 'legal',
        )
    );*/
}

function clipit_footer_page($page)
{
    $file_dir = elgg_get_plugins_path() . 'z03_clipit_global/pages/clipit';
    switch ($page[0]) {
        case "about":
            return false;
            break;
        case "team":
            include "$file_dir/team.php";
            break;
        case "developers":
            return false;
            break;
        default:
            return false;
    }
    return true;
}

function help_footer_page($page)
{
    return false;
}

function legal_footer_page($page)
{
    return false;
}

// Dashboard page
