<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 13/02/14
 * Time: 14:04
 * To change this template use File | Settings | File Templates.
 */


$user = elgg_get_logged_in_user_entity();
$my_groups_ids = ClipitUser::get_groups($user->guid);

$id_activities_array = array();
foreach($my_groups_ids as $group_id){
    $id_activities_array[] = ClipitGroup::get_activity($group_id);
}

$my_activities = ClipitActivity::get_by_id($id_activities_array);
$params_progress = array(
    'value' => 30,
    'width' => '200px'
);
$progress_bar = elgg_view("page/components/progressbar", $params_progress);

$params_list = array(
    'items'         => $my_activities,
    'pagination'    => false,
    'progress_bar'  => $progress_bar,
    'list_class'    => 'my-activities',
    'full_view'     => false,
);
$content = elgg_view("page/components/activity/list", $params_list);

$params = array(
    'content' => $content,
    'title' => elgg_echo("my_activities"),
    'filter' => ''
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
