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

function view_recommended_event($event, $view_type = 'full'){
    $relationship = get_relationship($event->object_id);
    switch($relationship->relationship){
        case "activity-video":
            $activity = array_pop(ClipitActivity::get_by_id(array($relationship->guid_one)));
            $entity = array_pop(ClipitVideo::get_by_id(array($relationship->guid_two)));
            $href = "clipit_activity/{$activity->id}/publications";
            $params = array(
                'title' => 'Added new video to activity',
                'icon' => 'fa-video-camera',
                'author' => ClipitVideo::get_group($relationship->guid_two),
                'body' => elgg_view("recommended/events/video", array('entity' => $entity, 'href' => $href, 'rating' => true))
            );
            break;
        case "group-video":
            $activity_id = ClipitGroup::get_activity($relationship->guid_one);
            $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
            $entity = array_pop(ClipitVideo::get_by_id(array($relationship->guid_two)));
            $href = "clipit_activity/{$activity->id}/group/multimedia";
            $params = array(
                'title' => 'Added new video to group',
                'icon' => 'fa-video-camera',
                'author' => $entity->owner_id,
                'body' => elgg_view("recommended/events/video", array('entity' => $entity, 'href' => $href, 'rating' => false))
            );
            break;
        case "group-file":
            $activity_id = ClipitGroup::get_activity($relationship->guid_one);
            $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
            $entity = array_pop(ClipitFile::get_by_id(array($relationship->guid_two)));
            $href = "clipit_activity/{$activity->id}/group/multimedia/view/{$entity->id}";
            $params = array(
                'title' => 'Added new file to group',
                'icon' => 'fa-file',
                'author' => $entity->owner_id,
                'body' => elgg_view("recommended/events/file", array(
                    'entity' => $entity,
                    'href' => $href,
                    'image' => elgg_view("multimedia/file/preview", array('file'  => $entity)
                    )))
            );
            break;
        case "message-destination":
            // Message from group|activity
            $object = ClipitSite::lookup($relationship->guid_two);
            switch($object['subtype']){
                case "ClipitGroup":
                    $activity_id = ClipitGroup::get_activity($relationship->guid_two);
                    break;
                case "ClipitActivity":
                    $activity_id = $relationship->guid_two;
                    break;
            }
            $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
            $entity = array_pop(ClipitPost::get_by_id(array($relationship->guid_one)));
            $href = "clipit_activity/{$activity->id}/group/discussion/view/{$entity->id}";
            $params = array(
                'title' => 'Added a new discussion topic',
                'icon' => 'fa-comment',
                'author' => $entity->owner_id,
                'body' => elgg_view("recommended/events/discussion", array('entity' => $entity,'href' => $href,))
            );
            break;
        case "group-user":
            $activity_id = ClipitGroup::get_activity($relationship->guid_one);
            $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
            $group = array_pop(ClipitGroup::get_by_id(array($relationship->guid_one)));
            $entity = array_pop(ClipitUser::get_by_id(array($relationship->guid_two)));
            $group_info = elgg_view('output/url', array(
                'href'  => "clipit_activity/{$activity->id}/group",
                'title' => $group->name,
                'text'  => $group->name,
            ));
            $params = array(
                'title' => 'joined the group <strong>'.$group_info.'</strong>',
                'icon' => 'fa-user',
                'author' => $entity->id,
                'body' => ''
            );
            break;
    }
    // Output
    if($entity && $activity){
        switch($view_type){
            case "full":
                return elgg_view("recommended/events/section", array_merge(
                    $params,
                    array(
                        'time_created' => $relationship->time_created,
                        'activity' => $activity
                    )
                ));
                break;
            case "simple":
                return elgg_view("recommended/events/section_simple", array_merge(
                    $params,
                    array(
                        'time_created' => $relationship->time_created,
                        'activity' => $activity
                    )
                ));
                break;
        }

    }
}
