<?php
 /**
 * Clipit Web Space
 * PHP version:     >= 5.2
 * Creation date:   29/07/14
 * Last update:     29/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC Clipit Project
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 */
$users = elgg_extract('users', $vars);
$groups = elgg_extract('groups', $vars);
?>
<?php
foreach($groups as $group):
    $group_users = array_intersect(array_keys($users), $group->user_array);
    $id = uniqid();
    $tags = array();
    $tags = ClipitGroup::get_tags($group->id);
?>
    <div class="col-md-4 group-list margin-bottom-10">
        <a title="<?php echo elgg_echo('delete');?>" href="javascript:;" class="pull-right btn btn-xs btn-danger delete-group" aria-label="delete" rel="nofollow"><i class="fa fa-trash-o"></i></a>
        <input type="text" name="group[<?php echo $id;?>][name]" value="<?php echo $group->name;?>" style="width: 85%;" aria-label="input-group-name" class="input-group-name form-control margin-bottom-10">
        <div class="margin-bottom-5" style="height: 50px;">
            <?php echo elgg_view("page/components/modal_remote", array('id'=> "sb-group-{$group->id}" ));?>
            <small>
                <?php
                echo elgg_view('output/url', array(
                    'href'  => "ajax/view/modal/group/assign_sb?group_id={$group->id}",
                    'text'  => '<i class="fa fa-plus"></i> '.elgg_echo('group:assign_sb'),
                    'id' => 'assign-sb',
                    'aria-label'=> elgg_echo('delete'),
                    'data-toggle'   => 'modal',
                    'data-target'   => '#sb-group-'.$group->id
                ));
                ?>
            </small>
            <?php echo elgg_view("tricky_topic/tags/view", array('tags' => $tags, 'limit' => 2, 'width' => '45%')); ?>
        </div>
        <ul class="items-padding users-list">
            <?php
                foreach($group_users as $group_user_id):
                    $group_user = $users[$group_user_id];
            ?>
                <li data-user="<?php echo $group_user->id;?>">
                    <?php echo elgg_view('output/url', array(
                        'title' => elgg_echo('delete'),
                        'text' => '<i class="fa fa-trash-o"></i>',
                        'href' => "javascript:;",
                        'style' => 'display:none;',
                        'aria-label' => 'delete',
                        'class' => 'pull-right btn btn-xs btn-danger delete-user',
                    ));
                    ?>
                    <?php echo elgg_view('output/img', array(
                        'src' => get_avatar($group_user, 'small'),
                        'class' => 'image-block avatar-tiny',
                        'alt' => 'avatar-tiny',
                    ));
                    ?>
                    <span class="text-truncate" title="<?php echo $group_user->name;?> (<?php echo $group_user->login;?>)">
                        <?php echo $group_user->name;?>
                    </span>
                </li>
            <?php endforeach;?>
            <input type="hidden" aria-label="input-group" class="input-group" name="group[<?php echo $id;?>][id]" value="<?php echo $group->id;?>">
            <input type="hidden" aria-label="input-users" class="input-users" name="group[<?php echo $id;?>][users]" value="<?php echo implode(",", $group->user_array);?>">
            <input type="hidden" aria-label="input-remove-group" class="input-remove-group" name="group[<?php echo $id;?>][remove]">
        </ul>
    </div>
<?php endforeach;?>