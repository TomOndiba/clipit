<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, URJC JuxtaLearn Team
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 * @subpackage      clipit_api
 */

/**
 * Exposes functions common to ClipIt resource to the REST API
 *
 * @param string $api_suffix The API suffix for a certain class
 * @param string $class_suffix The PHP suffix for a certain class
 *
 * @throws InvalidParameterException
 */
function expose_common_resource_functions($api_suffix, $class_suffix) {
    expose_function(
        $api_suffix . "get_by_tags", $class_suffix . "get_by_tags",
        array("tag_array" => array("type" => "array", "required" => true)),
        "Get the Resources containing at least one of the specified tags", 'GET', false, true
    );
    expose_function(
        $api_suffix . "get_resource_scope", $class_suffix . "get_resource_scope",
        array("id" => array("type" => "int", "required" => true)),
        "Get Resource scope for a Resource ('group', 'activity', 'task' or 'site')", 'GET', false, true
    );
    expose_function(
        $api_suffix . "get_group", $class_suffix . "get_group",
        array("id" => array("type" => "int", "required" => true)), "Get the Group this Resource is inside of", 'GET',
        false, true
    );
    expose_function(
        $api_suffix . "get_activity", $class_suffix . "get_activity",
        array("id" => array("type" => "int", "required" => true)), "Get the Activity this Resource is inside of", 'GET',
        false, true
    );
    expose_function(
        $api_suffix . "get_task", $class_suffix . "get_task", array("id" => array("type" => "int", "required" => true)),
        "Get the Task this Resource is inside of", 'GET', false, true
    );
    expose_function(
        $api_suffix . "get_site", $class_suffix . "get_site", array("id" => array("type" => "int", "required" => true)),
        "Get the Site this Resource is inside of", 'GET', false, true
    );
    expose_function(
        $api_suffix . "add_tags", $class_suffix . "add_tags", array(
            "id" => array("type" => "int", "required" => true),
            "tag_array" => array("type" => "array", "required" => true)
        ), "Add Tags by Id to a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "set_tags", $class_suffix . "set_tags", array(
            "id" => array("type" => "int", "required" => true),
            "tag_array" => array("type" => "array", "required" => true)
        ), "Set Tags by Id to a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "remove_tags", $class_suffix . "remove_tags", array(
            "id" => array("type" => "int", "required" => true),
            "tag_array" => array("type" => "array", "required" => true)
        ), "Remove Tags by Id from a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "get_tags", $class_suffix . "get_tags", array("id" => array("type" => "int", "required" => true)),
        "Get Tags from a Resource", 'GET', false, true
    );
    expose_function(
        $api_suffix . "add_labels", $class_suffix . "add_labels", array(
            "id" => array("type" => "int", "required" => true),
            "label_array" => array("type" => "array", "required" => true)
        ), "Add Labels by Id to a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "set_labels", $class_suffix . "set_labels", array(
            "id" => array("type" => "int", "required" => true),
            "label_array" => array("type" => "array", "required" => true)
        ), "Set Labels by Id to a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "remove_labels", $class_suffix . "remove_labels", array(
            "id" => array("type" => "int", "required" => true),
            "label_array" => array("type" => "array", "required" => true)
        ), "Remove Tags by Id from a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "get_labels", $class_suffix . "get_labels",
        array("id" => array("type" => "int", "required" => true)), "Get Labels from a Resource", 'GET', false, true
    );
    expose_function(
        $api_suffix . "add_performance_items", $class_suffix . "add_performance_items", array(
            "id" => array("type" => "int", "required" => true),
            "performance_item_array" => array("type" => "array", "required" => true)
        ), "Add Performance Items by Id to a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "set_performance_items", $class_suffix . "set_performance_items", array(
            "id" => array("type" => "int", "required" => true),
            "performance_item_array" => array("type" => "array", "required" => true)
        ), "Set Performance Items by Id to a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "remove_performance_items", $class_suffix . "remove_performance_items", array(
            "id" => array("type" => "int", "required" => true),
            "performance_item_array" => array("type" => "array", "required" => true)
        ), "Remove Performance Items by Id from a Resource", 'POST', false, true
    );
    expose_function(
        $api_suffix . "get_performance_items", $class_suffix . "get_performance_items",
        array("id" => array("type" => "int", "required" => true)), "Get Performance Items from a Resource", 'GET',
        false, true
    );
}