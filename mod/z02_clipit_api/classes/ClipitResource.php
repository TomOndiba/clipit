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
 * An extensible class which holds common functionality and properties for Resource objects such as Videos or
 * Storyboards.
 */
class ClipitResource extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitResource";
    const REL_RESOURCE_TAG = "ClipitResource-ClipitTag";
    const REL_RESOURCE_LABEL = "ClipitResource-ClipitLabel";
    const REL_RESOURCE_PERFORMANCE = "ClipitResource-ClipitPerformanceItem";
    const REL_GROUP_RESOURCE = "ClipitGroup-ClipitResource";
    const REL_ACTIVITY_RESOURCE = "ClipitActivity-ClipitResource";
    const REL_SITE_RESOURCE = "ClipitSite-ClipitResource";
    const REL_TASK_RESOURCE = "ClipitTask-ClipitResource";

    public $tag_array = array();
    public $label_array = array();
    public $performance_item_array = array();

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->tag_array = (array)static::get_tags($this->id);
        $this->label_array = (array)static::get_labels($this->id);
        $this->performance_item_array = (array)static::get_performance_items($this->id);
    }

    /**
     * Saves this instance into the system.
     * @return bool|int Returns id of saved instance, or false if error.
     */
    protected function save() {
        parent::save();
        static::set_tags($this->id, (array)$this->tag_array);
        static::set_labels($this->id, (array)$this->label_array);
        static::set_performance_items($this->id, (array)$this->performance_item_array);
        return $this->id;
    }

    static function get_by_tags($tag_array) {
        $return_array = array();
        $all_items = static::get_all(0, true); // Get all item ids, not objects
        foreach($all_items as $item_id) {
            $item_tags = (array)static::get_tags((int)$item_id);
            foreach($tag_array as $search_tag) {
                if(array_search($search_tag, $item_tags) !== false) {
                    $return_array[(int)$item_id] = new static((int)$item_id);
                    break;
                }
            }
        }
        return $return_array;
    }

    static function get_by_labels($label_array) {
        $return_array = array();
        $all_items = static::get_all(0, true); // Get all item ids, not objects
        foreach($all_items as $item_id) {
            $item_labels = (array)static::get_labels((int)$item_id);
            foreach($label_array as $search_tag) {
                if(array_search($search_tag, $item_labels) !== false) {
                    $return_array[(int)$item_id] = new static((int)$item_id);
                    break;
                }
            }
        }
        return $return_array;
    }

    static function get_by_performance_items($performance_item_array) {
        $return_array = array();
        $all_items = static::get_all(0, true); // Get all item ids, not objects
        foreach($all_items as $item_id) {
            $item_performance_items = static::get_performance_items((int)$item_id);
            foreach($performance_item_array as $search_tag) {
                if(array_search($search_tag, $item_performance_items) !== false) {
                    $return_array[(int)$item_id] = new static((int)$item_id);
                    break;
                }
            }
        }
        return $return_array;
    }

    static function get_resource_scope($id) {
        $site = static::get_site($id);
        if(!empty($site)) {
            return "site";
        }
        $task = static::get_task($id);
        if(!empty($task)) {
            return "task";
        }
        $group = static::get_group($id);
        if(!empty($group)) {
            return "group";
        }
        $activity = static::get_activity($id);
        if(!empty($activity)) {
            return "activity";
        }
        return null;
    }

    /**
     * Get the Group where a Resource is located
     *
     * @param int $id Resource ID
     *
     * @return int|null Returns the Group ID, or null if none.
     */
    static function get_group($id) {
        $resource = new static($id);
        if(!empty($resource->cloned_from)) {
            return static::get_group($resource->cloned_from);
        }
        $group = UBCollection::get_items($id, static::REL_GROUP_RESOURCE, true);
        if(!empty($group)) {
            return (int)array_pop($group);
        } else {
            return null;
        }
    }

    static function get_task($id) {
        $task = UBCollection::get_items($id, static::REL_TASK_RESOURCE, true);
        if(!empty($task)) {
            return array_pop($task);
        } else {
            return null;
        }
    }

    static function get_activity($id) {
        $group_id = static::get_group($id);
        if(!empty($group_id)) {
            return ClipitGroup::get_activity($group_id);
        } else {
            $activity = UBCollection::get_items($id, static::REL_ACTIVITY_RESOURCE, true);
            if(!empty($activity)) {
                return array_pop($activity);
            }
        }
        return null;
    }

    static function get_site($id) {
        $site = UBCollection::get_items($id, static::REL_SITE_RESOURCE, true);
        if(!empty($site)) {
            return array_pop($site);
        } else {
            return null;
        }
    }

    /**
     * Adds Tags to a Resource, referenced by Id.
     *
     * @param int   $id        Id from the Resource to add Tags to
     * @param array $tag_array Array of Tag Ids to be added to the Resource
     *
     * @return bool Returns true if success, false if error
     */
    static function add_tags($id, $tag_array) {
        return UBCollection::add_items($id, $tag_array, static::REL_RESOURCE_TAG);
    }

    /**
     * Sets Tags to a Resource, referenced by Id.
     *
     * @param int   $id        Id from the Resource to set Tags to
     * @param array $tag_array Array of Tag Ids to be set to the Resource
     *
     * @return bool Returns true if success, false if error
     */
    static function set_tags($id, $tag_array) {
        return UBCollection::set_items($id, $tag_array, static::REL_RESOURCE_TAG);
    }

    /**
     * Remove Tags from a Resource.
     *
     * @param int   $id        Id from Resource to remove Tags from
     * @param array $tag_array Array of Tag Ids to remove from Resource
     *
     * @return bool Returns true if success, false if error
     */
    static function remove_tags($id, $tag_array) {
        return UBCollection::remove_items($id, $tag_array, static::REL_RESOURCE_TAG);
    }

    /**
     * Get all Tags from a Resource
     *
     * @param int $id Id of the Resource to get Tags from
     *
     * @return array|bool Returns an array of Tag IDs, or false if error
     */
    static function get_tags($id) {
        return UBCollection::get_items($id, static::REL_RESOURCE_TAG);
    }

    /**
     * Add Labels to a Resource.
     *
     * @param int   $id          Id of the Resource to add Labels to.
     * @param array $label_array Array of Label Ids to add to the Resource.
     *
     * @return bool Returns true if added correctly, or false if error.
     */
    static function add_labels($id, $label_array) {
        return UBCollection::add_items($id, $label_array, static::REL_RESOURCE_LABEL);
    }

    /**
     * Set Labels to a Resource.
     *
     * @param int   $id          Id of the Resource to set Labels to.
     * @param array $label_array Array of Label Ids to set to the Resource.
     *
     * @return bool Returns true if added correctly, or false if error.
     */
    static function set_labels($id, $label_array) {
        return UBCollection::set_items($id, $label_array, static::REL_RESOURCE_LABEL);
    }

    /**
     * Remove Labels from a Resource.
     *
     * @param int   $id          Id of the Resource to remove Labels from.
     * @param array $label_array Array of Label Ids to remove from the Resource.
     *
     * @return bool Returns true if removed correctly, or false if error.
     */
    static function remove_labels($id, $label_array) {
        return UBCollection::remove_items($id, $label_array, static::REL_RESOURCE_LABEL);
    }

    /**
     * Get Label Ids from a Resource.
     *
     * @param int $id Id of the Resource to get Labels from.
     *
     * @return array|bool Returns array of Label Ids, or false if error.
     */
    static function get_labels($id) {
        return UBCollection::get_items($id, static::REL_RESOURCE_LABEL);
    }

    static function add_performance_items($id, $performance_item_array) {
        return UBCollection::add_items($id, $performance_item_array, static::REL_RESOURCE_PERFORMANCE);
    }

    static function set_performance_items($id, $performance_item_array) {
        return UBCollection::set_items($id, $performance_item_array, static::REL_RESOURCE_PERFORMANCE);
    }

    static function remove_performance_items($id, $performance_item_array) {
        return UBCollection::remove_items($id, $performance_item_array, static::REL_RESOURCE_PERFORMANCE);
    }

    static function get_performance_items($id) {
        return UBCollection::get_items($id, static::REL_RESOURCE_PERFORMANCE);
    }
}