<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   10/07/14
 * Last update:     10/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
?>
<h3 class="title-block"><?php echo elgg_echo('activity:grouping_mode');?></h3>
<div class="panel-group" id="accordion_grouping">
    <div class="panel panel-default">
        <div class="panel-heading select-radio" style="background: #fff;">
            <h4 class="panel-title blue " data-toggle="collapse" data-parent="#accordion_grouping" href="#collapse_1">
            <input value="1" style="visibility: hidden" type="radio" required name="groups_creation"/>
                <a href="javascript:;">
                    <i class="fa fa-user"></i>
                    Teacher make groups
                </a>
            </h4>
        </div>
        <div id="collapse_1" class="panel-collapse collapse">
            <div class="panel-body">
                Anim pariatur cliche reprehenderit,
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading select-radio" style="background: #fff;">
            <h4 class="panel-title blue" data-toggle="collapse" data-parent="#accordion_grouping" href="#collapse_2">
                <input value="2" style="visibility: hidden" type="radio" required name="groups_creation"/>
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    Students make groups
                </a>
            </h4>
        </div>
        <div id="collapse_2" class="panel-collapse collapse">
            <div class="panel-body">
                <?php echo elgg_view("activity/create/groups/limit");?>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading select-radio" style="background: #fff;">
            <h4 class="panel-title blue" data-toggle="collapse" data-parent="#accordion_grouping" href="#collapse_3">
                <input value="3" style="visibility: hidden" type="radio" required name="groups_creation"/>
                <a href="javascript:;">
                    <i class="fa fa-users"></i>
                    Create random groups
                </a>
            </h4>
        </div>
        <div id="collapse_3" class="panel-collapse collapse">
            <div class="panel-body form-group">
                <?php echo elgg_view("activity/create/groups/limit");?>
            </div>
        </div>
    </div>
</div>