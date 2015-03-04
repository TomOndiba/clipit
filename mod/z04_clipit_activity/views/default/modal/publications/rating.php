<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   21/05/14
 * Last update:     21/05/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$id = (int)get_input("id");
$by_target_id = (int)get_input("by_target");

if($id){
    $rating = array_pop(ClipitRating::get_by_id(array($id)));
    $group_id = (int)get_input("group_id");
    if($group_id){
        $group = array_pop(ClipitGroup::get_by_id(array($group_id)));
        $body = '<div style="margin-bottom: 10px;">
                    <span class="label label-blue"><i class="fa fa-users"></i> '.$group->name.'</span>
                 </div>';
    }
    $body .= elgg_view('performance_items/full', array('entity'  => $rating));
    $user = array_pop(ClipitUser::get_by_id(array($rating->owner_id)));
    echo elgg_view("page/components/modal",
        array(
            "dialog_class"     => "modal-lg",
            "remote"    => true,
            "target"    => "rating-average-{$rating->id}",
            "title"     => elgg_echo("publications:rating:name", array($user->name)),
            "form"      => false,
            "body"      => $body,
            "footer"    => false
        ));

} elseif($by_target_id){
    $rating_target = array_pop(ClipitRating::get_by_target(array($by_target_id)));
    $activity_id = (int)get_input("activiy_id");
    $body = elgg_view('performance_items/list', array('entity'  => $rating_target, 'activity_id' => $activity_id));
    echo elgg_view("page/components/modal",
        array(
            "dialog_class"     => "modal-lg",
            "remote"    => true,
            "target"    => "rating-list-{$rating_target->id}",
            "title"     => elgg_echo("publications:rating:list"),
            "form"      => false,
            "body"      => $body,
            "footer"    => false
        ));
}