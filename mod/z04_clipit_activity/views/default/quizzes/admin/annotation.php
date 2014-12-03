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
$result = elgg_extract('result', $vars);
$question = elgg_extract('question', $vars);

echo elgg_view('input/hidden', array(
    'name' => 'entity-id',
    'id' => 'entity-id',
    'value' => $result->id
));
?>
<div class="bg-blue-lighter_4 margin-top-10 annotate" style="<?php echo $result->description ? '': 'display:none;';?>padding: 10px;">
    <?php if($question->option_type == ClipitQuizQuestion::TYPE_STRING && $result):?>
    <div class="pull-right">
        <strong>
            <a href="javascript:;" class="margin-right-10">
                <i class="fa fa-check green"></i> Correct
            </a>
            <a href="javascript:;">
                <i class="fa fa-times red"></i> Incorrect
            </a>
        </strong>
    </div>
    <?php endif;?>
    <script>
        tinymce_setup();
    </script>
    <i class="fa fa-user blue"></i> <small>Teacher's annotate:</small>
    <?php echo elgg_view("input/plaintext", array(
        'class' => 'form-control mceEditor',
        'value' => $result->description,
        'name' => 'annotation',
        'rows' => 5
    ));
    ?>
    <a class="btn btn-primary btn-xs pull-right save-annotation" style="margin: 10px;"><?php echo elgg_echo('save');?></a>
    <div class="clearfix"></div>
</div>