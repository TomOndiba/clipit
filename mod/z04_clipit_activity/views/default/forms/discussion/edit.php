<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 25/02/14
 * Time: 10:41
 * To change this template use File | Settings | File Templates.
 */
$message = elgg_extract('entity', $vars);

// Load tinyMCE in textarea
$body = "<script>$(function(){tinymce_setup();});</script>";

$body .= elgg_view("input/hidden", array(
    'name' => 'message-id',
    'value' => $message->id,
));

$body .='<div class="form-group">
    <label for="discussion-title">'.elgg_echo("discussion:title_topic").'</label>
    '.elgg_view("input/text", array(
        'name' => 'discussion-title',
        'value' => $message->name,
        'class' => 'form-control',
        'required' => true
    )).'
</div>
<div class="form-group">
    <label for="discussion-text">'.elgg_echo("discussion:text_topic").'</label>
    '.elgg_view("input/plaintext", array(
        'name'  => 'discussion-text',
        'value' => $message->description,
        'id'    => 'edit-'.$message->id,
        'class' => 'form-control mceEditor',
        'required' => true,
        'rows'  => 6,
    )).'
</div>';

echo elgg_view("page/components/modal",
    array(
        "dialog_class"     => "modal-lg",
        "remote"    => true,
        "target"    => "edit-discussion-{$message->id}",
        "title"     => elgg_echo("discussion:edit"),
        "form"      => true,
        "body"      => $body,
        "cancel_button" => true,
        "ok_button" => elgg_view('input/submit',
            array(
                'value' => elgg_echo('update'),
                'class' => "btn btn-primary"
            ))
    ));
?>