<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 15:59
 */

function expose_comment_functions(){
    $api_suffix = "clipit.comment.";
    $class_suffix = "ClipitComment::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_common_message_functions($api_suffix, $class_suffix);
}
