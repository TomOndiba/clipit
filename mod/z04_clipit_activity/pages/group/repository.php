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
$title = elgg_echo("group:files");
elgg_push_breadcrumb($title);
$selected_tab = get_input('filter', 'files');
$href = "clipit_activity/{$activity->id}/group/{$group->id}/repository";
$entity_class = "ClipitGroup";
$entity = $group;
elgg_set_context("group");
if(!$page[4]) {
    switch ($selected_tab) {
        case 'files':
            $files = ClipitGroup::get_files($group->id);
            $params = array(
                'entity' => $group,
                'add_files' => true,
                'files' => $files,
                'href' => $href,
                'create' => $canCreate
            );
            $content = files_get_page_content_list($params);
            break;
        case 'videos':
            $videos = ClipitGroup::get_videos($group->id);
            $params = array(
                'entity' => $group,
                'add_video' => true,
                'entities' => $videos,
                'actions' => true,
                'href' => $href,
                'create' => $canCreate
            );
            $content = videos_get_page_content_list($params);
            break;
        case 'resources':
            $resources = ClipitGroup::get_resources($group->id);
            $params = array(
                'entity' => $group,
                'add_resource' => true,
                'entities' => $resources,
                'actions' => true,
                'href' => $href,
                'create' => $canCreate
            );
            $content = resources_get_page_content_list($params);
            break;
        case 'storyboards':
            $sbs = ClipitGroup::get_storyboards($group->id);
            $params = array(
                'entity' => $group,
                'add_sb' => true,
                'entities' => $sbs,
                'href' => $href,
                'create' => $canCreate,
                'actions' => true
            );
            $content = storyboards_get_page_content_list($params);
            break;
        default:
            return false;
            break;
    }
}
$filter = elgg_view('multimedia/filter', array('selected' => $selected_tab, 'entity' => $group, 'href' => $href));

if($page[4] == 'download' && $page[5]){
    $file_dir = elgg_get_plugins_path() . 'z04_clipit_activity/pages/file';
    set_input('id', $page[4]);
    include "$file_dir/download.php";
}
if($page[4]){
    $entity_id = (int)$page[5];
    switch($page[4]){
        case "view":
            include elgg_get_plugins_path() . 'z04_clipit_activity/pages/multimedia/view.php';
            break;
        case "publish":
            include elgg_get_plugins_path() . 'z04_clipit_activity/pages/multimedia/publish.php';
            break;
    }
}

$params = array(
    'content'   => $content,
    'filter'    => $filter,
    'title'     => $title,
);