<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   03/11/2014
 * Last update:     03/11/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$id = elgg_extract('id', $vars);
$num = elgg_extract('num', $vars);
$id_input = uniqid('input_');
$input_prefix = elgg_extract('input_prefix', $vars);
?>
<div class="margin-bottom-20 result">
    <label class="inline-block" style="padding-right: 10px;">
        <input
            type="radio" <?php echo $vars['checked'] == true ? 'checked' : '';?>
            name="<?php echo $input_prefix;?>[question][<?php echo $id;?>][select_one][<?php echo $id_input;?>][correct]"
            value="1"
            class="inline-block">
    </label>
    <?php echo elgg_view('output/url', array(
        'href'  => "javascript:;",
        'class' => 'fa fa-times red pull-right margin-top-10',
        'text'  => '',
        'onclick' => '$(this).parent().remove();',
    ));
    ?>
    <?php echo elgg_view("input/text", array(
        'name' => $input_prefix.'[question]['.$id.'][select_one]['.$id_input.'][value]',
        'class' => 'form-control inline-block',
        'style' => 'width: 85%',
        'placeholder' => 'Respuesta '.$num,
        'value' => $vars['value'],
    ));
    ?>
</div>