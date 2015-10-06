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
$user_id = elgg_get_logged_in_user_guid();
$user = array_pop(ClipitUser::get_by_id(array($user_id)));
?>
<li class="separator">|</li>
<li class="margin-left-10 margin-right-10 user-profile">
    <?php echo elgg_view('output/url', array(
        'title' => $user->name,
        'href'  => "profile/{$user->login}",
        'class' => 'avatar-user text-truncate',
        'text'  => elgg_view('output/img', array(
                'src' => get_avatar($user, 'small'),
                'alt' => $user->name,
                'title' => elgg_echo('profile'),
                'class' => 'elgg-border-plain elgg-transition avatar-small',
            ))."<span class='hidden-xs hidden-sm'> ".$user->name."</span>"
    ));
    ?>
    <?php echo elgg_view('output/url', array(
        'href'  => "#",
        'data-toggle' => 'dropdown',
        'class' => 'caret-down hidden-xs hidden-sm',
        'id' => 'settings',
        'text'  => '<i class="fa fa-caret-down"></i>'
    ));
    ?>
    <!-- Profile menu -->
    <ul id="menu_settings" class="dropdown-menu caret-menu" role="menu" aria-labelledby="settings">
        <?php if ($user->role == ClipitUser::ROLE_ADMIN):?>
        <li role="presentation">
            <?php echo elgg_view('output/url', array(
                'href'  => "admin",
                'title' => elgg_echo('admin:page'),
                'text'  => '<i class="fa fa-edit"></i> '.elgg_echo('admin:page'),
            ));
            ?>
        </li>
        <li role="presentation" class="divider"></li>
        <?php endif;?>
        <li role="presentation">
            <?php echo elgg_view('output/url', array(
                'href'  => "settings/user",
                'title' => elgg_echo('profile:settings:edit_profile'),
                'text'  => '<i class="fa fa-cog"></i> '.elgg_echo('profile:settings:edit_profile'),
            ));
            ?>
        </li>
        <li role="presentation" class="divider"></li>
        <li role="presentation">
            <?php echo elgg_view('output/url', array(
                'href'  => "settings/avatar",
                'title' => elgg_echo('avatar:edit'),
                'text'  => '<i class="fa fa-picture-o"></i> '.elgg_echo('avatar:edit'),
            ));
            ?>
        </li>
        <!--  Currently off      -->
        <?php //if($user->role == ClipitUser::ROLE_TEACHER):?>
<!--            <li role="presentation" class="divider"></li>-->
<!--            <li role="presentation">-->
<!--                --><?php //echo elgg_view('output/url', array(
//                    'href'  => "stats",
//                    'title' => elgg_echo('profile:stats'),
//                    'text'  => '<i class="fa fa-bar-chart-o"></i> '.elgg_echo('profile:stats'),
//                ));
//                ?>
<!--            </li>-->
<!--        --><?php //endif;?>
    </ul>
</li>
<li>
    <?php echo elgg_view('output/url', array(
        'href'  => "action/logout",
        'class' => 'hidden-xs hidden-sm',
        'title' => elgg_echo('user:logout'),
        'text'  => '<i style="color: #ff4343;" class="fa fa-sign-out"></i>'
    ));
    ?>
</li>
