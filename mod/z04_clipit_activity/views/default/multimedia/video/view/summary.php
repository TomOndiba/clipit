<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   30/06/14
 * Last update:     30/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entities = elgg_extract('entities', $vars);
if($entities):
foreach($entities as $video):
    $tags = $video->tag_array;
?>
<style>
.elgg-module.videos-summary .tags{
    margin-bottom: 0;
}
.elgg-module-aside .wrapper{
    border-bottom: 1px solid #bae6f6;
}
.elgg-module-aside .wrapper:last-child{
    border-bottom: 0;
}
</style>
    <div class="wrapper separator" style="
    overflow: hidden;
    border-radius: 0;
">
        <?php echo elgg_view('output/url', array(
            'href'  => "explore/view/{$video->id}",
            'text' => "<strong>{$video->name}</strong>",
            'class' => 'text-truncate margin-bottom-5',
        ));
        ?>

        <?php echo elgg_view('output/url', array(
            'href'  => "explore/view/{$video->id}",
            'text'  => elgg_view('output/img', array(
                'src' => $video->preview,
                'class' => 'pull-left image-block',
                'style' => 'width:40%;height:auto'
            )),
        ));
        ?>
        <div class="text" style="
    overflow: hidden;
">
            <div class="rating readonly" data-score="3.6666666666667" style="
    font-size: 13px;
">
                <i class="fa fa-star" data-rating="1"></i> <i class="fa fa-star" data-rating="2"></i> <i class="fa fa-star" data-rating="3"></i> <i class="fa fa-star-half-o" data-rating="4"></i> <i class="fa fa-star empty" data-rating="5"></i>
            </div>
            <?php echo elgg_view('tricky_topic/tags/view', array('tags' => $tags, 'limit' => 1, 'width' => "85%")); ?>
            <small class="show"><?php echo elgg_view('output/friendlytime', array('time' => $video->time_created));?></small>
        </div>

    </div>
<?php endforeach;?>
<?php else: ?>
<div class="wrapper">
    <small><strong><?php echo elgg_echo('videos:recommended:none');?></strong></small>
</div>
<?php endif;?>