<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   24/07/14
 * Last update:     24/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entities = elgg_extract('entities', $vars);
$activity = elgg_extract('activity', $vars);
$task = elgg_extract('task', $vars);
$entity_type = elgg_extract('entity_type', $vars);
$list_view = elgg_extract('list_view', $vars);
$entities_ids = array();
foreach($entities as $entity_object) {
    $entities_ids[] = $entity_object->id;
}
$groups = ClipitGroup::get_by_id($activity->group_array, 0, 0, 'name');
?>
<style>
.multimedia-preview .img-preview{
    width: 65px;
    max-height: 65px;
}
.multimedia-preview img {
    width: 100%;
}
.task-status{
    display: none;
}
</style>
<script>
$(function(){
    $(document).on("click", "#panel-expand-all",function(){
        $(".expand").parent(".panel").find(".panel-collapse").collapse('show');
        $(".user-rating").click();
    });
    $(document).on("click", "#panel-collapse-all",function(){
        $(".expand").parent(".panel").find(".panel-collapse").collapse('hide');
    });
    $(document).on("click", ".user-rating",function(){
        var content = $(this).closest(".panel").find(".panel-body");
        var us_id = $(this).data("user");
        if(content.is(':empty')){
            content.html('<i class="fa fa-spinner fa-spin fa-2x blue"></i>');
            $.get( elgg.config.wwwroot+"ajax/view/publications/admin/user_ratings", {entities_ids: <?php echo json_encode($entities_ids);?>, user_id: us_id}, function( data ) {
                content.html(data);
            });
        }
    });
    var container = $("#students");
    elgg.get("ajax/view/tasks/admin/feedback_data", {
        dataType: "json",
        data: {
            type: '<?php echo $entity_type;?>',
            task: <?php echo $task->id;?>,
            activity: <?php echo $activity->id;?>,
        },
        success: function (output) {
            $.each(output, function (i, data) {
                container.find("[data-entity="+data.entity+"] .count").text(data.count);
            });
        }
    });
    var hash = window.location.hash.replace('#', '');
    var collapse = $("[href='#user_"+hash+"']");
    if(collapse.length > 0){
        collapse.click();
    }

});
</script>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#students" aria-controls="students" role="tab" data-toggle="tab"><?php echo elgg_echo('students');?></a>
        </li>
        <li role="presentation">
            <a href="#items" role="tab" data-toggle="tab">
                <?php echo elgg_echo($entity_type);?>
                (<?php echo count($entities);?>)
            </a>
        </li>
    </ul>
</div>
<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="students" style="padding: 10px;">
    <p>
        <?php echo elgg_view('output/url', array(
            'title' => elgg_echo('expand:all'),
            'text' => elgg_echo('expand:all'),
            'href' => "javascript:;",
            'id' => 'panel-expand-all',
        ));
        ?>
        <span class="text-muted">|</span>
        <?php echo elgg_view('output/url', array(
            'title' => elgg_echo('collapse:all'),
            'text' => elgg_echo('collapse:all'),
            'href' => "javascript:;",
            'id' => 'panel-collapse-all',
        ));
        ?>
    </p>
    <?php
        foreach($groups as $group):
            $users = ClipitUser::get_by_id($group->user_array, 0, 0, 'name');
    ?>
    <h3 class="title-block">
        <?php echo $group->name;?>
    </h3>
    <ul class="panel-group" id="accordion_users">
        <?php
        foreach($users as $user):
            $status = ClipitTask::get_completed_status($task->id, $user->id);
        ?>
        <li class="panel panel-blue list-item" data-entity="<?php echo $user->id;?>">
            <a name="<?php echo $user->id;?>"></a>
            <div class="panel-heading expand" style="padding: 0px;background: none;">
                <div class="pull-right blue">
                    <span class="text-muted margin-right-10 count">-/-</span>
                    <span style="width: 14px;" class="inline-block">
                        <?php echo elgg_view('tasks/icon_entity_status', array('status' => $status));?>
                    </span>
                    <a data-toggle="collapse"
                       data-parent="#accordion_users"
                       href="#user_<?php echo $user->id;?>"
                       class="btn btn-border-blue margin-left-10 btn-xs btn-primary user-rating"
                       data-user="<?php echo $user->id;?>"
                        >
                        <?php echo elgg_echo('view');?>
                    </a>
                </div>
                <?php echo elgg_view("page/elements/user_block", array("entity" => $user)); ?>
            </div>
            <div class="clearfix"></div>
            <div id="user_<?php echo $user->id;?>" class="panel-collapse collapse">
                <div class="panel-body"></div>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
        <?php endforeach;?>
    </div>
    <div role="tabpanel" class="tab-pane margin-top-10" id="items" style="padding: 10px;">
        <?php
        echo elgg_view($list_view, array(
            'entities'    => $entities_ids,
            'href'      => "clipit_activity/{$activity->id}/publications",
        ));
        ?>
    </div>
</div>