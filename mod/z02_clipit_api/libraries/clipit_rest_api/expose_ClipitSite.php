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
 * Expose class functions for the ClipIt REST API
 */
function expose_site_functions() {
    $api_suffix = "clipit.site.";
    $class_suffix = "ClipitSite::";
    expose_function($api_suffix . "get_site",
        $class_suffix . "get_site",
        null,
        "Return the Site object.",
        'GET',
        false,
        true);
    expose_function($api_suffix . "get_site_id",
        $class_suffix . "get_site_id",
        null,
        "Return the Site ID.",
        'GET',
        false,
        true);
    unexpose_function("system.api.list");
    expose_function($api_suffix . "api_list",
        $class_suffix . "api_list",
        null,
        "Return the API method list, including description and required parameters.",
        'GET',
        false,
        false);
    unexpose_function("auth.gettoken");
    expose_function($api_suffix . "get_token",
        $class_suffix . "get_token",
        array("login" => array("type" => "string", "required" => true), "password" => array("type" => "string", "required" => true), "timeout" => array("type" => "int", "required" => false)),
        "Obtain a user authentication token which can be used for authenticating future API calls passing it as the parameter \"auth_token\"",
        'GET',
        false,
        false);
    expose_function($api_suffix . "remove_token",
        $class_suffix . "remove_token",
        array("token" => array("type" => "string", "required" => true)),
        "Remove an API user authentication token from the Site",
        'POST',
        false,
        true);
    expose_function($api_suffix . "lookup",
        $class_suffix . "lookup",
        array("id" => array("type" => "int", "required" => true)),
        "Returns basic information about an unknown object based on its id",
        'GET',
        false,
        true);
    expose_function($api_suffix . "get_domain",
        $class_suffix . "get_domain",
        null,
        "Get the server domain from the Site",
        'GET',
        false,
        true);
    expose_function($api_suffix . "add_storyboards",
        $class_suffix . "add_storyboards",
        array("storyboard_array" => array("type" => "array", "required" => true)),
        "Add Storyboards by Id to the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "set_storyboards",
        $class_suffix . "set_storyboards",
        array("storyboard_array" => array("type" => "array", "required" => true)),
        "Set Storyboards by Id to the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "remove_storyboards",
        $class_suffix . "remove_storyboards",
        array("storyboard_array" => array("type" => "array", "required" => true)),
        "Removes Storyboards by Id from the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "get_storyboards",
        $class_suffix . "get_storyboards",
        null,
        "Gets Storyboards from the Site",
        "GET",
        false,
        true);
    expose_function($api_suffix . "add_videos",
        $class_suffix . "add_videos",
        array("video_array" => array("type" => "array", "required" => true)),
        "Add Videos by Id to the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "set_videos",
        $class_suffix . "set_videos",
        array("video_array" => array("type" => "array", "required" => true)),
        "Set Videos by Id to the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "remove_videos",
        $class_suffix . "remove_videos",
        array("video_array" => array("type" => "array", "required" => true)),
        "Removes Videos by Id from the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "get_videos",
        $class_suffix . "get_videos",
        null,
        "Gets Videos from the Site",
        "GET",
        false,
        true);
    expose_function($api_suffix . "add_files",
        $class_suffix . "add_files",
        array("file_array" => array("type" => "array", "required" => true)),
        "Add Files by Id to the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "set_files",
        $class_suffix . "set_files",
        array("file_array" => array("type" => "array", "required" => true)),
        "Set Files by Id to the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "remove_files",
        $class_suffix . "remove_files",
        array("file_array" => array("type" => "array", "required" => true)),
        "Removes Files by Id from the Site",
        "POST",
        false,
        true);
    expose_function($api_suffix . "get_files",
        $class_suffix . "get_files",
        null,
        "Gets Files from the Site",
        "GET",
        false,
        true);
    expose_function($api_suffix . "publish_to_global",
        $class_suffix . "publish_to_global",
        null, "Publish local Site to Global Site", "POST", false, true);
    expose_function($api_suffix . "update_global_resources",
        $class_suffix . "update_global_resources",
        null, "Update global resources with local public resources", "POST", false, true);
    expose_function($api_suffix . "add_pub_storyboards",
        $class_suffix . "add_pub_storyboards",
        array("storyboard_array" => array("type" => "array", "required" => true)),
        "Add Storyboards by Id to the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "set_pub_storyboards",
        $class_suffix . "set_pub_storyboards",
        array("storyboard_array" => array("type" => "array", "required" => true)),
        "Set Storyboards by Id to the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "remove_pub_storyboards",
        $class_suffix . "remove_pub_storyboards",
        array("storyboard_array" => array("type" => "array", "required" => true)),
        "Removes Storyboards by Id from the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "get_pub_storyboards",
        $class_suffix . "get_pub_storyboards",
        null,
        "Gets Storyboards from the Public Scope",
        "GET",
        false,
        true);
    expose_function($api_suffix . "add_pub_videos",
        $class_suffix . "add_pub_videos",
        array("video_array" => array("type" => "array", "required" => true)),
        "Add Videos by Id to the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "set_pub_videos",
        $class_suffix . "set_pub_videos",
        array("video_array" => array("type" => "array", "required" => true)),
        "Set Videos by Id to the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "remove_pub_videos",
        $class_suffix . "remove_pub_videos",
        array("video_array" => array("type" => "array", "required" => true)),
        "Removes Videos by Id from the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "get_pub_videos",
        $class_suffix . "get_pub_videos",
        null,
        "Gets Videos from the Public Scope",
        "GET",
        false,
        true);
    expose_function($api_suffix . "add_pub_files",
        $class_suffix . "add_pub_files",
        array("file_array" => array("type" => "array", "required" => true)),
        "Add Files by Id to the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "set_pub_files",
        $class_suffix . "set_pub_files",
        array("file_array" => array("type" => "array", "required" => true)),
        "Set Files by Id to the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "remove_pub_files",
        $class_suffix . "remove_pub_files",
        array("file_array" => array("type" => "array", "required" => true)),
        "Removes Files by Id from the Public Scope",
        "POST",
        false,
        true);
    expose_function($api_suffix . "get_pub_files",
        $class_suffix . "get_pub_files",
        null,
        "Gets Files from the Public Scope",
        "GET",
        false,
        true);
}
