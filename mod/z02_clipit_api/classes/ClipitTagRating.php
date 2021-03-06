<?php
/**
 * Clipit - Clipit Web Space
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, URJC Clipit Team
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 * @subpackage      clipit_api
 */

/**
 * A rating of whether a Tag (Stumbling Block) has correctly been covered in a Resource. It is contained inside of a
 * ClipitRating instance which points to a specific Resource.
 */
class ClipitTagRating extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitTagRating";

    // ClipitRating where this Tag Rating is included in
    public $rating = 0;
    /**
     * @var int ID of the Tag that this rating refers to.
     */
    public $tag_id = 0;
    /**
     * @var bool Defines whether the linked Tag has been correctly covered or not.
     */
    public $is_used = false;

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->tag_id = (int)$elgg_entity->get("tag_id");
        $this->is_used = (bool)$elgg_entity->get("is_used");
        $rating_array = UBCollection::get_items($this->id, ClipitRating::REL_RATING_TAGRATING, true);
        if(!empty($rating_array)) {
            $this->rating = (int)array_pop($rating_array);
        }
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity) {
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("tag_id", (int)$this->tag_id);
        $elgg_entity->set("is_used", (bool)$this->is_used);
    }

    static function get_by_tag($tag_array) {
        $tag_rating_array = array();
        foreach($tag_array as $tag_id) {
            $elgg_objects = elgg_get_entities_from_metadata(
                array(
                    'type' => static::TYPE, 'subtype' => static::SUBTYPE, 'metadata_names' => array("tag_id"),
                    'metadata_values' => array($tag_id), 'limit' => 0
                )
            );
            if(!empty($elgg_objects)) {
                $temp_array = array();
                foreach($elgg_objects as $elgg_object) {
                    $temp_array[] = new static($elgg_object->guid, $elgg_object);
                }
                $tag_rating_array[$tag_id] = $temp_array;
            } else {
                $tag_rating_array[$tag_id] = null;
            }
        }
        return $tag_rating_array;
    }

    static function get_average_rating_for_target($target_id) {
        $rating_array = ClipitRating::get_by_target(array($target_id));
        $rating_array = $rating_array[$target_id];
        $average_rating = 0;
        $count = 0;
        if(!empty($rating_array)) {
            foreach ($rating_array as $rating) {
                foreach ($rating->tag_rating_array as $tag_rating_id) {
                    $tag_rating = new static($tag_rating_id);
                    if ($tag_rating->is_used) {
                        $average_rating++;
                    }
                    $count++;
                }
            }
        }
        if(!empty($count)) {
            return $average_rating = $average_rating / $count;
        } else {
            return null;
        }
    }

    /**
     * @param $target_id
     * @return array
     * @throws InvalidParameterException
     */
    static function get_item_average_rating_for_target($target_id) {
        $rating_array = ClipitRating::get_by_target(array($target_id));
        $rating_array = $rating_array[$target_id];
        $average_rating = array();
        $count = array();
        if(!empty($rating_array)) {
            foreach($rating_array as $rating) {
                foreach($rating->tag_rating_array as $tag_rating_id) {
                    $tag_rating = new static($tag_rating_id);
                    $tag_id = (int)$tag_rating->tag_id;
                    $is_used = (bool)$tag_rating->is_used;
                    if(!isset($average_rating[$tag_id])){
                        if($is_used) {
                            $average_rating[$tag_id] = 1;
                        }
                        $count[$tag_id] = 1;
                    } else{
                        if($is_used) {
                            $average_rating[$tag_id]++;
                        }
                        $count[$tag_id]++;
                    }
                }
            }
            if(!empty($count)) {
                foreach($count as $tag_id => $total) {
                    $average_rating[$tag_id] = $average_rating[$tag_id] / $total;
                }
            }
        }
        return $average_rating;
    }

    static function get_average_user_rating_for_target($user_id, $target_id) {
        $rating = ClipitRating::get_user_rating_for_target($user_id, $target_id);
        $average_rating = 0;
        $count = 0;
        if(!empty($rating)) {
            foreach ($rating->tag_rating_array as $tag_rating_id) {
                $tag_rating = new static($tag_rating_id);
                if ($tag_rating->is_used) {
                    $average_rating++;
                }
                $count++;
            }
        }
        if(!empty($count)) {
            return $average_rating = $average_rating / $count;
        } else {
            return null;
        }
    }
}