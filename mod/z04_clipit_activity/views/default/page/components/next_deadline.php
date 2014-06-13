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
$group = elgg_extract('entity', $vars);
$activity_id = ClipitGroup::get_activity($group->id);
$activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
?>
    <small class="show" style="margin-top: 5px;"><?php echo elgg_echo("activity:actual_deadline")?></small>
<?php
$task_found = false;
foreach(ClipitTask::get_by_id($activity->task_array) as $task):
    if($task->start <= time() && $task->end >= time()):
        $task_found = true;
        $status = get_task_status($task);
?>
    <div>
        <small class="pull-right">
            <?php echo elgg_view('output/friendlytime', array('time' => $task->end));?>
        </small>
        <?php echo $status['icon'];?>
            <?php echo elgg_view('output/url', array(
                'href'  => "clipit_activity/{$task->activity}/tasks/view/{$task->id}",
                'title' => $task->name,
                'text'  => $status['count']." <strong>".$task->name."</strong>",
            ));
            ?>
    </div>
<?php
    endif;
endforeach;

if(!$task_found): ?>
    <small><strong><?php echo elgg_echo('task:not_actual');?></strong></small>
<?php endif; ?>