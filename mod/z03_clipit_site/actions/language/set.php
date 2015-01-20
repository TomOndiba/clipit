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
$new_lang_id = get_input("lang");
$installed = get_installed_translations();
if(!isset($user_id)) {
    $user_id = elgg_get_logged_in_user_guid();
}
if(array_key_exists($new_lang_id, $installed) && !empty($new_lang_id)){
    if(!elgg_is_logged_in()){
        setcookie('client_language', $new_lang_id, time()+60*60*24*30, '/'); // expires: 30 days
    } else{
        ClipitUser::set_properties($user_id, array('language' => $new_lang_id));
    }
}


if(!get_input('no_forward')){
    forward(REFERER);
}