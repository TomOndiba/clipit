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
elgg_pop_breadcrumb($title);
elgg_push_breadcrumb($title, $href);
$object = ClipitSite::lookup($entity_id);
$filter = "";
if($task_id = get_input('task_id')){
    $object['subtype']::set_read_status($entity_id, true, array(elgg_get_logged_in_user_guid()));
}
switch($object['subtype']){
    // Clipit File
    case 'ClipitFile':
        elgg_push_breadcrumb(elgg_echo("files"), $href."?filter=files");
        $title = elgg_echo("file");
        $entity = array_pop(ClipitFile::get_by_id(array($entity_id)));
        $content = elgg_view('multimedia/view', array(
            'entity' => $entity,
            'type' => 'file',
            'preview' => elgg_view("multimedia/file/preview", array('file'  => $entity)),
            'body' => elgg_view("multimedia/file/body", array('entity'  => $entity))
        ));
        break;
    // Clipit Video
    case 'ClipitVideo':
        elgg_push_breadcrumb(elgg_echo("videos"), $href."?filter=videos");
        $title = elgg_echo("video");
        $entity = array_pop(ClipitVideo::get_by_id(array($entity_id)));
        $content = elgg_view('multimedia/view', array(
            'entity' => $entity,
            'type' => 'video',
            'preview' => elgg_view("multimedia/video/preview", array('entity'  => $entity)),
            'body' => elgg_view("multimedia/video/body", array('entity'  => $entity))
        ));
        break;
    // Clipit Resource
    case 'ClipitResource':
        elgg_push_breadcrumb(elgg_echo("resources"), $href."?filter=resources");
        $title = elgg_echo("resource");
        $entity = array_pop(ClipitResource::get_by_id(array($entity_id)));
        $content = elgg_view('multimedia/view', array(
            'entity' => $entity,
            'type' => 'resource',
            'preview' => false,
            'body' => elgg_view("multimedia/body", array('entity'  => $entity)),
            'description' => false,
        ));
        $content = elgg_view("multimedia/resource", array('entity_id' => $entity_id));
        break;
    default:
        return false;
        break;
}

elgg_push_breadcrumb($entity->name);