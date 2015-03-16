<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   17/11/2014
 * Last update:     17/11/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$questions = elgg_extract('questions', $vars);
$id = elgg_extract('id', $vars);
?>
<tr data-clone="<?php echo $id;?>">
    <td class="text-center">
        <i class="fa fa-level-up fa-rotate-90" style="font-size: 20px;"></i>
    </td>
    <td colspan="4" style="padding: 0">
        <table class="table">
            <thead>
            <tr class="info">
                <th><?php echo elgg_echo('select');?></th>
                <th><?php echo elgg_echo('quiz:question');?></th>
                <th><?php echo elgg_echo('created');?></th>
                <th style="width: 110px;"><?php echo elgg_echo('difficulty');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach(ClipitQuizQuestion::get_by_id($questions) as $question_clone):
                $user = array_pop(ClipitUser::get_by_id(array($question_clone->owner_id)));
                ?>
                <tr class="info">
                    <td>
                        <a class="btn btn-xs btn-primary questions-select" id="<?php echo $question_clone->id;?>">Select</a>
                    </td>
                    <td>
                        <?php echo $question_clone->name;?>
                    </td>
                    <td>
                        <?php echo elgg_view('output/friendlytime', array('time' => $question_clone->time_created));?>
                        <small class="show">
                            <?php echo elgg_echo('by');?>
                            <?php echo elgg_view('output/url', array(
                                'target' => '_blank',
                                'href' => 'profile/'.$user->login,
                                'text' => $user->name
                            ));
                            ?>
                        </small>
                    </td>
                    <td>
                        <?php echo difficulty_bar($question_clone->difficulty);?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </td>
</tr>