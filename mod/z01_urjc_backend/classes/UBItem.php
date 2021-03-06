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
class UBItem {
    /**
     * @const string Elgg entity TYPE for this class
     */
    const TYPE = "object";
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "UBItem";
    /**
     * @const string Clone-Parent relationship name
     */
    const REL_PARENT_CLONE = "parent-clone";
    /**
     * @var int Unique Id of this instance
     */
    public $id = 0;
    /**
     * @var string Name of this instance
     */
    public $name = "";
    /**
     * @var string Description of this instance
     */
    public $description = "";
    /**
     * @var string URL of the instance
     */
    public $url = "";
    /**
     * @var int Unique Id of the owner/creator of this instance
     */
    public $owner_id = 0;
    /**
     * @var int Timestamp when this Item was created
     */
    public $time_created = 0;
    /**
     * @var int Origin object id, in case this object was cloned. If not = 0.
     */
    public $cloned_from = 0;
    /**
     * @var array Object clone ids.
     */
    public $clone_array = array();

    /**
     * Constructor
     *
     * @param int                   $id          If !null, load instance.
     * @param ElggObject|ElggEntity $elgg_object Object to load instance from (optional)
     *
     * @throws APIException
     */
    function __construct($id = null, $elgg_object = null) {
        if(!empty($id)) {
            if(empty($elgg_object)){
                if(!$elgg_object = new ElggObject($id)) {
                    throw new APIException("ERROR: Failed to load " . get_called_class() ." object with ID '" . $id . "'.");
                }
            }
            $elgg_type = $elgg_object->type;
            $elgg_subtype = $elgg_object->getSubtype();
            if(($elgg_type != static::TYPE) || ($elgg_subtype != static::SUBTYPE)) {
                throw new APIException(
                    "ERROR: ID '" . $id . "' does not correspond to a " . get_called_class() . " object."
                );
            }
            $this->copy_from_elgg($elgg_object);
        }
    }

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity)
    {
        $this->id = (int)$elgg_entity->get("guid");
        $this->name = (string)$elgg_entity->get("name");
        $this->description = (string)$elgg_entity->get("description");
        $this->url = (string)$elgg_entity->get("url");
        $this->owner_id = (int)$elgg_entity->getOwnerGUID();
        $this->time_created = (int)$elgg_entity->getTimeCreated();
        $this->cloned_from = (int)static::get_cloned_from($this->id);
        $this->clone_array = (array)static::get_clones($this->id);
    }

    /**
     * Get the parent Item ID for an Item.
     *
     * @param int $id Item from which to return parent
     * @param bool $recursive Whether to look for parent recursively
     *
     * @return int Parent ID
     */
    static function get_cloned_from($id, $recursive = false)
    {
        $parent = UBCollection::get_items($id, static::REL_PARENT_CLONE, true);
        if (empty($parent)) {
            return 0;
        }
        $parent = (int)array_pop($parent);
        if ($recursive) {
            $new_parent = static::get_cloned_from($parent, true);
            while(!empty($new_parent)){
                $parent = $new_parent;
                $new_parent = static::get_cloned_from($parent, true);
            }
        }
        return $parent;
    }

    /**
     * Get all parent "master" items (which are not cloned)
     *
     * @param string $order_by forwarded to get_all (see get_all)
     * @param bool $ascending forwarded to get_all (see get_all)
     * @param bool|false $id_only Whether to return only IDs
     * @return static[]|int[] Array of parent objects from class
     */
    static function get_all_parents($order_by = "", $ascending = true, $id_only = false){
        $all_items = static::get_all(0, 0, $order_by, $ascending, $id_only);
        $parent_array = array();
        foreach($all_items as $item){
            $cloned_from = static::get_cloned_from($id_only ? $item : $item->id);
            if(empty($cloned_from)){
                $parent_array[] = $item;
            }
        }
        return $parent_array;
    }

    /**
     * Get an ID array of all cloned Items from a specified one.
     *
     * @param int $id Item from which to return clones
     * @param bool $recursive Whether to look for clones recursively
     *
     * @return int[] Array of Item IDs
     */
    static function get_clones($id, $recursive = false)
    {
        $item_clones = array_reverse(UBCollection::get_items($id, static::REL_PARENT_CLONE));
        if ($recursive) {
            $clone_array = array();
            foreach ($item_clones as $clone) {
                $clone_array[] = $clone;
                $clone_children = static::get_clones($clone, true);
                if(!empty($clone_children)){
                    $clone_array[] = $clone_children;
                }
            }
            return $clone_array;
        }
        return $item_clones;
    }

    /* Static Functions */

    /**
     * Create a new instance of this class, and assign values to its properties.
     *
     * @param array $prop_value_array Array of [property]=>value pairs to set into the new instance
     *
     * @return int|bool Returns instance Id if correct, or false if error
     */
    static function create($prop_value_array)
    {
        return static::set_properties(null, $prop_value_array);
    }

    /**
     * Sets values to specified properties of an Item
     *
     * @param int $id Id of Item to set property values
     * @param array $prop_value_array Array of property=>value pairs to set into the Item
     *
     * @return int|bool Returns Id of Item if correct, or false if error
     * @throws InvalidParameterException
     */
    static function set_properties($id, $prop_value_array)
    {
        if(!$item = new static($id)) {
            return false;
        }
        $class_properties = (array)static::list_properties();
        foreach ($prop_value_array as $prop => $value) {
            if (!array_key_exists($prop, $class_properties)) {
                throw new InvalidParameterException("ERROR: One or more property names do not exist.");
            }
            if ($prop == "id") {
                continue; // cannot set an item's ID manually.
            }
            $item->$prop = $value;
        }
        if (array_key_exists("time_created", $prop_value_array)) {
            return $item->save(true);
        } else {
            return $item->save(false);
        }
    }

    /**
     * Lists the properties contained in this object
     * @return array Array of properties with type and default value
     */
    static function list_properties()
    {
        return get_class_vars(get_called_class());
    }

    /**
     * Saves this instance to the system.
     * @param bool $double_save if double_save is true, this object is saved twice to ensure that all properties are
     * updated properly. E.g. the time_created property can only beset on ElggObjects during an update. Defaults to false!
     * @return bool|int Returns id of saved instance, or false if error.
     */
    protected function save($double_save = false)
    {
        if (!empty($this->id)) {
            if (!$elgg_object = new ElggObject($this->id)) {
                return false;
            }
        } else {
            $elgg_object = new ElggObject();
            $elgg_object->type = static::TYPE;
            $elgg_object->subtype = static::SUBTYPE;
        }
        $this->copy_to_elgg($elgg_object);
        $elgg_object->save();
        if ($double_save) {
            // Only updates are saving time_created, thus first save for creation, second save for updating to
            // proper creation time if given
            $elgg_object->save();
        }
        return $this->id = $elgg_object->get("guid");
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity)
    {
        $elgg_entity->set("name", (string)$this->name);
        $elgg_entity->set("description", (string)$this->description);
        $elgg_entity->set("url", (string)$this->url);
        if (!empty($this->owner_id)) {
            $elgg_entity->set("owner_guid", (int)$this->owner_id);
        }
        $elgg_entity->set("time_created", (int)$this->time_created);
        $elgg_entity->set("access_id", ACCESS_PUBLIC);
    }

    /**
     * Clone the specified Item, including all of its properties.
     *
     * @param int $id Item id from which to create a clone.
     * @param bool $linked Selects whether the clone will be linked to the parent object.
     * @param bool $keep_owner Selects whether the clone will keep the parent item's owner (default: no)
     *
     * @return bool|int Id of the new clone Item, false in case of error.
     */
    static function create_clone($id, $linked = true, $keep_owner = false) {
        $prop_value_array = static::get_properties($id);
        if($keep_owner === false){
            $prop_value_array["owner_id"] = elgg_get_logged_in_user_guid();
        }
        $clone_id = static::set_properties(null, $prop_value_array);
        if($linked){
            static::link_parent_clone($id, $clone_id);
        }
        return $clone_id;
    }

    /**
     * Get specified property values for an Item
     *
     * @param int $id Id of instance to get properties from
     * @param array $prop_array Array of property names to get values from
     *
     * @return array|bool Returns an array of property=>value pairs, or false if error
     * @throws InvalidParameterException
     */
    static function get_properties($id, $prop_array = null)
    {
        if (!$item = new static($id)) {
            return null;
        }
        $prop_value_array = array();
        if (!empty($prop_array)) {
            foreach ($prop_array as $prop) {
                if (array_key_exists($prop, static::list_properties())) {
                    $prop_value_array[$prop] = $item->$prop;
                } else {
                    throw new InvalidParameterException("ERROR: One or more property names do not exist.");
                }
            }
        } else {
            $prop_array = static::list_properties();
            do {
                $prop = key($prop_array);
                $prop_value_array[$prop] = $item->$prop;
                next($prop_array);
            } while (key($prop_array) !== null);
        }
        return $prop_value_array;
    }

    /**
     * Links two entities as parent and clone
     *
     * @param int $id_parent ID of parent entity
     * @param int $id_clone IF of clone entity
     * @return bool true if OK
     */
    static function link_parent_clone($id_parent, $id_clone)
    {
        return UBCollection::add_items($id_parent, array($id_clone), static::REL_PARENT_CLONE, true);
    }

    /**
     * Unlinks an entity from its parent
     *
     * @param int $id ID of entity
     * @return bool returns true if OK
     */
    static function unlink_from_parent($id)
    {
        $parent = static::get_cloned_from($id);
        return UBCollection::remove_items($parent, array($id), static::REL_PARENT_CLONE);
    }

    /**
     * Unlinks an entity from its clones
     *
     * @param int $id ID of entity
     * @return bool returns true if OK]
     */
    static function unlink_from_clones($id)
    {
        $clones = static::get_clones($id);
        return UBCollection::remove_items($id, $clones, static::REL_PARENT_CLONE);
    }

    /**
     * Get an array with the full clone list of the clone tree an item belongs to
     * @param int|null $id ID of Item (if non set, return all trees)
     *
     * @return int[] Array of item IDs
     * @throws InvalidParameterException
     */
    static function get_clone_tree($id = null){
        if(empty($id)){
            $all_items = array_reverse(static::get_all(0, 0, "", true, false));
            $clone_tree = array();
            foreach($all_items as $item_id){
                if(!in_array($item_id, array_flatten($clone_tree))){
                    $clone_tree[] = static::get_clone_tree($item_id);
                }
            }
        } else {
            // Find top parent in clone tree
            $top_parent =  static::get_cloned_from($id, true);
            if(empty($top_parent)){
                $top_parent = $id;
            }
            $clone_tree = array();
            $clone_tree[] = $top_parent;
            $clones = static::get_clones($top_parent, true);
            if(!empty($clones)){
                $clone_tree[] = $clones;
            }
        }
        return $clone_tree;
    }

    /**
     * Delete All Items for this class.
     * @return bool Returns true if correct, or false if error
     */
    static function delete_all()
    {
        $items = static::get_all(0, 0, "", true, true);
        if (!empty($items)) {
            static::delete_by_id($items);
        }
        return true;
    }

    static function count_all(){
        return elgg_get_entities(array("limit" => 0, "count" => true));
    }

    /**
     * Get all Object instances of this TYPE and SUBTYPE from the system, optionally only a specified property.
     *
     * @param int $limit Number of results to show, default= 0 [no limit] (optional)
     * @param int $offset Offset from where to show results, default=0 [from the begining] (optional)
     * @param string $order_by Default = "" == time_created desc (newest first)
     * @param bool $ascending Default = true (ascending order)
     * @param bool $id_only Whether to only return IDs, or return whole objects (default: false)
     * will be done if it is set to true.
     *
     * @return static[]|int[] Returns an array of Objects, or Object IDs if id_only = true
     */
    static function get_all($limit = 0, $offset = 0, $order_by = "", $ascending = true, $id_only = false)
    {
        $return_array = array();
        if(!empty($order_by)){
            $options = array(
                'type' => static::TYPE,
                'subtype' => static::SUBTYPE,
                'limit' => $limit,
                'offset' => $offset,
                'order_by_metadata' =>
                    array("name" => $order_by ,
                        "direction" => ($ascending ? "ASC":"DESC"))
            );
        }else{
            $options = array(
                'type' => static::TYPE,
                'subtype' => static::SUBTYPE,
                'limit' => $limit,
                'offset' => $offset,
                'sort_by' => "e.time_created desc"
            );
        }
        $elgg_entity_array = elgg_get_entities_from_metadata($options);
        if (!$elgg_entity_array) {
            return $return_array;
        }
        if ($id_only) {
            foreach ($elgg_entity_array as $elgg_entity) {
                $return_array[] = (int)$elgg_entity->guid;
            }
            return $return_array;
        }
        foreach ($elgg_entity_array as $elgg_entity) {
            $return_array[(int)$elgg_entity->guid] = new static((int)$elgg_entity->guid, $elgg_entity);
        }
        return $return_array;
    }

    /**
     * Delete Items given their Id.
     *
     * @param array $id_array List of Item Ids to delete
     *
     * @return bool Returns true if correct, or false if error
     */
    static function delete_by_id($id_array)
    {
        if (empty($id_array)) {
            return true;
        }
        foreach ($id_array as $id) {
            // Check if ID is of same subtype as class
            $lookup_array = ClipitSite::lookup($id);
            if($lookup_array["subtype"] != static::SUBTYPE){
                continue;
            }
            // Don't allow to delete the Site ID
            $site = elgg_get_site_entity();
            if ($id == $site->guid) {
                continue;
            }
            if (delete_entity((int)$id) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get Objects with id contained in a given list.
     *
     * @param array $id_array Array of item IDs to get
     * @param int $limit (optional) limit of items to get
     * @param int $offset (optional) offset of items to get
     * @param string $order_by (optional) order by a certain property
     * @param bool $ascending (optional) order by ascending (default) or descending
     * @return static[] array of UBItems filtered by the parameters given
     */
    static function get_by_id($id_array, $limit = 0, $offset = 0, $order_by = "", $ascending = true) {
        $item_array = array();
        if(empty($id_array)){
            return $item_array;
        }
        foreach($id_array as $id) {
            if(empty($id)){
                continue;
            }
            $item_array[(int)$id] = new static((int)$id);
        }
        if(!empty($item_array) && !empty($order_by)){
            $args = array("order_by" => $order_by, "ascending" => $ascending);
            uasort($item_array, function($i1, $i2) use($args){
                if (!$i1 && !$i2) {
                    return 0;
                }
                if($i1->$args["order_by"] == $i2->$args["order_by"]){
                    return 0;
                }
                if((bool)$args["ascending"]) {
                    if (!$i1) {
                        return 1;
                    }
                    if (!$i2) {
                        return -1;
                    }
                    return (strtolower($i1->$args["order_by"]) < strtolower($i2->$args["order_by"]) ? -1 : 1);
                } else {
                    if (!$i1) {
                        return -1;
                    }
                    if (!$i2) {
                        return 1;
                    }
                    return (strtolower($i2->$args["order_by"]) < strtolower($i1->$args["order_by"]) ? -1 : 1);
                }
            });
        }
        if(empty($limit)){
            $limit = null;
        }
        return array_slice($item_array, (int)$offset, $limit, true);
    }

    /**
     * Get Items with Owner Id contained in a given list.
     *
     * @param array $owner_array Array of Owner Ids
     * @param int $limit (optional)      Number of Items to return, default 0 = all
     * @param int $offset (optional) offset of items to get
     * @param string $order_by (optional) order by a certain property
     * @param bool $ascending (optional) order by ascending (default) or descending
     *
     * @return static[] Returns an array of Items
     */
    static function get_by_owner($owner_array = null, $limit = 0, $offset = 0, $order_by = "", $ascending = true)
    {
        $object_array = array();
        if (empty($owner_array)) {
            $item_array = static::get_all();
            $return_array = array();
            foreach ($item_array as $item) {
                if (!isset($return_array[$item->owner_id])) {
                    $return_array[$item->owner_id] = array();
                }
                $return_array[$item->owner_id][] = $item;
            }
            if (!empty($order_by)) {
                foreach ($return_array as $owner_items) {
                    $args = array("order_by" => $order_by, "ascending" => $ascending);
                    uasort($owner_items,
                        function ($i1, $i2) use ($args) {
                            if (!$i1 && !$i2) {
                                return 0;
                            }
                            if ($i1->$args["order_by"] == $i2->$args["order_by"]) {
                                return 0;
                            }
                            if ((bool)$args["ascending"]) {
                                if (!$i1) {
                                    return 1;
                                }
                                if (!$i2) {
                                    return -1;
                                }
                                return (strtolower($i1->$args["order_by"]) < strtolower($i2->$args["order_by"]) ? -1 : 1);
                                //return strcmp($i1->$args["order_by"], $i2->$args["order_by"]);
                            } else {
                                if (!$i1) {
                                    return -1;
                                }
                                if (!$i2) {
                                    return 1;
                                }
                                return (strtolower($i2->$args["order_by"]) < strtolower($i1->$args["order_by"]) ? -1 : 1);
                                //return strcmp($i2->$args["order_by"], $i1->$args["order_by"]);
                            }
                        });
                }
            }
            return $return_array;
        }
        // else if !empty($owner_array)
        foreach($owner_array as $owner_id) {
            $elgg_object_array = elgg_get_entities(
                array(
                    "type" => static::TYPE,
                    "subtype" => static::SUBTYPE,
                    "owner_guid" => (int)$owner_id,
                    "limit" => $limit,
                    "offset" => $offset,
                    "sort_by" => "e.time_created"
                )
            );
            if(!empty($elgg_object_array)) {
                $temp_array = array();
                foreach($elgg_object_array as $elgg_object) {
                    $temp_array[(int)$elgg_object->guid] = new static((int)$elgg_object->guid, $elgg_object);
                }
                if(!empty($temp_array)) {
                    if (!empty($order_by)) {
                        $args = array("order_by" => $order_by, "ascending" => $ascending);
                        uasort($temp_array,
                            function ($i1, $i2) use ($args) {
                                if (!$i1 && !$i2) {
                                    return 0;
                                }
                                if ($i1->$args["order_by"] == $i2->$args["order_by"]) {
                                    return 0;
                                }
                                if ((bool)$args["ascending"]) {
                                    if (!$i1) {
                                        return 1;
                                    }
                                    if (!$i2) {
                                        return -1;
                                    }
                                    return (strtolower($i1->$args["order_by"]) < strtolower($i2->$args["order_by"]) ? -1 : 1);
                                    //return strcmp($i1->$args["order_by"], $i2->$args["order_by"]);
                                } else {
                                    if (!$i1) {
                                        return -1;
                                    }
                                    if (!$i2) {
                                        return 1;
                                    }
                                    return (strtolower($i2->$args["order_by"]) < strtolower($i1->$args["order_by"]) ? -1 : 1);
                                    //return strcmp($i2->$args["order_by"], $i1->$args["order_by"]);
                                }
                            });
                    }
                    $object_array[(int)$owner_id] = $temp_array;
                } else {
                    $object_array[(int)$owner_id] = null;
                }
            } else{
                $object_array[(int)$owner_id] = null;
            }
        }
        return $object_array;
    }

    /**
     * Get all system events filtered by the class TYPE and SUBTYPE.
     *
     * @param int $offset Skip the first $offset events
     * @param int $limit  Return at most $limit events
     *
     * @return array Array of system events
     */
    static function get_events($offset = 0, $limit = 10) {
        return get_system_log(
            null, // $by_user = ""
            null, // $event = ""
            null, // $class = ""
            static::TYPE, // $type = ""
            static::SUBTYPE, // $subtype = ""
            $limit, // $limit = 10
            $offset, // $offset = 0
            null, // $count = false
            null, // $timebefore = 0
            null, // $timeafter = 0
            null, // $object_id = 0
            null
        ); // $ip_address = ""
    }

    /**
     * Get all objects which match a $search_string
     *
     * @param string $search_string String for searching matching objects
     * @param bool   $name_only     Whether to look only in the name property, default false.
     * @param bool   $strict        Whether to match the $search_string exactly, including case, or only partially.
     * @param int    $offset        The offset of the returned array
     * @param int    $limit         The limit of the returned array
     *
     * @return static[] Returns an array of matched objects
     */
    static function get_from_search($search_string, $name_only = false, $strict = false, $limit = 0, $offset = 0) {
        $search_result = array();
        if(!$strict) {
            $search_string = strtolower($search_string);
            // get the full array of entities
            $elgg_object_array = elgg_get_entities(
                array('type' => static::TYPE, 'subtype' => static::SUBTYPE, 'limit' => 0)
            );
            $search_result = array();
            foreach($elgg_object_array as $elgg_object) {
                // search for string in name
                if(strpos(strtolower($elgg_object->name), $search_string) !== false) {
                    $search_result[(int)$elgg_object->guid] = new static((int)$elgg_object->guid, $elgg_object);
                    continue;
                }
                // if not in name, search in description
                if($name_only === false) {
                    if(strpos(strtolower($elgg_object->description), $search_string) !== false) {
                        $search_result[(int)$elgg_object->guid] = new static((int)$elgg_object->guid, $elgg_object);
                    }
                }
            }
        } else { // $strict == true
            // directly retrieve entities with name = $search_string
            $elgg_object_array = elgg_get_entities_from_metadata(
                array(
                    'type' => static::TYPE,
                    'subtype' => static::SUBTYPE,
                    'metadata_names' => array("name"),
                    'metadata_values' => array($search_string),
                    'limit' => 0
                )
            );
            if(!empty($elgg_object_array)) {
                foreach($elgg_object_array as $elgg_object) {
                    $search_result[(int)$elgg_object->guid] = new static((int)$elgg_object->guid, $elgg_object);
                }
            }
        }
        if(empty($limit)){
            return array_slice($search_result, (int)$offset, count($search_result), true);
        } else {
            return array_slice($search_result, (int)$offset, (int)$limit, true);
        }
        //return $search_result;
    }

    /**
     * Sort by Date, oldest to newest.
     *
     * @param static $i1
     * @param static $i2
     *
     * @return int Returns 0 if equal, -1 if i1 before i2, 1 if i1 after i2.
     */
    static function sort_by_date($i1, $i2) {
        if(!$i1 && !$i2){
            return 0;
        }elseif(!$i1){
            return 1;
        }elseif(!$i2){
            return -1;
        }
        if((int)$i1->time_created == (int)$i2->time_created) {
            return 0;
        }
        return ((int)$i1->time_created < (int)$i2->time_created) ? - 1 : 1;
    }

    /**
     * Sort by Date Inverse order, newest to oldest.
     *
     * @param static $i1
     * @param static $i2
     *
     * @return int Returns 0 if equal, -1 if i1 before i2, 1 if i1 after i2.
     */
    static function sort_by_date_inv($i1, $i2) {
        if(!$i1 && !$i2){
            return 0;
        }elseif(!$i1){
            return 1;
        }elseif(!$i2){
            return -1;
        }
        if((int)$i1->time_created == (int)$i2->time_created) {
            return 0;
        }
        return ((int)$i1->time_created > (int)$i2->time_created) ? - 1 : 1;
    }

    /**
     * Sort by Name, in alphabetical order.
     *
     * @param static $i1
     * @param static $i2
     *
     * @return int Returns 0 if equal, -1 if i1 before i2, 1 if i1 after i2.
     */
    static function sort_by_name($i1, $i2) {
        if(!$i1 && !$i2){
            return 0;
        }elseif(!$i1){
            return 1;
        }elseif(!$i2){
            return -1;
        }
        return strcmp($i1->name, $i2->name);
    }

    /**
     * Sort by Name, in inverse alphabetical order.
     *
     * @param static $i1
     * @param static $i2
     *
     * @return int Returns 0 if equal, -1 if i1 before i2, 1 if i1 after i2.
     */
    static function sort_by_name_inv($i1, $i2) {
        if(!$i1 && !$i2){
            return 0;
        }elseif(!$i1){
            return 1;
        }elseif(!$i2){
            return -1;
        }
        return strcmp($i2->name, $i1->name);
    }

    /**
     * Sort numbers, in increasing order.
     *
     * @param float $i1
     * @param float $i2
     *
     * @return int Returns 0 if equal, -1 if i1 before i2, 1 if i1 after i2.
     */
    static function sort_numbers($i1, $i2) {
        if(!$i1 && !$i2){
            return 0;
        }elseif(!$i1){
            return 1;
        }elseif(!$i2){
            return -1;
        }
        if((int)$i1 == (int)$i2) {
            return 0;
        }
        return ((int)$i1 < (int)$i2) ? - 1 : 1;
    }

    /**
     * Sort numbers, in decreasing order.
     *
     * @param float $i1
     * @param float $i2
     *
     * @return int Returns 0 if equal, -1 if i1 before i2, 1 if i1 after i2.
     */
    static function sort_numbers_inv($i1, $i2) {
        if(!$i1 && !$i2){
            return 0;
        }elseif(!$i1){
            return 1;
        }elseif(!$i2){
            return -1;
        }
        if((int)$i1 == (int)$i2) {
            return 0;
        }
        return ((int)$i1 > (int)$i2) ? - 1 : 1;
    }
}