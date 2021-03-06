<?php
 /**
 * Clipit Web Space
 * PHP version:     >= 5.2
 * Creation date:   4/07/14
 * Last update:     4/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC Clipit Project
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 */

function publications_get_page_content_list($task_type, $tasks, $href){
    $entity_tasks = array();
    foreach($tasks as $task_id){
        $task = array_pop(ClipitTask::get_by_id(array($task_id)));
        if($task->task_type == $task_type && $task->status != ClipitTask::STATUS_LOCKED){
            $task_entity[] = $task->id;
            $entity_tasks[$task->id] = $task->name ." [".date("d M Y", $task->start)." - ".date("d M Y", $task->end)."]";
        }
    }
    $last_task_id = reset($task_entity);
    $get_task = get_input('task_id', $last_task_id);
    $task = array_pop(ClipitTask::get_by_id(array($get_task)));
    $user = array_pop(ClipitUser::get_by_id(array(elgg_get_logged_in_user_guid())));
    $send_to_site = false;
    $unlink = false;
    if($task->status == ClipitTask::STATUS_ACTIVE){
        $unlink = true;
    }
    if(hasTeacherAccess($user->role)){
        $send_to_site = true;
    }
    switch($task_type){
        case ClipitTask::TYPE_VIDEO_UPLOAD:
            $view = 'multimedia/video/list';
            $entities = $task->video_array;
            $none_msg = elgg_echo('videos:none');

            // Search items
            if($search_term = stripslashes(get_input("search"))){
                $items_search = array_keys(ClipitVideo::get_from_search($search_term));
                $entities = array_uintersect($items_search, $entities, "strcasecmp");
            }
            elgg_extend_view("videos/search", "search/search");
            break;
        case ClipitTask::TYPE_FILE_UPLOAD:
            $view = 'multimedia/file/list';
            $entities = $task->file_array;
            $none_msg = elgg_echo('files:none');

            // Search items
            if($search_term = stripslashes(get_input("search"))){
                $items_search = array_keys(ClipitFile::get_from_search($search_term));
                $entities = array_uintersect($items_search, $entities, "strcasecmp");
            }
            elgg_extend_view("files/search", "search/search");
            break;
    }

    $content = elgg_view('tasks/select', array('entities' => $entity_tasks, 'entity' => $task));

    $content .= elgg_view($view, array(
        'entities'    => $entities,
        'options' => false,
        'href'      => $href,
        'rating'    => true,
        'preview' => false,
        'send_site' => $send_to_site,
        'actions'   => false,
        'unlink' => $unlink,
        'total_comments' => true,
    ));
    if (!$entities) {
        $content .= elgg_view('output/empty', array('value' => $none_msg));
    }
    return $content;
}