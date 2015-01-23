<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   27/11/2014
 * Last update:     27/11/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$example = elgg_extract('entity', $vars);
$multimedia = elgg_extract('multimedia', $vars);
$user = array_pop(ClipitUser::get_by_id(array($example->owner_id)));
$tricky_topic = array_pop(ClipitTrickyTopic::get_by_id(array($example->tricky_topic)));
?>
<div class="margin-bottom-10">
    <div class="pull-right">
        <small class="show">
            <?php echo elgg_view('output/friendlytime', array('time' => $example->time_created));?>
        </small>
        <div class="margin-top-10">
            <?php if($user->id == elgg_get_logged_in_user_guid()):?>
                <?php echo elgg_view('output/url', array(
                    'href'  => "tricky_topics/examples/edit/{$example->id}",
                    'class' => 'btn btn-xs btn-primary',
                    'title' => elgg_echo('edit'),
                    'text'  => '<i class="fa fa-edit"></i>',
                ));
                ?>
                <?php echo elgg_view('output/url', array(
                    'href'  => "action/example/remove?id={$example->id}",
                    'class' => 'btn btn-xs btn-danger remove-object',
                    'is_action' => true,
                    'title' => elgg_echo('delete'),
                    'text'  => '<i class="fa fa-trash-o"></i>',
                ));
                ?>
            <?php endif;?>
            <span class="margin-left-10">
                <?php echo elgg_view("page/components/print_button");?>
            </span>
        </div>
    </div>
    <small class="show"><?php echo elgg_echo('author');?></small>
    <i class="fa-user fa blue"></i>
    <?php echo elgg_view('output/url', array(
        'href'  => "profile/{$user->login}",
        'title' => $user->name,
        'text'  => $user->name,
    ));
    ?>
    <div class="clearfix"></div>
</div>

<?php if($example->description):?>
    <small class="show"><?php echo elgg_echo('description');?></small>
    <?php echo $example->description;?>
<?php endif;?>

<div class="row">
    <div class="col-md-9">
        <div class="margin-bottom-10">
            <small class="show"><?php echo elgg_echo('tricky_topic');?></small>
            <?php echo elgg_view('output/url', array(
                'href'  => "tricky_topics/view/{$example->tricky_topic}",
                'title' => $tricky_topic->name,
                'text'  => $tricky_topic->name,
            ));
            ?>
        </div>
        <div class="margin-bottom-10">
            <small class="show"><?php echo elgg_echo('tags');?></small>
            <?php echo elgg_view('tricky_topic/tags/view', array('tags' => $example->tag_array)); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="margin-bottom-10">
            <small class="show"><?php echo elgg_echo('location');?></small>
            <?php echo elgg_view('output/url', array(
                'href'  => "tricky_topics/examples?subject={$example->location}",
                'title' => $example->location,
                'text'  => $example->location,
            ));
            ?>
        </div>
        <div class="margin-bottom-10">
            <small class="show"><?php echo elgg_echo('country');?></small>
            <?php echo elgg_view('output/url', array(
                'href'  => "tricky_topics/examples?subject={$example->location}",
                'title' => get_countries_list($example->country),
                'text'  => get_countries_list($example->country),
            ));
            ?>
        </div>
    </div>
</div>
<hr>
<?php echo elgg_view('examples/reflection_item/list', array('entities' => $example->reflection_item_array));?>
<div>
    <?php echo elgg_view('page/components/title_block', array('title' => elgg_echo('activity:stas')));?>
    <div role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#files" aria-controls="files" role="tab" data-toggle="tab">
                    <?php echo elgg_echo('files');?> (<?php echo count($multimedia['files']);?>)
                </a>
            </li>
            <li role="presentation">
                <a href="#videos" aria-controls="videos" role="tab" data-toggle="tab">
                    <?php echo elgg_echo('videos');?> (<?php echo count($multimedia['videos']);?>)
                </a>
            </li>
            <li role="presentation">
                <a href="#storyboards" aria-controls="storyboards" role="tab" data-toggle="tab">
                    <?php echo elgg_echo('storyboards');?> (<?php echo count($multimedia['storyboards']);?>)
                </a>
            </li>
        </ul>
        <style>
            .filter-by-tags > a{
                padding: 10px !important;
                display: inline-block;
            }
        </style>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="files">
                <div class="margin-top-20">
                    <?php
                    $params = array(
                        'add_files' => false,
                        'files' => $multimedia['files'],
                        'href' => $href,
                        'options' => false,
                    );
                    if($multimedia['files']) {
                        echo elgg_view('multimedia/file/list_summary', $params);
                    } else {
                        echo elgg_view('output/empty', array('value' => elgg_echo('file:none')));
                    }
                    ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="videos">
                <div class="margin-top-20">
                    <?php
                    $params = array(
                        'videos' => $multimedia['videos'],
                        'href' => $href,
                        'view_comments' => false,
                        'options' => false,
                        'author_bottom' => true,
                    );
                    if($multimedia['videos']) {
                        echo elgg_view('multimedia/video/list_summary', $params);
                    } else {
                        echo elgg_view('output/empty', array('value' => elgg_echo('videos:none')));
                    }
                    ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="storyboards">
                <div class="margin-top-20">
                    <?php
                    $params = array(
                        'entities' => $multimedia['storyboards'],
                        'href' => $href,
                        'view_comments' => false,
                        'options' => false,
                    );
                    if($multimedia['storyboards']) {
                        echo elgg_view('multimedia/storyboard/list_summary', $params);
                    } else {
                        echo elgg_view('output/empty', array('value' => elgg_echo('storyboards:none')));
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>