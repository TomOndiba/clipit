<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 16:00
 */

function expose_file_functions(){
    $api_suffix = "clipit.file.";
    $class_suffix = "ClipitFile::";
    expose_common_functions($api_suffix, $class_suffix);
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
        "Adds Tags (by Id) to a File",
        "POST", false, true);
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
        "Removes Tags (by Id) from a File",
        "POST", false, true);
    expose_function(
        $api_suffix . "get_tags",
        $class_suffix . "get_tags",
        array(
            "id" => array(
                "type" => "int",
                "required" => true)),
        "Gets Tags (by Id) from a File",
        "GET", false, true);
}
