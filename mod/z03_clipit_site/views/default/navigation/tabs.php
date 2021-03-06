<?php
 /**
 * Clipit Web Space
 * PHP version:     >= 5.2
 * Creation date:   28/01/2015
 * Last update:     28/01/2015
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC Clipit Project
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 */
$href = elgg_extract('href', $vars);
$base_url = elgg_http_remove_url_query_element(current_page_url(), 'offset');

$tabs = array(
    'all' => array(
        'text' => elgg_echo('all'),
        'href' => elgg_http_remove_url_query_element($base_url, 'filter'),
        'priority' => 100,
    ),
    'mine' => array(
        'text' => elgg_echo('mine'),
        'href' => elgg_http_add_url_query_elements($base_url, array('filter' => 'mine')),
        'priority' => 200,
    ),
);

foreach ($tabs as $name => $tab) {
    $tab['name'] = $name;

    if ($vars['selected'] == $name) {
        $tab['selected'] = true;
    }

    elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'nav nav-tabs'));