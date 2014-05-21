<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 16:02
 */

function expose_quiz_question_functions(){
    $api_suffix = "clipit.quiz_question.";
    $class_suffix = "ClipitQuizQuestion::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_function(
        $api_suffix . "add_quiz_results",
        $class_suffix . "add_quiz_results",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "quiz_result_array" => array(
                "type" => "array",
                "required" => true)),
        "Add Quiz Results for the specified Quiz",
        'POST', false, true);
    expose_function(
        $api_suffix . "set_quiz_results",
        $class_suffix . "set_quiz_results",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "quiz_result_array" => array(
                "type" => "array",
                "required" => true)),
        "Set Quiz Results for the specified Quiz",
        'POST', false, true);
    expose_function(
        $api_suffix . "remove_quiz_results",
        $class_suffix . "remove_quiz_results",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "quiz_result_array" => array(
                "type" => "array",
                "required" => true)),
        "Remove Quiz Results from the specified Quiz",
        'POST', false, true);
    expose_function(
        $api_suffix . "get_quiz_results",
        $class_suffix . "get_quiz_results",
        array(
            "id" => array(
                "type" => "int",
                "required" => true)),
        "Get Quiz Results from the specified Quiz",
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
        "Add Tags by Id",
        'POST', false, true);
    expose_function(
        $api_suffix . "set_tags",
        $class_suffix . "set_tags",
        array(
            "id" => array(
                "type" => "int",
                "required" => true),
            "tag_array" => array(
                "type" => "array",
                "required" => true)),
        "Set Tags by Id",
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
        "Remove Tags by Id",
        'POST', false, true);
    expose_function(
        $api_suffix . "get_tags",
        $class_suffix . "get_tags",
        array(
            "id" => array(
                "type" => "int",
                "required" => true)),
        "Get Taxonomy Tags from a Quiz",
        'GET', false, true);
}
