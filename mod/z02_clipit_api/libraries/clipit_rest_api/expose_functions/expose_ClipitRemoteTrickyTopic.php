<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 * @author          Pablo Llin�s Arnaiz <pebs74@gmail.com>, URJC JuxtaLearn Team
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 * @subpackage      clipit_api
 */
/**
 * Expose class functions for the ClipIt REST API
 */
function expose_remote_tricky_topic_functions() {
    $api_suffix = "clipit.remote_tricky_topic.";
    $class_suffix = "ClipitRemoteTrickyTopic::";
    expose_common_remote_resource_functions($api_suffix, $class_suffix);
}