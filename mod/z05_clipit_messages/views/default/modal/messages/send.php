<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   22/04/14
 * Last update:     22/04/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$user_id_to = (int)get_input("id");
$user_id = elgg_get_logged_in_user_guid();

$user_to = array_pop(ClipitUser::get_by_id(array($user_id_to)));
if($user_to && $user_id_to != $user_id){
    echo elgg_view_form('messages/compose', array('data-validate'=> "true"),  array("entity" => $user_to ));
}