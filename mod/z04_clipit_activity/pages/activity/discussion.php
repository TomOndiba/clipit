<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   8/09/14
 * Last update:     8/09/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$title = elgg_echo("activity:discussion");
$href = "clipit_activity/{$activity->id}/discussion";
elgg_push_breadcrumb($title);
$messages = array_pop(ClipitPost::get_by_destination(array($activity->id)));
$canCreate = false;
if( ($access == 'ACCESS_TEACHER' || $access == 'ACCESS_MEMBER' || in_array($user_id, $activity->student_array)) && $activity_status != 'closed'){
    $canCreate = true;
}
$attach_multimedia = false;
if($user->role == ClipitUser::ROLE_TEACHER){
    $attach_multimedia = true;
}
$content =  elgg_view('discussion/list',
    array(
        'entity' => $activity,
        'messages' => $messages,
        'href'   => $href,
        'attach_multimedia' => $attach_multimedia,
        'create' => $canCreate
    ));
if(!$messages){
    $content .= elgg_view('output/empty', array('value' => elgg_echo('discussions:none')));
}
if($page[2] == 'view' && $page[3]){
    $message_id = (int)$page[3];
    $message = array_pop(ClipitPost::get_by_id(array($message_id)));
    elgg_pop_breadcrumb($title);
    elgg_push_breadcrumb($title, $href);
    elgg_push_breadcrumb($message->name);
    if($message && $message->destination == $activity->id){
        $href_multimedia = "clipit_activity/{$activity->id}/resources/view";
        $content = elgg_view('discussion/view',
            array(
                'entity'     => $message,
                'activity_id'   => $activity->id,
                'href_multimedia' => $href_multimedia,
                'show_group' => true,
            ));
    } else {
        return false;
    }
}
$params = array(
    'content'   => $content,
    'filter'    => '',
    'title'     => $title,
    'sub-title' => $activity->name,
    'title_style' => "background: #". $activity->color,
);