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
class UBSite {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "UBSite";
    public $id = 0;
    public $name = "";
    public $description = "";
    public $url = "";
    public $owner_id = 0;
    public $time_created = 0;

    /**
     * Constructor
     * @throws APIException
     */
    function __construct() {
        $elgg_entity = elgg_get_site_entity();
        $this->copy_from_elgg($elgg_entity);
    }

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        $this->id = (int)$elgg_entity->get("guid");
        $this->name = (string)$elgg_entity->get("name");
        $this->description = (string)$elgg_entity->get("description");
        $this->url = (string)$elgg_entity->get("url");
        $this->owner_id = (int)$elgg_entity->getOwnerGUID();
        $this->time_created = (int)$elgg_entity->getTimeCreated();
    }

    /**
     * Saves the Site to the system.
     * @param  bool $double_save if $double_save is true, this object is saved twice to ensure that all properties are updated properly. E.g. the time created property can only beset on ElggObjects during an update. Defaults to false!
     * @return bool|int Returns the Id of the saved instance, or false if error
     */
    protected function save($double_save = false) {
        $elgg_entity = elgg_get_site_entity();
        $this->copy_to_elgg($elgg_entity);
        $elgg_entity->save();
        return $this->id = $elgg_entity->get("guid");
    }

    /**
     * @param ElggEntity $elgg_entity
     */
    protected function copy_to_elgg($elgg_entity) {
        $elgg_entity->set("name", (string)$this->name);
        $elgg_entity->set("description", (string)$this->description);
        $elgg_entity->set("url", (string)$this->url);
    }

    static function get_site() {
        return new static();
    }

    static function get_site_id() {
        return (int)datalist_get("default_site");
    }

    /**
     * Dummy method in case someone treats Clipit Site as a typical Clipit Object
     *
     * @param int $limit
     * @param int $offset
     * @param string $order_by
     * @param bool $ascending
     * @param bool $id_only
     * @return array
     */
    static function get_all($limit = 0, $offset = 0, $order_by = "", $ascending = true, $id_only = false) {
        if($id_only) {
            return static::get_site_id();
        } else{
            return array(static::get_site_id() => static::get_site());
        }
    }

        /**
     * Get the REST API method list, including description and required parameters.
     * @return array List of all available REST API Methods.
     */
    static function api_list() {
        return list_all_apis();
    }

    /**
     * Get authentication token, required for all other REST API calls. The token must be set as the value for the
     * "auth_token" key in each REST API call.
     *
     * @param string $login User login
     * @param string $password User password
     * @param int $timeout Session timeout in minutes
     *
     * @return string Authentication Token.
     * @throws SecurityException
     */
    static function get_token($login, $password = "", $timeout = 60) {
        global $CONFIG;
        if (!elgg_get_logged_in_user_guid()) {
            if (elgg_authenticate($login, $password) !== true) {
                throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
            }
        }
        $user = get_user_by_username($login);
        $query = "select * from {$CONFIG->dbprefix}users_apisessions where user_guid = {$user->guid};";
        $row = get_data_row($query);
        if (isset($row->token)) {
            $timeout = $timeout * 60 * 1000; // convert mins to ms
            // if the token expires in less than $timeout, we extend the timeout
            if (((int)$row->expires - time()) < $timeout) {
                $new_expires = time() + $timeout;
                $update_timeout_query = "update {$CONFIG->dbprefix}users_apisessions set expires = {$new_expires} where user_guid = {$user->guid};";
                update_data($update_timeout_query);
            }
            return $row->token;
        } else {
            return create_user_token($login, $timeout);
        }
    }

    static function remove_token($token) {
        return remove_user_token($token, null);
    }

    static function lookup($id) {
        if(empty($id)){
            return null;
        }
        try {
            $elgg_object = new ElggObject((int)$id);
            $object['type'] = (string)$elgg_object->type;
            $object['subtype'] = (string)get_subtype_from_id($elgg_object->subtype);
            $object['name'] = (string)$elgg_object->get("name");
            $object['description'] = (string)$elgg_object->description;
            //$object['class'] = get_class_from_subtype($object['subtype']);
            return $object;
        } catch (Exception $e) {
            try {
                $elgg_user = new ElggUser((int)$id);
                $object['type'] = (string)$elgg_user->type;
                $object['subtype'] = (string)get_subtype_from_id($elgg_user->subtype);
                return $object;
            } catch (Exception $e) {
                return null;
            }
        }
    }

    static function get_domain() {
        $site = elgg_get_site_entity();
        $urlData = parse_url($site->url);
        $hostData = explode('.', $urlData['host']);
        $hostData = array_reverse($hostData);
        return $hostData[1] . '.' . $hostData[0];
    }


    static function normalize_xml_key($key) {
        return str_replace(array('!', '"', '#', '$', '%', '&', '(', ')', '*', '+', ',', '/', ';', '<', '=', '>', '?', '@', '\\', '[', ']', '^', '`', '{', '}', '|', '~'),
            '_',
            $key);
    }

    /* Static Functions */
    /**
     * Lists the properties contained in this object
     * @return array Array of properties with type and default value
     */
    static function list_properties() {
        return get_class_vars(get_called_class());
    }

}