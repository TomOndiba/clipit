<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   28/01/2015
 * Last update:     28/01/2015
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$activities = elgg_extract('entities', $vars);
$count = elgg_extract('count', $vars);
$table_orders = elgg_extract('table_orders', $vars);
?>
<div class="margin-bottom-20">
    <div class="pull-right">
        <?php echo elgg_view("page/components/print_button");?>
    </div>
    <?php echo elgg_view('output/url', array(
        'href'  => "tricky_topics/create",
        'class' => 'btn btn-primary margin-bottom-10',
        'title' => elgg_echo('create'),
        'text'  => elgg_echo('create'),
    ));
    ?>
</div>
<table class="table table-striped table-order">
    <thead>
    <tr class="title_order">
        <?php foreach($table_orders as $data):?>
            <th>
                <a href="<?php echo $data['href'];?>">
                    <i class="fa <?php echo $data['sort_icon'];?> blue margin-right-5" style="position: absolute;left: 0;margin-top: 3px;"></i>
                    <span class="margin-left-5"><?php echo $data['value'];?></span>
                </a>
            </th>
        <?php endforeach;?>
        <th><?php echo elgg_echo('activity:teachers');?></th>
    </tr>
    </thead>
    <?php
    foreach($activities as $activity):
        $user = array_pop(ClipitUser::get_by_id(array($activity->owner_id)));
        $tricky_topic = array_pop(ClipitTrickyTopic::get_by_id(array($activity->tricky_topic)));
    ?>
        <tr>
            <td>
<!--                <i class="activity-point pull-left margin-top-5 margin-right-10" style="background: #--><?php //echo $activity->color;?><!--"></i>-->
                <div class="content-block">
                <strong>
                <?php echo elgg_view('output/url', array(
                    'href'  => "clipit_activity/{$activity->id}",
                    'title' => $activity->name,
                    'text'  => $activity->name,
                ));
                ?>
                </strong>
                <div class="margin-top-5">
                <small style='color: #999;text-transform: uppercase;'>
                    <i class='fa fa-calendar'></i>
                    <?php echo date("d M Y", $activity->start);?>
                    -
                    <?php echo date("d M Y", $activity->end);?>
                </small>
                </div>
                </div>
            </td>
            <td>
                <?php echo elgg_view('output/url', array(
                    'href'  => "tricky_topics/view/{$tricky_topic->id}",
                    'title' => $tricky_topic->name,
                    'text'  => $tricky_topic->name,
                ));
                ?>
            </td>
            <td>
                <small class="activity-status status-<?php echo $activity->status;?>">
                    <strong><?php echo elgg_echo("status:".$activity->status);?></strong>
                </small>
            </td>

            <td>
                <ul style="max-height: 100px; overflow: auto;">
                    <?php
                    foreach($activity->teacher_array as $teacher_id):
                        $teacher = array_pop(ClipitUser::get_by_id(array($teacher_id)));
                    ?>
                        <li class="list-item" style="border-bottom: 0;margin-bottom: 0;">
                            <?php echo elgg_view("messages/compose_icon", array('entity' => $teacher));?>
                            <?php echo elgg_view('output/url', array(
                                'href'  => "profile/".$user->login,
                                'title' => $teacher->name,
                                'text'  => $teacher->name,
                            ));
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php echo clipit_get_pagination(array('count' => $count, 'limit' => 10)); ?>