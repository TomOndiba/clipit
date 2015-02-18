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
$tricky_topic = elgg_extract('entity', $vars);
$tt_parent = elgg_extract('tricky_topic_parent', $vars);
$multimedia = elgg_extract('multimedia', $vars);
$examples = elgg_extract('examples', $vars);
$user = array_pop(ClipitUser::get_by_id(array($tricky_topic->owner_id)));
?>
<div class="margin-bottom-10">
    <div class="pull-right">
        <div class="margin-bottom-10">
            <?php if($user->id == elgg_get_logged_in_user_guid()):?>
                <?php echo elgg_view('output/url', array(
                    'href'  => "tricky_topics/edit/{$tricky_topic->id}",
                    'class' => 'btn btn-xs btn-primary',
                    'title' => elgg_echo('edit'),
                    'text'  => '<i class="fa fa-edit"></i>',
                ));
                ?>
                <?php echo elgg_view('output/url', array(
                    'href'  => "action/tricky_topic/remove?id={$tricky_topic->id}",
                    'class' => 'btn btn-xs btn-danger remove-object',
                    'is_action' => true,
                    'title' => elgg_echo('delete'),
                    'text'  => '<i class="fa fa-trash-o"></i>',
                ));
                ?>
            <?php endif;?>
            <?php echo elgg_view('output/url', array(
                'href'  => "tricky_topics/create/{$tricky_topic->id}",
                'class' => 'btn btn-xs btn-primary btn-border-blue',
                'title' => elgg_echo('duplicate'),
                'text'  => '<i class="fa fa-copy"></i>',
            ));
            ?>
            <span class="margin-left-10">
                <?php echo elgg_view("page/components/print_button");?>
            </span>
        </div>
        <small class="show">
            <?php echo elgg_view('output/friendlytime', array('time' => $tricky_topic->time_created));?>
        </small>
    </div>
    <div class="inline-block">
        <small class="show"><?php echo elgg_echo('author');?></small>
        <i class="fa-user fa blue"></i>
        <?php echo elgg_view('output/url', array(
            'href'  => "profile/{$user->login}",
            'title' => $user->name,
            'text'  => $user->name,
        ));
        ?>
    </div>
    <?php
    if($tt_parent):
        $tt_parent = array_pop(ClipitTrickyTopic::get_by_id(array($tt_parent)));
    ?>
    <div class="inline-block margin-left-20">
        <small class="show">
            <i class="fa fa-sitemap"></i>
            <?php echo elgg_echo('tricky_topic:duplicate_from');?>
        </small>
        <?php echo elgg_view('output/url', array(
            'href'  => "tricky_topics/view/{$tt_parent->id}",
            'title' => $tt_parent->name,
            'text'  => $tt_parent->name,
        ));
        ?>
    </div>
    <?php endif;?>
    <div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if($tricky_topic->description):?>
            <small class="show"><?php echo elgg_echo('description');?></small>
            <?php echo $tricky_topic->description;?>
        <?php endif;?>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <small class="show"><?php echo elgg_echo('tags');?></small>
        <?php echo elgg_view('tricky_topic/tags/view', array('tags' => $tricky_topic->tag_array)); ?>
    </div>
    <div class="col-md-2">
        <small class="show"><?php echo elgg_echo('education_level');?></small>
        <?php echo elgg_view('output/url', array(
            'href'  => set_search_input('tricky_topics', array('education_level'=>$tricky_topic->education_level)),
            'title' => elgg_echo('education_level:'.$tricky_topic->education_level),
            'text'  => elgg_echo('education_level:'.$tricky_topic->education_level),
        ));
        ?>
    </div>
    <div class="col-md-3">
        <small class="show"><?php echo elgg_echo('tricky_topic:subject');?></small>
        <?php echo elgg_view('output/url', array(
            'href'  => set_search_input('tricky_topics', array('subject'=>$tricky_topic->subject)),
            'title' => $tricky_topic->subject,
            'text'  => $tricky_topic->subject,
        ));
        ?>
    </div>
</div>
<a name="examples"></a>
<div class="margin-bottom-10">
    <?php echo elgg_view('page/components/title_block', array('title' => elgg_echo('examples')));?>
    <?php echo elgg_view('examples/summary', array('entities' => $examples));?>
    <?php echo elgg_view('output/url', array(
        'class' => 'btn btn-xs btn-primary',
        'href'  => "tricky_topics/examples/create?tricky_topic_id={$tricky_topic->id}",
        'title'  => elgg_echo('example:create'),
        'text'  => elgg_echo('example:create'),
    ));
    ?>
</div>
<a name="resources"></a>
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
        <div role="tabpanel" class="tab-pane active form-group" id="files">
            <div class="margin-top-20">
                <?php
                echo elgg_view_form('tricky_topic/resources', array(
                    'body' => elgg_view('forms/attachments/files',
                        array('submit' => true, 'entity_id' => $tricky_topic->id)),
                    'class' => 'gray-block',
                    'enctype' => 'multipart/form-data'
                ));
                ?>
                <hr>
                <?php
                $params = array(
                    'add_files' => false,
                    'files' => $multimedia['files'],
                    'href' => $href,
                    'options' => true,
                    'preview' => true
                );
                if($multimedia['files']) {
                    echo elgg_view('multimedia/file/list_summary', $params);
                } else {
                    echo elgg_view('output/empty', array('value' => elgg_echo('file:none')));
                }
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane form-group" id="videos">
            <div class="margin-top-20">
                <?php
                echo elgg_view_form('tricky_topic/resources', array(
                    'body' => elgg_view('forms/attachments/videos',
                        array('submit' => true, 'entity_id' => $tricky_topic->id)),
                    'class' => 'gray-block',
                    'enctype' => 'multipart/form-data'
                ));
                ?>
                <hr>
                <?php
                $params = array(
                    'videos' => $multimedia['videos'],
                    'href' => $href,
                    'view_comments' => false,
                    'actions' => true,
                    'options' => false,
                    'author_bottom' => true,
                    'preview' => true
                );
                if($multimedia['videos']) {
                    echo elgg_view('multimedia/video/list_summary', $params);
                } else {
                    echo elgg_view('output/empty', array('value' => elgg_echo('videos:none')));
                }
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane form-group" id="storyboards">
            <div class="margin-top-20">
                <?php
                echo elgg_view_form('tricky_topic/resources', array(
                    'body' => elgg_view('forms/attachments/storyboards',
                        array('submit' => true, 'entity_id' => $tricky_topic->id)),
                    'class' => 'gray-block',
                    'enctype' => 'multipart/form-data'
                ));
                ?>
                <hr>
                <?php
                $params = array(
                    'entities' => $multimedia['storyboards'],
                    'href' => $href,
                    'view_comments' => false,
                    'actions' => true,
                    'preview' => true
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