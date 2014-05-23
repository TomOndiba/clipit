<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 16:01
 */

function expose_performance_palette_functions(){
    $api_suffix = "clipit.performance_palette.";
    $class_suffix = "ClipitPerformancePalette::";
    expose_function(
        $api_suffix . "get_performance_palette",
        $class_suffix . "get_performance_palette",
        null,
        "Get the JuxtaLearn Performance Palette",
        'GET', false, true);
    expose_function(
        $api_suffix . "add_performance_items",
        $class_suffix . "add_performance_items",
        array(
            "performance_items" => array(
                "type" => "array",
                "required" => true)),
        "Add Performance Items to the Performance Palette",
        'POST', false, true);
}
