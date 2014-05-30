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
 * Class ClipitTrickyTopic
 *
 */
class ClipitTrickyTopic extends UBItem{
    const SUBTYPE = "ClipitTrickyTopic";

    public $subject = "";
    public $country = "";
    public $tag_array = array();


    protected function load_from_elgg($elgg_object){
        parent::load_from_elgg($elgg_object);
        $this->subject = (string)$elgg_object->get("subject");
        $this->country = (string)$elgg_object->get("country");
        $this->tag_array = (array)$elgg_object->get("tag_array");
    }

    protected function copy_to_elgg($elgg_object){
        parent::copy_to_elgg($elgg_object);
        $elgg_object->set("subject", (string)$this->subject);
        $elgg_object->set("country", (string)$this->country);
        $elgg_object->set("tag_array", (array)$this->tag_array);
    }
}