<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   24/06/14
 * Last update:     24/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$href = elgg_extract('href', $vars);
$counts = elgg_extract('counts', $vars);
$video_count = isset($counts['videos']) ? " (".$counts['videos'].")" : "";
$sb_count = isset($counts['storyboards']) ? " (".$counts['storyboards'].")" : "";
$files_count = isset($counts['files']) ? " (".$counts['files'].")" : "";

$href = http_build_query(array(
    'by' => get_input('by'),
    'id' => get_input('id'),
    'text' => get_input('text'),
));
if(get_input('by')){
    $href = "/search?{$href}";
}
$href = (get_input('by') || get_input('text')) ? $href.'&' : '?';
$href_activity = get_input('activity') ? "&activity=".get_input('activity') : "";

$tabs = array(
    'all' => array(
        'text' => elgg_echo('all'),
        'href' => "explore".rtrim($href,'&').$href_activity,
        'priority' => 100,
    ),
    'videos' => array(
        'text' => elgg_echo('videos') ."{$video_count}",
        'href' => "explore{$href}filter=videos{$href_activity}",
        'priority' => 200,
    ),
    'storyboards' => array(
        'text' => elgg_echo('storyboards') ."{$sb_count}",
        'href' => "explore{$href}filter=storyboards{$href_activity}",
        'priority' => 300,
    ),
    /*'files' => array(
        'text' => elgg_echo('files') ."{$files_count}",
        'href' => "explore{$href}filter=files{$href_activity}",
        'priority' => 400,
    ),*/
);
foreach ($tabs as $name => $tab) {
    $tab['name'] = $name;

    if ($vars['selected'] == $name) {
        $tab['selected'] = true;
    }
    elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'nav nav-tabs'));