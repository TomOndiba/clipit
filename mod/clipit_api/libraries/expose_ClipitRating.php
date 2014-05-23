<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 16:02
 */

function expose_rating_functions(){
    $api_suffix = "clipit.rating.";
    $class_suffix = "ClipitRating::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_function(
        $api_suffix . "get_user_ratings",
        $class_suffix . "get_user_ratings",
        array(
            "user_id" => array(
                "type" => "int",
                "required" => true),
            "target_id" => array(
                "type" => "int",
                "required" => false)),
        "Return all ratings made by a user, optionally the rating for a target if specified",
        'GET', false, true);
    expose_function(
        $api_suffix . "add_tag_ratings",
        $class_suffix . "add_tag_ratings",
        array(
            "rating_id" => array(
                "type" => "int",
                "required" => true),
            "tag_rating_array" => array(
                "type" => "array",
                "required" => true)),
        "Add Tag Ratings to a Rating.",
        'POST', false, true);
    expose_function(
        $api_suffix . "set_tag_ratings",
        $class_suffix . "set_tag_ratings",
        array(
            "rating_id" => array(
                "type" => "int",
                "required" => true),
            "tag_rating_array" => array(
                "type" => "array",
                "required" => true)),
        "Set Tag Ratings to a Rating.",
        'POST', false, true);
    expose_function(
        $api_suffix . "remove_tag_ratings",
        $class_suffix . "remove_tag_ratings",
        array(
            "rating_id" => array(
                "type" => "int",
                "required" => true),
            "tag_rating_array" => array(
                "type" => "array",
                "required" => true)),
        "Remove Tag Ratings from a Rating.",
        'POST', false, true);
    expose_function(
        $api_suffix . "get_tag_ratings",
        $class_suffix . "get_tag_ratings",
        array(
            "rating_id" => array(
                "type" => "int",
                "required" => true)),
        "Get Tag Ratings from a Rating.",
        'GET', false, true);
}
