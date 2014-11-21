<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   24/06/14
 * Last update:     24/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entity_id = elgg_extract('entity_id', $vars);
$list = elgg_extract('list', $vars);
$class = elgg_extract('class', $vars);
$id = elgg_extract('id', $vars);
if(!$id){
    $id = uniqid();
}
?>
<style>
.select-item{
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 4px;
    z-index: 10;
    display: none;
    cursor: pointer;
}
.attach-block{
    margin: 2px !important;
    border: 2px solid transparent;
}
.multimedia-block:hover .attach-block{
    border: 2px solid #32b4e5 !important;
}
.multimedia-block:hover .attach-block.selected {
    border: 2px solid rgba(153, 203, 0, 0.5) !important;
}
.multimedia-block .selected{
    border: 2px solid #99cb00;
    display: block;
}
.multimedia-block:hover .select-item{
    display: block;
}
</style>
<style>
    #attach_list ul.menu-list>li {
        padding: 5px;
    }
</style>
<div data-attach="<?php echo $id;?>" id="attach_list" class="row attach_list <?php echo $class;?>" style="padding: 10px 0;display: none">
    <ul class="col-md-2 margin-top-10 menu-list">
        <li class="selected" data-menu="files">
            <strong><span class="blue-lighter pull-right" id="files_count"></span></strong>
            <?php echo elgg_view('output/url', array(
                'href'  => "javascript:;",
                'title' => elgg_echo('files'),
                'class' => 'element_attach_menu show child-decoration-none',
                'data-menu' => 'files',
                'text'  => elgg_echo('files')
            ));
            ?>
        </li>
        <li data-menu="videos">
            <strong><span class="blue-lighter pull-right" id="videos_count"></span></strong>
            <?php echo elgg_view('output/url', array(
                'href'  => "javascript:;",
                'title' => elgg_echo('videos'),
                'class' => 'element_attach_menu show child-decoration-none',
                'data-menu' => 'videos',
                'text'  => elgg_echo('videos')
            ));
            ?>
        </li>
        <li data-menu="storyboards">
            <strong><span class="blue-lighter pull-right" id="storyboards_count"></span></strong>
            <?php echo elgg_view('output/url', array(
                'href'  => "javascript:;",
                'title' => elgg_echo('storyboards'),
                'class' => 'element_attach_menu show child-decoration-none',
                'data-menu' => 'storyboards',
                'text'  => elgg_echo('storyboards')
            ));
            ?>
        </li>
    </ul>
    <div class="col-md-10" style="border-left: 1px solid #bae6f6;padding: 0px 10px;">
        <div class="multimedia-list">
            <p id="attach-loading" style="display: none;"><span><i class="fa fa-spinner fa-spin"></i></span></p>
        </div>
    </div>
</div>