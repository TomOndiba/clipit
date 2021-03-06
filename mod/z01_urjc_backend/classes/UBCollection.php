<?php
/**
 * Clipit eLearning Platform
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, Clipit Team
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 * @subpackage      urjc_backend
 */

/**
 * <Class Description>
 */
abstract class UBCollection {
    /**
     * Adds Items to a Collection.
     *
     * @param int $id Id from Collection to add Items to.
     * @param array $item_array Array of Item Ids to add.
     * @param string $rel_name Name of the relationship to use.
     * @param bool $exclusive Whether the added items have an exclusive relationship with the collection.
     *
     * @return bool Returns true if success, false if error
     */
    static function add_items($id, $item_array, $rel_name, $exclusive = false) {
        if(empty($id)){
            return false;
        }
        foreach ($item_array as $item_id) {
            if(empty($item_id)){
                continue;
            }
            if ($exclusive) {
                $rel_array = get_entity_relationships($item_id, true);
                foreach ($rel_array as $rel) {
                    if ($rel->relationship == $rel_name) {
                        delete_relationship($rel->id);
                    }
                }
            }
            add_entity_relationship($id, $rel_name, $item_id);
        }
        return true;
    }

    /**
     * Sets Items to a Collection.
     *
     * @param int $id Id from Collection to add Items to.
     * @param array $item_array Array of Item Ids to set.
     * @param string $rel_name Name of the relationship to use.
     * @param bool $exclusive Whether the added items have an exclusive relationship with the collection.
     *
     * @return bool Returns true if success, false if error
     */
    static function set_items($id, $item_array, $rel_name, $exclusive = false) {
        $to_be_removed = static::get_items($id, $rel_name);
        $to_be_added = array();
        foreach($item_array as $item_id){
            $pos = array_search($item_id, $to_be_removed);
            // if new item does not currently exist, add to to_be_added array
            if($pos === false){
                $to_be_added[] = $item_id;
            }
            // else (if exists) remove from to_be_removed array
            else{
                unset($to_be_removed[$pos]);
            }
        }
        static::add_items($id, $to_be_added, $rel_name, $exclusive);
        static::remove_items($id, $to_be_removed, $rel_name);
        return true;
    }

    /**
     * Get Items from a Collection.
     *
     * @param int $id Id from Collection to get Items from.
     * @param string $rel_name Name of the relationship linking the items.
     * @param bool $inverse Whether the Id specified is in the first (false) or second (true) term of the relationship.
     *
     * @return int[]|bool Returns an array of Item IDs, or false if error.
     */
    static function get_items($id, $rel_name, $inverse = false) {
        $rel_array = get_entity_relationships($id, $inverse);
        $item_array = array();
        foreach ($rel_array as $rel) {
            if ($rel->relationship == $rel_name) {
                if ($inverse) {
                    $item_array[$rel->id] = (int)$rel->guid_one;
                } else {
                    $item_array[$rel->id] = (int)$rel->guid_two;
                }
            }
        }
        // IDs are created sequentially, so inverse ordering == reverse chrono-order
        uasort($item_array, 'UBItem::sort_numbers_inv');
        return $item_array;
    }

    /**
     * Count the number of related items.
     *
     * @param int $id Item to count related items with.
     * @param string $rel_name Name of the relationship
     * @param bool $inverse position of the Item in the relationship (first = false, seccond = true)
     * @param bool $recursive Whether to count recursively or not
     *
     * @return int Number of items related with the one specified.
     */
    static function count_items($id, $rel_name, $inverse = false, $recursive = false) {
        $rel_array = get_entity_relationships($id, $inverse);
        $count = 0;
        foreach ($rel_array as $rel) {
            if ($rel->relationship === $rel_name) {
                $count++;
                if ($recursive) {
                    if ($inverse) {
                        $count += static::count_items($rel->guid_one, $rel_name, $inverse, $recursive);
                    } else {
                        $count += static::count_items($rel->guid_two, $rel_name, $inverse, $recursive);
                    }
                }
            }
        }
        return $count;
    }

    /**
     * Remove Items from a Collection.
     *
     * @param int $id Id from Collection to remove Items from.
     * @param array $item_array Array of Item Ids to remove.
     * @param string $rel_name Name of the relationship to use.
     *
     * @return bool Returns true if success, false if error.
     */
    static function remove_items($id, $item_array, $rel_name) {
        foreach ($item_array as $item_id) {
            remove_entity_relationship($id, $rel_name, $item_id);
        }
        return true;
    }

    /**
     * Remove all items from a collection.
     *
     * @param int $id ID of the Collection to remove items from.
     * @param string $rel_name Name of the relationship to use.
     *
     * @return bool Returns true if success, false if error.
     */
    static function remove_all_items($id, $rel_name) {
        return remove_entity_relationships($id, $rel_name);
    }

    static function set_timestamp($id1, $id2, $rel_name, $timestamp){
        $rel_array = get_entity_relationships($id1);
        if(empty($rel_array)){
            return null;
        }
        foreach($rel_array as $rel){
            if($rel->relationship == $rel_name && (int)$rel->guid_two == (int)$id2){
                $rel->time_created = $timestamp;
                return $rel->save();
            }
        }
        return null;
    }

    static function get_timestamp($id1, $id2, $rel_name) {
        $rel_array = get_entity_relationships($id1);
        if (empty($rel_array)) {
            return null;
        }
        foreach ($rel_array as $rel) {
            if ($rel->relationship == $rel_name && (int)$rel->guid_two == (int)$id2) {
                return $rel->getTimeCreated();
            }
        }
        return null;
    }
}