<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 26/02/14
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */

$activity_id = elgg_get_page_owner_guid();
$user_id = elgg_get_logged_in_user_guid();
$group_id = ClipitGroup::get_from_user_activity($user_id, $activity_id);
$group = array_pop(ClipitGroup::get_by_id(array($group_id)));
$activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));

if($activity->status == ClipitActivity::STATUS_ENROLL && $activity->group_mode == ClipitActivity::GROUP_MODE_STUDENT){
    $header = elgg_view_form('group/leave',
        array('class'   => 'pull-right'),
        array('entity'  => $group)
    );
}
$header .= elgg_view('output/url', array(
    'href'  => "clipit_activity/{$activity_id}/group/{$group->id}",
    'text'  => "<h3>{$group->name}</h3>",
    'title' => $group->name,
    'class' => 'text-truncate',
));

$body .= "<small>".elgg_echo('group:progress')."</small>";
$params_progress = array(
    'value' => get_group_progress($group->id),
    'width' => '100%',
);
$body .= elgg_view("page/components/progressbar", $params_progress);
// Module view
echo elgg_view('page/components/module', array(
    'header' => $header,
    'body' => $body,
    'class' => 'activity-group-block',
));
?>