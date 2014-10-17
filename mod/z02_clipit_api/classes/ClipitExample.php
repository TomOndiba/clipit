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
 * An Example experience in which students have trouble understanding a Stumbling Block (or Tag).
 */
class ClipitExample extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitExample";
    const REL_EXAMPLE_TAG = "ClipitExample-ClipitTag";
    public $tag_array = array();
    public $resource_url = "";

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->resource_url = (string)$elgg_entity->get("resource_url");
        $this->tag_array = (array)static::get_tags($this->id);
    }

    /**
     * Copy $this object's parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity) {
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("resource_url", (string)$this->resource_url);
    }

    protected function save($double_save=false) {
        parent::save($double_save);
        static::set_tags($this->id, (array)$this->tag_array);
        return $this->id;
    }

    /**
     * Adds Tags to an Example, referenced by Id.
     *
     * @param int   $id        Id from the Example to add Tags to
     * @param array $tag_array Array of Tag Ids to be added to the Example
     *
     * @return bool Returns true if success, false if error
     */
    static function add_tags($id, $tag_array) {
        return UBCollection::add_items($id, $tag_array, static::REL_EXAMPLE_TAG);
    }

    /**
     * Sets Tags to an Example, referenced by Id.
     *
     * @param int   $id        Id from the Example to set Tags to
     * @param array $tag_array Array of Tag Ids to be set to the Example
     *
     * @return bool Returns true if success, false if error
     */
    static function set_tags($id, $tag_array) {
        return UBCollection::set_items($id, $tag_array, static::REL_EXAMPLE_TAG);
    }

    /**
     * Remove Tags from an Example.
     *
     * @param int   $id        Id from Example to remove Tags from
     * @param array $tag_array Array of Tag Ids to remove from Example
     *
     * @return bool Returns true if success, false if error
     */
    static function remove_tags($id, $tag_array) {
        return UBCollection::remove_items($id, $tag_array, static::REL_EXAMPLE_TAG);
    }

    /**
     * Get all Tags from an Example
     *
     * @param int $id Id of the Example to get Tags from
     *
     * @return array|bool Returns an array of Tag IDs, or false if error
     */
    static function get_tags($id) {
        return UBCollection::get_items($id, static::REL_EXAMPLE_TAG);
    }
}