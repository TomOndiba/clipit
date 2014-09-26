<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   28/05/14
 * Last update:     28/05/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$title = get_input("title");
$description = get_input("description");
$labels = get_input("labels");
$labels = array_filter(explode(",", $labels));
$tags = get_input("tags");
$performance_items = get_input("performance_items");
$entity_id = get_input("entity-id");
$task_id = get_input("task-id");
$parent_id = get_input("parent-id");
// for trial
$performance_items = array();
$rubrics_concept = ClipitPerformanceItem::get_by_category('Concepto');
$rubrics_audiovisual = ClipitPerformanceItem::get_by_category('Audiovisual');


$object = ClipitSite::lookup($entity_id);
$entity_class = $object['subtype'];

$parent_object = ClipitSite::lookup($parent_id);
$parent_entity_class = $parent_object['subtype'];

switch($parent_entity_class){
    // Clipit Activity
    case 'ClipitActivity':
        $entity_level_class = "ClipitActivity";
        $parent_id = array_pop(ClipitActivity::get_by_id(array($parent_id)));
        $href = "clipit_activity/{$parent_id}/publications";
        break;
    // Clipit Group
    case 'ClipitGroup':
        $entity_level_class = "ClipitActivity";
        $parent_id = ClipitGroup::get_activity($parent_id);
        $href = "clipit_activity/{$parent_id}/tasks/view/{$task_id}";
        break;
    default:
        register_error(elgg_echo("video:cantadd"));
        break;
}

$entity = array_pop($entity_class::get_by_id(array($entity_id)));
if(count($entity)==0 || trim($title) == "" || trim($description) == ""){
    register_error(elgg_echo("cantpublish"));
} else{
    // Clone
    $new_entity_id = $entity_class::create_clone($entity_id);

    $entity_class::set_properties($new_entity_id, array(
        'name' => $title,
        'description' => $description
    ));
    // Labels
    $total_labels = array();
    foreach($labels as $label){
        if($label_exist = array_pop(ClipitLabel::get_from_search($label, true, true))){
            $total_labels[] = $label_exist->id;
        } else {
            $total_labels[] = ClipitLabel::create(array(
                'name'    => $label,
            ));
        }
    }
    $entity_class::set_labels($new_entity_id, $total_labels);
    // Tags
    $entity_class::set_tags($new_entity_id, $tags);

    if($new_entity_id){
        switch($entity_class){
            case "ClipitVideo":
                ClipitTask::add_videos($task_id, array($new_entity_id));
                $rubrics = array_merge($rubrics_audiovisual, $rubrics_concept);
                foreach($rubrics as $rubric){
                    $performance_items[] = $rubric->id;
                }
                break;
            case "ClipitResource":
                ClipitTask::add_resources($task_id, array($new_entity_id));
                $rubrics = array_merge($rubrics_audiovisual, $rubrics_concept);
                foreach($rubrics as $rubric){
                    $performance_items[] = $rubric->id;
                }
                break;
            case "ClipitStoryboard":
                ClipitTask::add_storyboards($task_id, array($new_entity_id));
                $rubrics = array_merge($rubrics_concept);
                foreach($rubrics as $rubric){
                    $performance_items[] = $rubric->id;
                }
                break;
            default:
                register_error(elgg_echo("cantpublish"));
                break;
        }
        // Performance items
        $entity_class::set_performance_items($new_entity_id, $performance_items);
    } else {
        register_error(elgg_echo("cantpublish"));
    }
    system_message(elgg_echo('published'));
}
forward($href);