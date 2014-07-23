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
function expose_tag_functions() {
    $api_suffix = "clipit.tag.";
    $class_suffix = "ClipitTag::";
    expose_common_functions($api_suffix, $class_suffix);
    expose_function(
        $api_suffix . "get_tricky_topics", $class_suffix . "get_tricky_topics",
        array("id" => array("type" => "int", "required" => true)), "Get Tricky Topics which contain a Tag", 'GET',
        false, true
    );
}
