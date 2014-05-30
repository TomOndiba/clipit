<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   29/05/14
 * Last update:     29/05/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$activity = elgg_extract('activity', $vars);
$author_id = elgg_extract('author_id', $vars);
$object = ClipitSite::lookup($author_id);

switch($object['subtype']){
    case "ClipitGroup":
        echo '<div style="width: 40px; 40px;background: #'.$activity->color.';" class="text-center">
                    <h1 style="color: #fff;margin: 0">G</h1>
                </div>';
        break;
    case "ClipitActivity":
        echo '<div style="width: 40px; 40px;background: #'.$activity->color.';" class="text-center">
                    <h1 style="color: #fff;margin: 0">A</h1>
                </div>';
        break;
}
if($object['type'] == 'user'){
    $user = array_pop(ClipitUser::get_by_id(array($author_id)));
    $elgg_user = new ElggUser($author_id);
    echo '<img src="'.$elgg_user->getIconURL('small').'"/>';
}

