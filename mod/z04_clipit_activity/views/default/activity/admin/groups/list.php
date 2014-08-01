<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   25/07/14
 * Last update:     25/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$groups = elgg_extract('groups', $vars);
$activity = elgg_extract('activity', $vars);
?>
<input type="hidden" class="input-activity" name="entity-id" value="<?php echo $activity->id;?>">
<p class="text-right margin-bottom-20">
    <?php echo elgg_view('input/submit',
        array(
            'value' => elgg_echo('save'),
            'class' => "btn btn-primary"
        ));
    ?>
</p>
<style>
.items-padding li:hover .btn-danger{
    display: block !important;
}
</style>
<div id="groups" class="row">
    <?php echo elgg_view('activity/admin/groups/summary', array('groups' => $groups));?>
    <div class="col-md-4 margin-bottom-10" style="position: relative;">
        <a href="#create-group">
        <div class="bg-center-icon" style="cursor: pointer;">
            <div class="bg"></div>
            <div class="center-icon">
                <div>
                    <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x blue"></i>
                        <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
            </div>
        </div>
        </a>
        <ul style="background: #d6f0fa;" class="items-padding">
            <li></li>
        </ul>
    </div>
</div>
<p class="text-right">
    <?php echo elgg_view('input/submit',
        array(
            'value' => elgg_echo('save'),
            'class' => "btn btn-primary"
        ));
    ?>
</p>