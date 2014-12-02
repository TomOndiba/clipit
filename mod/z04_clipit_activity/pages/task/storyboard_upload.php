<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   10/10/2014
 * Last update:     10/10/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$storyboards = ClipitGroup::get_storyboards($group_id);
$href_publications = "clipit_activity/{$activity->id}/publications";
$body = elgg_view('multimedia/storyboard/list', array(
    'entities'    => $storyboards,
    'href'      => "clipit_activity/{$activity->id}/group/{$group_id}/repository",
    'task_id'   => $task->id,
    'publish'   => true,
));
if(!$storyboards){
    $body = elgg_view('output/empty', array('value' => elgg_echo('task:storyboards:none', array(elgg_view('output/url',
        array(
            'href'=> "clipit_activity/{$activity->id}/group/{$group_id}/repository?filter=storyboards",
            'text' => elgg_echo('repository:group')
        )
    )))));
}
// Group id get parameter
if(get_input('group_id')){
    $group_id = get_input('group_id');
    $object = ClipitSite::lookup($group_id);
    $status = get_task_status($task, $group_id);
    $storyboard = array($status['result']);
    $super_title = $object['name'];
    if($status['status']){
        $body .= elgg_view('multimedia/storyboard/list', array(
            'entities'    => $storyboard,
            'href'      => $href_publications,
            'task_id'   => $task->id,
        ));
    } else {
        $body = elgg_view('output/empty', array('value' => elgg_echo('storyboards:none')));
    }
}
if($status['status'] === true || $task->end <= time()){
    $storyboard = array($status['result']);
    $body = elgg_view("page/components/title_block", array(
        'title' => elgg_echo("task:my_storyboard"),
    ));
    // Task is completed, show my sb
    if($status['status'] === true){
        $body .= elgg_view('multimedia/storyboard/list', array(
            'entities'    => $storyboard,
            'href'      => $href_publications,
            'task_id'   => $task->id,
        ));
    } else {
        $body = elgg_view('multimedia/storyboard/list', array(
            'entities'    => $storyboards,
            'href'      => "clipit_activity/{$activity->id}/group/{$group_id}/repository",
            'task_id'   => $task->id,
            'rating'    => false,
            'actions'   => false,
            'publish'   => true,
            'total_comments' => false,
        ));
    }
    // View other storyboards
    $body .= elgg_view("page/components/title_block", array(
        'title' => elgg_echo("task:other_storyboards"),
    ));
    if(($key = array_search($status['result'], $task->storyboard_array)) !== false) {
        unset($task->storyboard_array[$key]);
    }
    if($task->storyboard_array){
        $body .= elgg_view('multimedia/storyboard/list_summary', array(
            'entities'    => $task->storyboard_array,
            'href'      => $href_publications,
            'task_id'   => $task->id,
        ));
    } else {
        $body .= elgg_view('output/empty', array('value' => elgg_echo('storyboards:none')));
    }
}
// Teacher view
if($user->role == ClipitUser::ROLE_TEACHER){
    $storyboards = ClipitStoryboard::get_by_id($task->storyboard_array);
    if($storyboards){
        $body = elgg_view('tasks/admin/task_upload', array(
            'entities'    => $storyboards,
            'activity'      => $activity,
            'task'      => $task,
            'list_view' => 'multimedia/storyboard/list'
        ));
    } else {
        $body = elgg_view('output/empty', array('value' => elgg_echo('storyboards:none')));
    }
}