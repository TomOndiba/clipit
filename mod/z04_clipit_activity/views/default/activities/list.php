<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 14/02/14
 * Time: 10:29
 * To change this template use File | Settings | File Templates.
 */
$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$user_loggedin_id = elgg_get_logged_in_user_guid();

$list_class = 'row';
if (isset($vars['list_class'])) {
    $list_class = "$list_class {$vars['list_class']}";
}

if (is_array($items) && count($items) > 0):
    echo "<ul class=\"$list_class\">";
    foreach ($items as $item):
        if($item):
            $group_id = ClipitGroup::get_from_user_activity($user_loggedin_id, $item->id);
            $group_array = ClipitGroup::get_by_id(array($group_id));
            $group =  array_pop($group_array); // ClipitGroup object
            $users = ClipitGroup::get_users($group_id);
            $users = array_slice($users, 0, 10);

            $params_progress = array(
                'value' => get_group_progress($group->id),
                'width' => '100%'
            );
            $progress_bar = elgg_view("page/components/progressbar", $params_progress);

            $title = elgg_get_friendly_title($item->name);
            $teachers = ClipitActivity::get_teachers($item->id);
            $isCalled = in_array($user_loggedin_id, $item->called_users_array);
            ?>
            <li class='list-item col-md-12'>
                <div class="row">
                    <div class="col-md-6">
                        <div class="pull-right">
                            <small class="activity-status status-<?php echo $item->status;?>">
                                <strong><?php echo elgg_echo("status:".$item->status);?></strong>
                            </small>
                        </div>
                        <h4 style='margin-top: 0'>
                            <?php echo elgg_view('output/url', array(
                                    'href' => "clipit_activity/{$item->id}",
                                    'text' => $item->name,
                                    'is_trusted' => true,
                                    'style' => 'color: #'.$item->color
                                ));
                            ?>
                        </h4>
                        <div style='color: #999;text-transform: uppercase;'>
                            <i class='fa fa-calendar'></i>
                            <?php echo date("d M Y", $item->time_created);?>
                            -
                            <?php echo date("d M Y", $item->end);?>
                        </div>
                        <div style='max-height: 40px; overflow: hidden; color: #666666;margin-top: 5px; '>
                            <?php echo $item->description; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-6" style="border-right: 1px solid #ccc; padding-right: 15px;">
                                <?php if($item->tricky_topic):?>
                                <div style="margin-bottom: 10px;" class="text-truncate">
                                    <small class="show"><?php echo elgg_echo('tricky_topic');?></small>
                                    <?php echo elgg_view('tricky_topic/preview', array('activity' => $item));?>
                                </div>
                                <?php endif; ?>
                                <?php if($item->status == 'enroll' && $isCalled): ?>
                                    <?php echo elgg_view('output/url', array(
                                        'href'  => "clipit_activity/{$item->id}/join",
                                        'class' => 'btn btn-xs btn-default btn-border-blue-lighter',
                                        'title' => elgg_echo('activity:join'),
                                        'text'  => elgg_echo('activity:join')
                                    ));
                                    ?>
                                <?php endif; ?>
                                <?php if($group):?>
                                <div>
                                    <?php echo $progress_bar; ?>
                                    <strong>
                                        <?php echo elgg_view('output/url', array(
                                            'href'  => "clipit_activity/{$item->id}/group/{$group->id}",
                                            'title' => $group->name,
                                            'text'  => $group->name));
                                        ?>
                                    </strong>
                                </div>
                                <div style='margin-top: 5px;'>
                                    <?php
                                    foreach ($users as $user_id):
                                        $user = array_pop(ClipitUser::get_by_id(array($user_id)));
                                        $user_avatar = elgg_view('output/img', array(
                                            'src' => get_avatar($user, 'small'),
                                            'style' => 'margin: 1px;',
                                            'class' => 'avatar-tiny'
                                        ));
                                    ?>
                                        <?php echo elgg_view('output/url', array(
                                            'href'  => "profile/".$user->login,
                                            'title' => $user->name,
                                            'text' => $user_avatar
                                         ));
                                        ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-6">
                                <small class='show'>Teacher/s</small>
                                <ul style="max-height: 100px; overflow: auto;">
                                    <?php
                                    foreach($teachers as $teacher_id):
                                        $teacher = array_pop(ClipitUser::get_by_id(array($teacher_id)));
                                    ?>
                                    <li class="list-item" style="border-bottom: 0;margin-bottom: 0;">
                                        <?php echo elgg_view("page/elements/user_block", array('entity' => $teacher)); ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
