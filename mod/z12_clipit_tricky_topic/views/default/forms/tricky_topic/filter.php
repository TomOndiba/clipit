<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   19/01/2015
 * Last update:     19/01/2015
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$tags = get_input('tags');
?>
<?php
echo elgg_view("input/hidden", array(
    'name' => 'tags',
    'id' => 'input_tags',
    'value' => $tags
));
?>
<div class="form-group">
    <label class="text-muted"><?php echo elgg_echo('tricky_topic');?></label>
    <?php echo elgg_view("input/text", array(
        'name' => 'tricky_topic',
        'class' => 'form-control',
        'value' => get_input('tricky_topic')
    ));
    ?>
</div>
<div class="form-group">
    <label class="text-muted"><?php echo elgg_echo('tags');?></label>
    <ul id="tags"></ul>
</div>
<?php
$ed_levels = array(
    '' => '',
    1 => elgg_echo('education_level:1'),
    2 => elgg_echo('education_level:2'),
    3 => elgg_echo('education_level:3'),
    4 => elgg_echo('education_level:4'),
);
?>
<div class="form-group">
    <label class="text-muted"><?php echo elgg_echo('education_level');?></label>
    <?php echo elgg_view("input/dropdown", array(
        'name' => 'education_level',
        'style' => 'padding: 0;height: 25px;',
        'value' => get_input('education_level'),
        'class' => 'form-control select-question-type',
        'options_values' => get_education_levels(),
    ));
    ?>
</div>
<div class="form-group">
    <label class="text-muted"><?php echo elgg_echo('example:subject');?></label>
    <?php echo elgg_view("input/text", array(
        'name' => 'subject',
        'class' => 'form-control',
        'value' => get_input('subject')
    ));
    ?>
</div>
<div class="text-right">
    <?php echo elgg_view('input/submit', array(
        'class' => 'btn btn-primary btn-sm',
        'value'  => elgg_echo('search'),
    ));
    ?>
</div>