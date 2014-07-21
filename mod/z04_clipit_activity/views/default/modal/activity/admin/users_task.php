<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   18/07/14
 * Last update:     18/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$task_id = get_input('id');
$group_id = get_input('group_id');

$users_id = ClipitGroup::get_users($group_id);
$body = '<ul>';
foreach($users_id as $user_id){
    $user = array_pop(ClipitUser::get_by_id(array($user_id)));
    if(ClipitTask::get_completed_status($task_id, $user_id)){
        // Task completed
        $completed .= '
            <li class="list-item">
                '.elgg_view("page/elements/user_block", array("entity" => $user, 'mail' => false)).'
            </li>';
    } else {
        // Task not completed
        $not_completed .= '
            <li class="list-item">
                '.elgg_view("page/elements/user_block", array("entity" => $user, 'mail' => false)).'
            </li>';
    }
}
$body .='</ul>';
$body .= '<h4><i class="fa fa-check green"></i> '.elgg_echo('task:completed').'</h4><hr class="margin-0 margin-bottom-10">';
if($completed){
    $body .= $completed;
} else {
    $body .= elgg_view('output/empty', array('value' => elgg_echo('users:none')));
}
$body .= '<h4><i class="fa fa-times red"></i> '.elgg_echo('task:not_completed').'</h4><hr class="margin-0 margin-bottom-10">';
if($not_completed){
    $body .= $not_completed;
} else {
    $body .= elgg_view('output/empty', array('value' => elgg_echo('users:none')));
}

$object = ClipitSite::lookup($task_id);
// modal remote
echo elgg_view("page/components/modal",
    array(
        "dialog_class"     => "modal-md",
        "remote"    => true,
        "target"    => "users-task-{$task_id}-{$group_id}",
        "title"     => elgg_echo('activity:task').$object['name'],
        "form"      => false,
        "body"      => $body,
        "footer"    => false
    ));
?>