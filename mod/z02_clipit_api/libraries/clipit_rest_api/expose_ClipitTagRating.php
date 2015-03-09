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
function expose_tag_rating_functions() {
    $api_suffix = "clipit.tag_rating.";
    $class_suffix = "ClipitTagRating::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_function(
        $api_suffix . "get_average_rating_for_target", $class_suffix . "get_average_rating_for_target",
        array("target_id" => array("type" => "int", "required" => true)),
        "Get the average tag rating for a target", 'GET', false, true
    );
    expose_function(
        $api_suffix . "get_item_average_rating_for_target", $class_suffix . "get_item_average_rating_for_target", array(
        "target_id" => array("type" => "int", "required" => true)
    ), "Get the average tag rating by tag for a target", 'GET', false, true
    );
    expose_function(
        $api_suffix . "get_average_user_rating_for_target", $class_suffix . "get_average_user_rating_for_target", array(
        "user_id" => array("type" => "int", "required" => true),
        "target_id" => array("type" => "int", "required" => true)
    ), "Get the average tag rating from a user for a target", 'GET', false, true
    );
}
