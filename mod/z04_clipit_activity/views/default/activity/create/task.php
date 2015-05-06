<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   7/07/14
 * Last update:     7/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$task_type = elgg_extract('task_type', $vars);
$id = elgg_extract('id', $vars);
$task = elgg_extract('task', $vars); // task data
$show_performance_items = false;

switch($task_type){
    case "upload":
        $task_types = array(
            ClipitTask::TYPE_QUIZ_TAKE => elgg_echo('task:quiz_answer'),
            ClipitTask::TYPE_VIDEO_UPLOAD => elgg_echo('task:video_upload'),
            ClipitTask::TYPE_STORYBOARD_UPLOAD => elgg_echo('task:storyboard_upload'),
            ClipitTask::TYPE_RESOURCE_DOWNLOAD => elgg_echo('task:resource_download'),
            ClipitTask::TYPE_OTHER => elgg_echo('task:other')
        );
        $input_array = "[{$id}]";
        $disabled = false;
        break;
    case "feedback":
        $task_types = array(
            ClipitTask::TYPE_VIDEO_FEEDBACK => elgg_echo('task:video_feedback'),
            ClipitTask::TYPE_STORYBOARD_FEEDBACK => elgg_echo('task:storyboard_feedback')
        );
        $input_array = "[{$id}][feedback-form]";
        $disabled = true;
        if(get_config('fixed_performance_rating')){
            $show_performance_items = true;
        }
        break;
    case "download":
        $task_types = array(
            ClipitTask::TYPE_RESOURCE_DOWNLOAD => elgg_echo('task:resource_download'),
        );
        $input_array = "[{$id}]";
        $disabled = false;
        break;
}
$task_types = array_merge(array('' => elgg_echo('task:select:task_type')), $task_types);
if($vars['required'] !== false){
    $required = true;
}
?>
<div class="col-mds-12">
    <?php if(!$disabled && !$task && $vars['delete_task']!==false):?>
        <i class="delete-task fa fa-times red pull-left margin-top-5" style="cursor: pointer" onclick="javascript:$(this).closest('.task').remove();"></i>
    <?php endif;?>
<!--    <div class="content-block">-->
    <div class="pull-left">
        <div class="col-md-5">
            <div class="form-group">
                <label for="task-title"><?php echo elgg_echo("task:title");?></label>
                <?php echo elgg_view("input/text", array(
                    'name' => "task{$input_array}[title]",
                    'class' => 'form-control input-task-title',
                    'value' => $task->name,
                    'required' => $required
                ));
                ?>
            </div>
        </div>
        <div class="col-md-3">
            <label for="task-type"><?php echo elgg_echo("task:task_type");?></label>
            <?php echo elgg_view('input/dropdown', array(
                'name' => "task{$input_array}[type]",
                'class' => 'form-control task-types',
                'style' => 'padding-top: 5px;padding-bottom: 5px;',
                'required' => $required,
                'disabled' => $task->task_type ? true : $disabled,
                'value' => $vars['default_task'] ? $vars['default_task'] : $task->task_type,
                'options_values' => $task_types
            ));
            ?>
            <?php if($disabled):?>
                <?php echo elgg_view("input/hidden", array(
                    'name' => "task{$input_array}[type]",
                    'class' => 'form-control task-types',
                    'value' => $vars['default_task'] ? $vars['default_task'] : false,
                ));
                ?>
            <?php endif;?>
        </div>
        <div class="col-md-2">
            <label for="task-start"><?php echo elgg_echo("start");?></label>
            <?php echo elgg_view("input/text", array(
                'name' => "task{$input_array}[start]",
                'class' => 'form-control datepicker input-task-start',
                'value' => $task->start ? date("d/m/y H:i", $task->start) : "",
                'required' => $required,
                'readonly' => true
            ));
            ?>
        </div>
        <div class="col-md-2">
            <label for="task-end"><?php echo elgg_echo("end");?></label>
            <?php echo elgg_view("input/text", array(
                'name' => "task{$input_array}[end]",
                'class' => 'form-control datepicker input-task-end',
                'value' => $task->end ? date("d/m/y H:i", $task->end) : "",
                'required' => $required,
                'readonly' => true
            ));
            ?>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="task-description"><?php echo elgg_echo("description");?></label>
                <?php echo elgg_view("input/plaintext", array(
                    'name' => "task{$input_array}[description]",
                    'class' => 'form-control',
                    'value' => $task->description,
                    'rows' => $task->description ? 5:1,
                    'onclick' => 'javascript:this.rows=5;'
                ));
                ?>
            </div>
        </div>
        <?php
            if($show_performance_items  ||
                ($task
                && ($task->task_type == ClipitTask::TYPE_STORYBOARD_FEEDBACK || $task->task_type == ClipitTask::TYPE_VIDEO_FEEDBACK)
                && get_config('fixed_performance_rating'))
            ):
            $performance_items = array();
            if($task){
                $performance_items = $task->performance_item_array;
            }
//
//            if($task &&
//                ($task->task_type == ClipitTask::TYPE_STORYBOARD_FEEDBACK ||
//                $task->task_type == ClipitTask::TYPE_VIDEO_FEEDBACK)
//                && get_config('fixed_performance_rating')
//            ):
        ?>
            <script>$(function(){$(".chosen-select-items").chosen();});</script>
            <style>
                .chosen-container{width: 100% !important;}
                /*.chosen-container .chosen-drop{position: relative;display: none;}*/
                /*.chosen-with-drop .chosen-drop{display: block;}*/
            </style>
            <div class="col-md-4" style="margin-bottom: 50px;">
                <label><?php echo elgg_echo("performance_items");?></label>
                <select name="<?php echo "task{$input_array}[performance_items][]";?>" data-placeholder="<?php echo elgg_echo('click_add');?>" style="width:100%;" multiple class="chosen-select-items" tabindex="8">
                    <option value=""></option>
                    <?php foreach(ClipitPerformanceItem::get_from_category(null) as $category => $items):?>
                        <optgroup label="<?php echo $category; ?>">
                            <?php foreach($items as $item): ?>
                                <option <?php echo in_array($item->id, $performance_items) ? "selected" : "";?> value="<?php echo $item->id; ?>">
                                    <?php echo $item->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif;?>

        <?php if(!$disabled):?>
            <div class="col-md-4 feedback-module" style="<?php echo $vars['feedback_check'] != false ? '' : 'display: none;'; ?>padding: 10px;background: #fafafa;">
                <label for="activity-title"><?php echo elgg_echo("task:feedback");?></label>
                <div class="checkbox feedback-check">
                    <label>
                        <input type="checkbox" value="1" name="<?php echo "task{$input_array}[feedback]";?>"> <?php echo elgg_echo("task:feedback:check");?>
                    </label>
                </div>
            </div>
        <?php endif;?>
    </div>
    <?php if(!$disabled):?>
        <?php echo elgg_view("input/hidden", array(
            'name' => 'input_prefix',
            'value' => "task{$input_array}",
        ));
        ?>
        <div class="clearfix"></div>
        <div class="task-type-container margin-bottom-10"
             style="<?php echo $task->task_type == ClipitTask::TYPE_QUIZ_TAKE ? '':'display:none;' ;?>
                 border: 1px solid #bae6f6;border-radius: 3px;padding: 10px;">
            <?php if($task):
                switch($task->task_type){
                    case ClipitTask::TYPE_QUIZ_TAKE:
                        echo elgg_view('activity/admin/tasks/quiz/quiz', array(
                            'entity' => array_pop(ClipitQuiz::get_by_id(array($task->quiz))),
                            'activity_id' => $task->activity,
                            'input_prefix' => "task{$input_array}"
                        ));
                        break;
                }
            endif;
            ?>
        </div>
    <?php endif;?>
</div>
<?php
if(!$disabled):
    $attach = $vars['entity'];
    $attach['id'] = $id;
?>
    <?php if($attach['selected']):?>
        <script>
            $(function(){
                $("[data-attach='<?php echo $attach['id'];?>']").attach_multimedia({
                    default_list: "files",
                    data:{
                        list: $(this).data("menu"),
                        entity_id: "<?php echo $task->activity;?>",
                        selected: <?php echo $attach['selected'];?>
                    }
                }).loadAll();
            });
        </script>
    <?php endif;?>

    <?php echo elgg_view("multimedia/attach/list", $attach);?>

<!--    <ul class="nav nav-tabs margin-bottom-5 margin-top-10" style="display: none;">-->
<!--        <li class="active"><a href="#activity-multimedia" data-toggle="tab">Materiales de la actividad</a></li>-->
<!--        <li><a href="#tricky-topic-multimedia" data-toggle="tab">Materiales del tema clave</a></li>-->
<!--    </ul>-->
<!--    <div class="attach-multimedia tab-content">-->
<!--        <div id="activity-multimedia" class="tab-pane active">-->
<!--            --><?php //echo elgg_view("multimedia/attach/list", array_merge($attach, array('class' => 'multimedia-activity')));?>
<!--        </div>-->
<!--        <div id="tricky-topic-multimedia" class="tab-pane">-->
<!--            --><?php //echo elgg_view("multimedia/attach/list", array('id' => $id.time(), 'class' => 'multimedia-tt'));?>
<!--        </div>-->
<!--    </div>-->

<?php endif;?>