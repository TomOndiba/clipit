<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   26/06/14
 * Last update:     26/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entities = elgg_extract('entities', $vars);

$href = http_build_query(array(
    'by' => get_input('by'),
    'id' => get_input('id'),
    'text' => get_input('text'),
    'filter' => get_input('filter'),
));
if(get_input('by')){
    $href = "/search?{$href}";
}
$href = (get_input('by') || get_input('text')) ? $href.'&' : '?';
$activity_get = get_input('activity');

$icon = '
    <div class="image-block">
        <i class="fa fa-globe"></i>
    </div>';
elgg_register_menu_item('explore:menu', array(
    'name' => 'explore_site',
    'text' => $icon.elgg_echo('site'),
    'style' => 'margin-bottom: 20px',
    'href' => "explore{$href}site=true",
    'selected' => $activity_get ? false : true
));

foreach($entities as $entity){
    $selected = false;
    if($activity_get == $entity->id){
        $selected = true;
    }
    $icon = '
    <div class="image-block">
        <span class="activity-point" style="background: #'.$entity->color.'"></span>
    </div>';
    elgg_register_menu_item('explore:menu', array(
        'name' => 'explore_'.$entity->id,
        'text' => $icon.$entity->name,
        'href' => "explore{$href}activity={$entity->id}",
        'selected' => $selected
    ));
}
echo elgg_view_menu('explore:menu', array(
    'sort_by' => 'register',
));

