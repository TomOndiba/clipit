<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   2/06/14
 * Last update:     2/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entity_id = elgg_extract('entity_id', $vars);
?>
<?php echo elgg_view("input/hidden", array(
    'name' => 'entity-id',
    'id' => 'entity-id',
    'value' => $entity_id
));?>
<?php echo elgg_view("input/hidden", array(
    'name' => 'labels',
    'id' => 'input_labels',
    'value' => ''
));?>
<div class="form-group">
    <label><?php echo elgg_echo("labels");?> <small><?php echo elgg_echo("tags:commas:separated");?></small></label>
    <ul id="labels"></ul>
</div>
<div class="text-right">
    <?php echo elgg_view('input/reset',
        array(
            'value' => elgg_echo('cancel'),
            'class' => "btn btn-border-blue cancel btn-default",
            'onclick' => 'javascript:$(this).closest(\'form\').hide();'
        ));
    ?>
    <?php echo elgg_view('input/submit',
        array(
            'value' => elgg_echo('submit'),
            'class' => "btn btn-primary"
        ));
    ?>
</div>