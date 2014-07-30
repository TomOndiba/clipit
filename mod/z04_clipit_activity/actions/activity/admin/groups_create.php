<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   29/07/14
 * Last update:     29/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
//$activity_id = get_input('entity-id');
//$group_name = get_input('group_name');
//$users = get_input('called_users');

$group = get_input('group');
$group_name = $group['name'];
$users = $group['users'];
$activity_id = get_input('activity_id');

if(empty($users) || trim($group_name) == ""){
    register_error(elgg_echo("group:cantcreate"));
} else {
    $group_id = ClipitGroup::create(array(
        'name' => $group_name,
    ));
    ClipitActivity::add_students($activity_id, $users);
    ClipitGroup::add_users($group_id, $users);
    ClipitActivity::add_groups($activity_id, array($group_id));
    //system_message(elgg_echo('group:created'));

    $result["status"] = true;
    $html = elgg_view("activity/admin/groups/summary", array('groups' => ClipitGroup::get_by_id(array($group_id))));
    $result["text"] = $html;
    echo json_encode($result);
    die();
}
forward(REFERRER);