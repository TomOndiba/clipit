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
 * A main topic to explain through videos, which can be decomposed into tags (Stumbling Blocks).
 */
class ClipitTrickyTopic extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitTrickyTopic";
    const REL_TRICKYTOPIC_TAG = "ClipitTrickyTopic-ClipitTag";
    const REL_TRICKYTOPIC_FILE = "ClipitTrickyTopic-ClipitFile";
    const REL_TRICKYTOPIC_STORYBOARD = "ClipitTrickyTopic-ClipitStoryboard";
    const REL_TRICKYTOPIC_VIDEO = "ClipitTrickyTopic-ClipitVideo";
    public $tag_array = array();
    public $subject = "";
    public $education_level = 0;
    // Linked Teacher Resources
    public $storyboard_array = array();
    public $video_array = array();
    public $file_array = array();

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->subject = (string)$elgg_entity->get("subject");
        $this->education_level = (int)$elgg_entity->get("education_level");
        $this->tag_array = (array)static::get_tags((int)$this->id);
        $this->file_array = (array)static::get_files((int)$this->id);
        $this->storyboard_array = (array)static::get_storyboards((int)$this->id);
        $this->video_array = (array)static::get_videos((int)$this->id);
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity) {
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("subject", (string)$this->subject);
        $elgg_entity->set("education_level", (int)$this->education_level);
    }

    /**
     * Saves this instance to the system.
     * @param  bool $double_save if $double_save is true, this object is saved twice to ensure that all properties are updated properly. E.g. the time created property can only beset on ElggObjects during an update. Defaults to false!
     * @return bool|int Returns the Id of the saved instance, or false if error
     */
    protected function save($double_save=false) {
        parent::save($double_save);
        static::set_tags((int)$this->id, (array)$this->tag_array);
        static::set_files((int)$this->id, (array)$this->file_array);
        static::set_storyboards((int)$this->id, (array)$this->storyboard_array);
        static::set_videos((int)$this->id, (array)$this->video_array);
        return (int)$this->id;
    }

    /**
     * Adds Tags to a Tricky Topic, referenced by Id.
     *
     * @param int   $id        Id from the Tricky Topic to add Tags to
     * @param array $tag_array Array of Tag Ids to be added to the Tricky Topic
     *
     * @return bool Returns true if success, false if error
     */
    static function add_tags($id, $tag_array) {
        return UBCollection::add_items($id, $tag_array, static::REL_TRICKYTOPIC_TAG);
    }

    /**
     * Sets Tags to a Tricky Topic, referenced by Id.
     *
     * @param int   $id        Id from the Tricky Topic to set Tags to
     * @param array $tag_array Array of Tag Ids to be set to the Tricky Topic
     *
     * @return bool Returns true if success, false if error
     */
    static function set_tags($id, $tag_array) {
        return UBCollection::set_items($id, $tag_array, static::REL_TRICKYTOPIC_TAG);
    }

    /**
     * Remove Tags from a Tricky Topic.
     *
     * @param int   $id        Id from Tricky Topic to remove Tags from
     * @param array $tag_array Array of Tag Ids to remove from Tricky Topic
     *
     * @return bool Returns true if success, false if error
     */
    static function remove_tags($id, $tag_array) {
        return UBCollection::remove_items($id, $tag_array, static::REL_TRICKYTOPIC_TAG);
    }

    /**
     * Get all Tags from a Tricky Topic
     *
     * @param int $id Id of the Tricky Topic to get Tags from
     *
     * @return array|bool Returns an array of Tag IDs, or false if error
     */
    static function get_tags($id) {
        return UBCollection::get_items($id, static::REL_TRICKYTOPIC_TAG);
    }

    // TEACHER RESOURCES: STORYBOARDS
    static function add_storyboards($id, $storyboard_array) {
        return UBCollection::add_items($id, $storyboard_array, static::REL_TRICKYTOPIC_STORYBOARD);
    }

    static function set_storyboards($id, $storyboard_array) {
        return UBCollection::set_items($id, $storyboard_array, static::REL_TRICKYTOPIC_STORYBOARD);
    }

    static function remove_storyboards($id, $storyboard_array) {
        return UBCollection::remove_items($id, $storyboard_array, static::REL_TRICKYTOPIC_STORYBOARD);
    }

    static function get_storyboards($id) {
        return UBCollection::get_items($id, static::REL_TRICKYTOPIC_STORYBOARD);
    }

    // TEACHER RESOURCES: VIDEOS
    static function add_videos($id, $video_array) {
        return UBCollection::add_items($id, $video_array, static::REL_TRICKYTOPIC_VIDEO);
    }

    static function set_videos($id, $video_array) {
        return UBCollection::set_items($id, $video_array, static::REL_TRICKYTOPIC_VIDEO);
    }

    static function remove_videos($id, $video_array) {
        return UBCollection::remove_items($id, $video_array, static::REL_TRICKYTOPIC_VIDEO);
    }

    static function get_videos($id) {
        return UBCollection::get_items($id, static::REL_TRICKYTOPIC_VIDEO);
    }

    // TEACHER RESOURCES: FILES
    static function add_files($id, $file_array) {
        return UBCollection::add_items($id, $file_array, static::REL_TRICKYTOPIC_FILE);
    }

    static function set_files($id, $file_array) {
        return UBCollection::set_items($id, $file_array, static::REL_TRICKYTOPIC_FILE);
    }

    static function remove_files($id, $file_array) {
        return UBCollection::remove_items($id, $file_array, static::REL_TRICKYTOPIC_FILE);
    }

    static function get_files($id) {
        return UBCollection::get_items($id, static::REL_TRICKYTOPIC_FILE);
    }
}