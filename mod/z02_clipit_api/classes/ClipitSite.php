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
 * The Site class, which is unique (only one instance) and holds general Site information and Site-layer Resources.
 */
class ClipitSite extends UBSite {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitSite";
    const REL_SITE_FILE = "ClipitSite-ClipitFile";
    const REL_SITE_VIDEO = "ClipitSite-ClipitVideo";
    const REL_SITE_STORYBOARD = "ClipitSite-ClipitStoryboard";
    public $file_array = array();
    public $video_array = array();
    public $storyboard_array = array();

    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->file_array = (array)static::get_files();
        $this->video_array = (array)static::get_videos();
        $this->storyboard_array = (array)static::get_storyboards();
    }

    /**
     * Saves Site parameters into Elgg
     * @return int Site ID
     */
    protected function save() {
        $site_id = parent::save();
        static::set_files($this->file_array);
        static::set_videos($this->video_array);
        static::set_storyboards($this->storyboard_array);
        return $site_id;
    }

    // FILES
    static function add_files($file_array) {
        $id = static::get_site_id();
        return UBCollection::add_items($id, $file_array, static::REL_SITE_FILE);
    }

    static function set_files($file_array) {
        $id = static::get_site_id();
        return UBCollection::set_items($id, $file_array, static::REL_SITE_FILE);
    }

    static function remove_files($file_array) {
        $id = static::get_site_id();
        return UBCollection::remove_items($id, $file_array, static::REL_SITE_FILE);
    }

    static function get_files() {
        $id = static::get_site_id();
        return UBCollection::get_items($id, static::REL_SITE_FILE);
    }

    // VIDEOS
    static function add_videos($video_array) {
        $id = static::get_site_id();
        return UBCollection::add_items($id, $video_array, static::REL_SITE_VIDEO);
    }

    static function set_videos($video_array) {
        $id = static::get_site_id();
        return UBCollection::set_items($id, $video_array, static::REL_SITE_VIDEO);
    }

    static function remove_videos($video_array) {
        $id = static::get_site_id();
        return UBCollection::remove_items($id, $video_array, static::REL_SITE_VIDEO);
    }

    static function get_videos() {
        $id = static::get_site_id();
        return UBCollection::get_items($id, static::REL_SITE_VIDEO);
    }

    // STORYBOARDS
    static function add_storyboards($storyboard_array) {
        $id = static::get_site_id();
        return UBCollection::add_items($id, $storyboard_array, static::REL_SITE_STORYBOARD);
    }

    static function set_storyboards($storyboard_array) {
        $id = static::get_site_id();
        return UBCollection::set_items($id, $storyboard_array, static::REL_SITE_STORYBOARD);
    }

    static function remove_storyboards($storyboard_array) {
        $id = static::get_site_id();
        return UBCollection::remove_items($id, $storyboard_array, static::REL_SITE_STORYBOARD);
    }

    static function get_storyboards() {
        $id = static::get_site_id();
        return UBCollection::get_items($id, static::REL_SITE_STORYBOARD);
    }
}