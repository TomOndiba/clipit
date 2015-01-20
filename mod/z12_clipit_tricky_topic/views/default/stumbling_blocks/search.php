<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   19/01/2015
 * Last update:     19/01/2015
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$query = stripslashes(get_input('q', get_input('term', '')));
foreach(ClipitTag::get_from_search($query) as $label){
    $output[] = $label->name;
}
$output = array_slice($output, 0, 10);
echo json_encode($output);
die();