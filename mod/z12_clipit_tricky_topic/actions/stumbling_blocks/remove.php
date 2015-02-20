<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   18/12/2014
 * Last update:     18/12/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$id = get_input('id');

if(ClipitTag::delete_by_id(array($id))){
    system_message(elgg_echo('tag:removed'));
} else {
    register_error(elgg_echo("tag:cantremove"));
}
forward("tricky_topics/stumbling_blocks");