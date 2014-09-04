<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   23/07/14
 * Last update:     23/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entities = elgg_extract('entities', $vars);
$activities = ClipitActivity::get_by_id($entities);
?>
<div class="wrapper separator">
<?php
foreach($activities as $activity):
    $activity_progress = round(((time() - $activity->start)/($activity->end - $activity->start)) * 100);
    if($activity_progress == 0){
        $activity_progress = 5;
    }
?>
    <div class="bar" style="height: 35px;line-height: 35px;max-width:100%;width:<?php echo $activity_progress;?>%;background: #<?php echo $activity->color;?>;">
        <div>
            <h4>
             <?php echo elgg_view('output/url', array(
                    'href' => "clipit_activity/{$activity->id}",
                    'text' => $activity->name,
                    'title' => $activity->title,
                    'is_trusted' => true,
                ));
             ?>
            </h4>
        </div>
    </div>
<?php endforeach;?>
</div>