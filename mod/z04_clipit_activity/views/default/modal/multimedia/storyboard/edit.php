<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   7/05/14
 * Last update:     7/05/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$id = (int)get_input("id");
$user_id = elgg_get_logged_in_user_guid();
$storyboard = array_pop(ClipitStoryboard::get_by_id(array($id)));

if($storyboard && $storyboard->owner_id == $user_id){
    $file = array_pop(ClipitFile::get_by_id(array($storyboard->file)));
    echo elgg_view_form('multimedia/storyboards/edit', array('data-validate'=> "true" ), array(
        'entity'  => $storyboard,
        'file' => $file
    ));
}