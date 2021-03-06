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
 * A complete User Rating linked to a published Resource.
 */
class ClipitRating extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitRating";
    const REL_RATING_TAGRATING = "ClipitRating-ClipitTagRating";
    const REL_RATING_RUBRICRATING = "ClipitRating-ClipitRubricRating";
    /**
     * @var int Rating target (ClipitVideo or ClipitFile)
     */
    public $target = 0;
    /**
     * @var int Overall rating opinion (YES = true, NO = false=
     */
    public $overall = false;
    /**
     * @var array Ratings about Tags used
     */
    public $tag_rating_array = array();
    /**
     * @var array Ratings based on Rubric Items
     */
    public $rubric_rating_array = array();

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity){
        parent::copy_from_elgg($elgg_entity);
        $this->target = (int)$elgg_entity->get("target");
        $this->overall = (bool)$elgg_entity->get("overall");
        $this->tag_rating_array = (array)static::get_tag_ratings($this->id);
        $this->rubric_rating_array = (array)static::get_rubric_ratings($this->id);
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity){
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("target", (int)$this->target);
        $elgg_entity->set("overall", (bool)$this->overall);
    }

    /**
     * Saves this instance to the system.
     * @param  bool $double_save if $double_save is true, this object is saved twice to ensure that all properties are updated properly. E.g. the time created property can only beset on ElggObjects during an update. Defaults to false!
     * @return bool|int Returns the Id of the saved instance, or false if error
     */
    protected function save($double_save = false){
        parent::save($double_save);
        static::set_tag_ratings($this->id, (array)$this->tag_rating_array);
        static::set_rubric_ratings($this->id, (array)$this->rubric_rating_array);
        return $this->id;
    }

    static function get_target($id){
        $prop_value_array = static::get_properties($id, array("target"));
        if (empty($prop_value_array)) {
            return null;
        }
        return (int)$prop_value_array["target"];
    }

    /**
     * Get Ratings made by a User for a Target
     *
     * @param int $user_id User ID
     * @param int $target_id Target ID
     *
     * @return ClipitRating|null Returns a Rating, or null if any.
     */
    static function get_user_rating_for_target($user_id, $target_id){
        $rating = elgg_get_entities_from_metadata(array(
            'type' => static::TYPE,
            'subtype' => static::SUBTYPE,
            'metadata_names' => array("target"),
            'metadata_values' => array($target_id),
            'owner_guid' => $user_id
        ));
        if (empty($rating)) {
            return null;
        }
        $rating_id = array_pop($rating)->guid;
        return new static($rating_id);
    }

    /**
     * Get the average overall rating
     *
     * @param int $target_id ID of target to return overall rating from
     *
     * @return float Average overall rating [0.0-1.0]
     */
    static function get_average_rating_for_target($target_id){
        $rating_array = static::get_by_target(array($target_id));
        $rating_array = $rating_array[$target_id];
        $average_rating = 0;
        $count = 0;
        if (!empty($rating_array)) {
            foreach ($rating_array as $rating) {
                if ($rating->overall === true) {
                    $average_rating++;
                }
                $count++;
            }
        }
        if (empty($count)) {
            return null;
        }
        return $average_rating = $average_rating / $count;
    }

    /**
     * Get Ratings by Target
     *
     * @param array $target_array Array of Target IDs
     *
     * @return static[] Array of [target] => array(Ratings)
     */
    static function get_by_target($target_array) {
        $rating_array = array();
        foreach($target_array as $target_id) {
            $elgg_objects = elgg_get_entities_from_metadata(
                array(
                    'type' => static::TYPE, 'subtype' => static::SUBTYPE, 'metadata_names' => array("target"),
                    'metadata_values' => array($target_id), 'limit' => 0
                )
            );
            if(!empty($elgg_objects)) {
                $temp_array = array();
                foreach($elgg_objects as $elgg_object) {
                    $temp_array[] = new static($elgg_object->guid);
                }
                $rating_array[$target_id] = $temp_array;
            } else {
                $rating_array[$target_id] = null;
            }
        }
        return $rating_array;
    }

    static function add_tag_ratings($id, $tag_rating_array){
        return UBCollection::add_items($id, $tag_rating_array, static::REL_RATING_TAGRATING, true);
    }

    static function remove_tag_ratings($id, $tag_rating_array){
        return UBCollection::remove_items($id, $tag_rating_array, static::REL_RATING_TAGRATING);
    }

    static function add_rubric_ratings($id, $rubric_rating_array){
        return UBCollection::add_items($id, $rubric_rating_array, static::REL_RATING_RUBRICRATING, true);
    }

    static function remove_rubric_ratings($id, $rubric_rating_array){
        return UBCollection::remove_items($id, $rubric_rating_array, static::REL_RATING_RUBRICRATING);
    }

    static function set_tag_ratings($id, $tag_rating_array){
        return UBCollection::set_items($id, $tag_rating_array, static::REL_RATING_TAGRATING);
    }

    static function set_rubric_ratings($id, $rubric_rating_array){
        return UBCollection::set_items($id, $rubric_rating_array, static::REL_RATING_RUBRICRATING);
    }

    static function get_tag_ratings($id) {
        return UBCollection::get_items($id, static::REL_RATING_TAGRATING);
    }

    static function get_rubric_ratings($id){
        return UBCollection::get_items($id, static::REL_RATING_RUBRICRATING);
    }
}

