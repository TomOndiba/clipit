<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   26/06/14
 * Last update:     26/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entity_id = elgg_extract("entity_id", $vars);
$object = ClipitSite::lookup($entity_id);
$entity = array_pop($object['subtype']::get_by_id(array($entity_id)));
?>
<div class="multimedia-preview attach-preview">
    <?php switch($object['subtype']){
         case "ClipitFile":
            echo elgg_view("multimedia/file/preview", array('file' => $entity, 'size' => 'normal'));
         break;
        case "ClipitStoryboard":
            $entity = array_pop(ClipitFile::get_by_id(array($entity->file)));
            echo elgg_view("multimedia/file/preview", array('file' => $entity, 'size' => 'normal'));
        break;
        case "ClipitVideo":
            echo elgg_view("multimedia/video/preview", array('entity' => $entity));
            break;
    }
    ?>
</div>