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
$activity = elgg_extract('activity', $vars);
$task = elgg_extract('task', $vars);
$quiz = elgg_extract('quiz', $vars);
$entities_ids = array_keys($entities);
$users = elgg_extract('entities', $vars);
$users = ClipitUser::get_by_id($users);
$groups = ClipitActivity::get_groups($activity->id);
elgg_load_js('jquery:chartjs');
?>
<script>
    $(function(){
        $(".show-chart").click(
            {quiz: <?php echo $quiz->id;?>},
            clipit.task.admin.quiz.showChart
        );
        $(".show-data").click(
            {quiz: <?php echo $quiz->id;?>},
            clipit.task.admin.quiz.showData
        );
        $('a[data-toggle="tab"]').on('shown.bs.tab',
            {quiz: <?php echo $quiz->id;?>},
            clipit.task.admin.quiz.onShowTab
        );
        // Start first tab
        $('a[data-toggle="tab"]:first').tab('show');
    });
</script>
<div>
    <small><?php echo elgg_echo('quiz:name');?></small>
    <h4 style="margin-top: 0;"><?php echo $quiz->name;?></h4>
</div>
<hr>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation">
            <a href="#students" aria-controls="profile" role="tab" data-toggle="tab"><?php echo elgg_echo('students');?></a>
        </li>
        <?php if($groups):?>
        <li role="presentation">
            <a href="#groups" aria-controls="groups" role="tab" data-toggle="tab"><?php echo elgg_echo('groups');?></a>
        </li>
        <?php endif;?>
        <li role="presentation">
            <a href="#activity" aria-controls="activity" role="tab" data-toggle="tab"><?php echo elgg_echo('activity');?></a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane margin-top-10 active" id="students" style="padding: 10px;">
          <?php
          elgg_push_context('quizstudents');
            $params = array(
            'filter' => '',
            'num_columns' => 1,
            );
            echo "<div class=\"learning_analytics_dashboard\">";
            echo elgg_view_layout('la_widgets_quizresults', $params);
            echo "</div>";
            elgg_pop_context();
            ?>
            <ul>
            <?php
                $students = ClipitUser::get_by_id($activity->student_array);
                foreach($students as $student):
            ?>
                <li class="list-item" data-entity="<?php echo $student->id;?>">
                    <div class="pull-right">
                        <div class="margin-right-10 inline-block status text-muted">
                            <small class="msg-not-finished"></small>
                            <div class="counts " style="display: none;">
                                <span class="answered"></span> -
                                <i class="fa fa-check green"></i> <strong class="a-correct">-</strong>
                            </div>
                        </div>
                        <span class="pull-right">
                            <a href="#questions-<?php echo $student->id;?>"
                               class="show-data btn-primary btn btn-xs btn-icon fa-list fa btn-border-blue"
                               data-type="student"
                               data-entity-type="questions"
                               data-toggle="collapse"
                                ></a>
                            <a href="#chart-<?php echo $student->id;?>"
                               class="show-data margin-left-10 btn-icon btn-border-blue btn btn-xs fa fa-bar-chart-o"
                               data-toggle="collapse"
                               data-type="student"
                               data-entity-type="chart"
                               aria-expanded="false"
                                ></a>
                        </span>
                    </div>
                    <?php echo elgg_view("page/elements/user_block", array("entity" => $student)); ?>
                    <div class="clearfix"></div>
                    <div>
                        <div class="collapse margin-top-10 chart" style="margin-left: 35px;" id="chart-<?php echo $student->id;?>"></div>
                        <div class="collapse margin-top-10 questions" id="questions-<?php echo $student->id;?>"></div>
                    </div>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
        <?php if($groups):?>
        <div role="tabpanel" class="tab-pane margin-top-10" id="groups" style="padding: 10px;">
            <?php
            elgg_push_context('quizgroups');
            $params = array(
                'filter' => '',
                'num_columns' => 1,
            );
            echo "<div class=\"learning_analytics_dashboard\">";
            echo elgg_view_layout('la_widgets_quizresults', $params);
            echo "</div>";
            elgg_pop_context();
            ?>
            <ul>
                <?php foreach(ClipitGroup::get_by_id($groups) as $group):?>
                <li class="list-item" data-entity="<?php echo $group->id;?>">
                    <div class="pull-right">
                        <div class="margin-right-10 inline-block status text-muted">
                            <small class="msg-not-finished"></small>
                            <div class="counts " style="display: none;">
                                <span class="answered"></span> -
                                <i class="fa fa-check green"></i> <strong class="a-correct">-</strong>
                            </div>
                        </div>
                        <span class="pull-right">
                            <a href="#questions-<?php echo $group->id;?>"
                               class="show-data btn-primary btn btn-xs btn-icon fa-list fa btn-border-blue"
                               data-type="group"
                               data-entity-type="questions"
                               data-toggle="collapse"
                                ></a>
                            <a href="#chart-<?php echo $group->id;?>"
                               class="show-data margin-left-10 btn-icon btn-border-blue btn btn-xs fa fa-bar-chart-o"
                               data-toggle="collapse"
                               data-type="group"
                               data-entity-type="chart"
                               aria-expanded="false"></a>
                        </span>
                    </div>
                    <?php
                        echo elgg_view("page/components/modal_remote", array('id'=> "group-{$group->id}" ));
                        echo elgg_view('output/url', array(
                            'href'  => "ajax/view/modal/group/view?id={$group->id}",
                            'text'  => '<i class="fa fa-users"></i> '.$group->name,
                            'title' => $group->name,
                            'data-toggle'   => 'modal',
                            'data-target'   => '#group-'.$group->id
                        ));
                    ?>
                    <small class="show">
                        <?php echo count($group->user_array);?> <?php echo elgg_echo('students');?>
                    </small>
                    <div class="clearfix"></div>
                    <div>
                        <div class="collapse margin-top-10 chart" style="margin-left: 35px;" id="chart-<?php echo $group->id;?>"></div>
                        <div class="collapse margin-top-10 questions" id="questions-<?php echo $group->id;?>"></div>
                    </div>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php endif;?>
        <div role="tabpanel" class="tab-pane margin-top-10" id="activity" style="padding: 10px;">
            <?php
            elgg_push_context('quizactivity');
            $params = array(
                'filter' => '',
                'num_columns' => 1, 
            );
            echo "<div class=\"learning_analytics_dashboard\">";
            echo elgg_view_layout('la_widgets_quizresults', $params);
            echo "</div>";
            elgg_pop_context();
            ?>
            <ul>
                <li data-entity="<?php echo $activity->id;?>">
                <a href="#questions-<?php echo $activity->id;?>"
                   class="show-data btn-primary btn btn-xs btn-icon btn-border-blue"
                   data-type="activity"
                   data-entity-type="questions"
                   data-toggle="collapse"
                    ><i class="fa-list fa"></i> <?php echo elgg_echo('quiz:questions');?></a>
                <a href="#chart-<?php echo $activity->id;?>"
                   class="show-data margin-left-10 btn-primary btn btn-xs btn-icon btn-border-blue"
                   data-toggle="collapse"
                   data-type="activity"
                   data-entity-type="chart"
                   aria-expanded="false"><i class="fa-bar-chart-o fa"></i> <?php echo elgg_echo('stats');?></a>
                <div>
                    <div class="collapse margin-top-10 chart" style="margin-left: 35px;" id="chart-<?php echo $activity->id;?>"></div>
                    <div class="collapse margin-top-10 questions" id="questions-<?php echo $activity->id;?>"></div>
                </div>
                </li>
            </ul>
        </div>
    </div>

</div>

<div>
<!--    --><?php //echo elgg_view('widgets/quizresult/content');?>
</div>
