<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   14/05/14
 * Last update:     14/05/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$id = (int)get_input("id");
$user_id = elgg_get_logged_in_user_guid();
$user = array_pop(ClipitUser::get_by_id(array($user_id)));
$video = array_pop(ClipitVideo::get_by_id(array($id)));

if($video && $video->owner_id == $user_id || $user->role == ClipitUser::ROLE_TEACHER){
    $activity_id = ClipitVideo::get_activity($video->id);
    $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
    $body = elgg_view("page/components/modal",
        array(
            "dialog_class" => "modal-md",
            "remote" => true,
            "target" => "publish-{$video->id}",
            "title" => elgg_echo("publish"),
            "form" => true,
            "body" => elgg_view('forms/multimedia/videos/save',
                array(
                    'send_site' => true,
                    'entity'  => $video,
                    'publish' => true,
                    'tags' => $video->tag_array,
                    'parent_id' => $activity_id,
                )),
            "cancel_button" => true,
            "ok_button" => elgg_view('input/submit',
                array(
                    'value' => elgg_echo('send'),
                    'class' => "btn btn-primary"
                ))
        ));
    echo elgg_view_form('multimedia/videos/edit', array('action' => 'action/publications/publish', 'body' => $body, 'data-validate'=> "true"));
}