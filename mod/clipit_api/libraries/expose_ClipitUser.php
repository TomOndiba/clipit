<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 21/05/14
 * Time: 16:04
 */

function expose_user_functions(){
    $api_suffix = "clipit.user.";
    $class_suffix = "ClipitUser::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_function(
        $api_suffix . "get_by_login",
        $class_suffix . "get_by_login",
        array(
            "login_array" => array(
                "type" => "array",
                "required" => true)),
        "Get all instances by Login",
        'GET', false, true);
    expose_function(
        $api_suffix . "get_by_email",
        $class_suffix . "get_by_email",
        array(
            "email_array" => array(
                "type" => "array",
                "required" => true)),
        "Get all instances by email. The result is a nested array, with an array of users per email.",
        'GET', false, true);
    expose_function(
        $api_suffix . "get_by_role",
        $class_suffix . "get_by_role",
        array(
            "role_array" => array(
                "type" => "array",
                "required" => true)),
        "Get all instances by role. The result is a nested array, with an array of users per role.",
        'GET', false, true);
    expose_function(
        $api_suffix . "get_groups",
        $class_suffix . "get_groups",
        array(
            "user_id" => array(
                "type" => "int",
                "required" => true)),
        "Get all Group Ids in which this user is a member of.",
        'GET', false, true);
    expose_function(
        $api_suffix . "set_role_student",
        $class_suffix . "set_role_student",
        array(
            "user_id" => array(
                "type" => "int",
                "required" => true)),
        "Set the Role of a User to Student.",
        'POST', false, true);
    expose_function(
        $api_suffix . "set_role_teacher",
        $class_suffix . "set_role_teacher",
        array(
            "user_id" => array(
                "type" => "int",
                "required" => true)),
        "Set the Role of a User to Student.",
        'POST', false, true);
    expose_function(
        $api_suffix . "set_role_admin",
        $class_suffix . "set_role_admin",
        array(
            "user_id" => array(
                "type" => "int",
                "required" => true)),
        "Set the Role of a User to Student.",
        'POST', false, true);
    expose_function(
        $api_suffix . "get_last_login",
        $class_suffix . "get_last_login",
        array(
            "user_id" => array(
                "type" => "int",
                "required" => true)),
        "Get the last login time for a User.",
        "GET", false, true);
}
