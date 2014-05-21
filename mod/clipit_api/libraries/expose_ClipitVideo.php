<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 16:04
 */

function expose_video_functions(){
    $api_suffix = "clipit.video.";
    $class_suffix = "ClipitVideo::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_common_publication_functions($api_suffix, $class_suffix);
    expose_function(
        $api_suffix . "add_comments",
        $class_suffix . "add_comments",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "comment_array" => array(
                "type" => "array",
                "required" => true)),
        "Add Comments by Id to a Video",
        'POST', false, true);
    expose_function(
        $api_suffix . "remove_comments",
        $class_suffix . "remove_comments",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "comment_array" => array(
                "type" => "array",
                "required" => true)),
        "Remove Comments by Id from a Video",
        'POST', false, true);
    expose_function(
        $api_suffix . "get_comments",
        $class_suffix . "get_comments",
        array(
            "id" => array(
                "type" => "int",
                "required" => true)),
        "Get Comments from a Video",
        'GET', false, true);
    expose_function(
        $api_suffix . "add_tags",
        $class_suffix . "add_tags",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "tag_array" => array(
                "type" => "array",
                "required" => true)),
        "Add Tags by Id to a Video",
        'POST', false, true);
    expose_function(
        $api_suffix . "remove_tags",
        $class_suffix . "remove_tags",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "tag_array" => array(
                "type" => "array",
                "required" => true)),
        "Remove Tags by Id from a Video",
        'POST', false, true);
    expose_function(
        $api_suffix . "get_tags",
        $class_suffix . "get_tags",
        array(
            "id" => array(
                "type" => "int",
                "required" => true)),
        "Get Tags from a Video",
        'GET', false, true);
}
