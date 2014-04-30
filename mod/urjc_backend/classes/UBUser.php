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
 * @subpackage      urjc_backend
 */

/**
 * Class UBUser
 *
 */
class UBUser extends UBItem{
    /**
     * @const string Elgg entity type for this class
     * @const string Elgg entity subtype for this class
     * @const string Default User Role if not specified
     */
    const TYPE = "user";
    const SUBTYPE = "";

    /**
     * @var string Login name used to authenticate
     * @var string Login password (md5 of password + password_hash)
     * @var string Random string to encode password
     * @var string User email
     * @var string User role (default: "user")
     */
    public $login = "";
    public $password = "";
    public $email = "";
    public $role = "user";
    public $language = "";
    public $last_login = -1;
    private $password_hash = "";

    function __construct($id = -1){
        if($id != -1){
            if(!($elgg_user = new ElggUser($id))){
                throw new APIException("ERROR: Id '" . $id . "' does not correspond to a " . get_called_class() . " object.");
            }
        } else{
            $elgg_user = new ElggUser();
            $elgg_user->save();
        }
        $this->_load($elgg_user);
    }

    /**
     * Loads a User instance from the system.
     *
     * @param ElggUser $elgg_user User to load from the system.
     *
     * @return UBUser|bool Returns User instance, or false if error.
     */
    protected function _load($elgg_user){
        parent::_load($elgg_user);
        $this->email = (string)$elgg_user->email;
        $this->login = (string)$elgg_user->username;
        $this->password = (string)$elgg_user->password;
        $this->password_hash = (string)$elgg_user->salt;
        $this->role = (string)$elgg_user->role;
        $this->language = (string)$elgg_user->language;
        $this->last_login = (int)$elgg_user->last_login;
    }

    /**
     * Saves this instance to the system.
     *
     * @return bool|int Returns id of saved instance, or false if error.
     */
    function save(){
        if($this->id == -1){
            $elgg_user = new ElggUser();
            $elgg_user->subtype = (string)static::SUBTYPE;
        } elseif(!$elgg_user = new ElggUser($this->id)){
            return false;
        }
        $elgg_user->name = $this->name;
        $elgg_user->description = $this->description;
        $elgg_user->email = $this->email;
        $elgg_user->username = $this->login;
        $elgg_user->password = $this->password;
        $elgg_user->salt = $this->password_hash;
        $elgg_user->role = $this->role;
        if($this->language == ""){
            $elgg_user->language = get_language();
        } else{
            $elgg_user->language = $this->language;
        }
        $elgg_user->owner_guid = 0;
        $elgg_user->container_guid = 0;
        $elgg_user->access_id = ACCESS_PUBLIC;
        $elgg_user->save();
        $this->time_created = $elgg_user->time_created;
        return $this->id = $elgg_user->guid;
    }

    /**
     * Creates an encoded user password using a random hash for encoding.
     *
     * @param string $password The new user password in clear text.
     *
     * @return bool 'true' if success, 'false' if error.
     */
    private function setPassword($password){
        if(!$password){
            return false;
        }
        $this->password_hash = generate_random_cleartext_password();
        $this->password = md5($password . $this->password_hash);
        return true;
    }

    /**
     * Sets values to specified properties of an Item
     *
     * @param int   $id Id of Item to set property valyes
     * @param array $prop_value_array Array of property=>value pairs to set into the Item
     *
     * @return int|bool Returns Id of Item if correct, or false if error
     * @throws InvalidParameterException
     */
    static function set_properties($id, $prop_value_array){
        if(!$item = new static((int)$id)){
            return false;
        }
        $new_prop_value_array = array();
        foreach($prop_value_array as $prop => $value){
            if($prop == "password"){
                $item->setPassword($value);
            } else{
                $new_prop_value_array[$prop] = $value;
            }
        }
        return parent::set_properties($id, $new_prop_value_array);
    }

    /**
     * Authenticate a user and log him into the system.
     *
     * @param string $login User login
     * @param string $password User password
     * @param bool   $persistent Determines whether to make the session persistent
     *
     * @return bool Returns true if ok, or false if error
     */
    static function login($login, $password, $persistent = false){
        if(!elgg_authenticate($login, $password)){
            return false;
        }
        $elgg_user = get_user_by_username($login);
        $token = UBSite::get_token($login, $password); // default timeout 1h
        $site = elgg_get_site_entity();
        setcookie("clipit_token", $token, 0, "/", '.'.get_site_domain($site->guid));
        return login($elgg_user, $persistent);
    }

    /**
     * Logs out a user from the system.
     *
     * @return bool Returns true if ok, or false if error.
     */
    static function logout(){
        UBSite::remove_token($_COOKIE["clipit_token"]);
        setcookie("clipit_token", "", time() - (86400 * 365), "/");
        return logout();
    }

    /**
     * Get users with login contained in a given list of logins.
     *
     * @param array $login_array Array of user logins
     *
     * @return UBUser[] Returns an array of User objects
     */
    static function get_by_login($login_array){
        $user_array = array();
        foreach($login_array as $login){
            $elgg_user = get_user_by_username($login);
            if(!$elgg_user){
                $user_array[$login] = null;
            } else{
                $user_array[$login] = new static((int)$elgg_user->guid);
            }
        }
        return $user_array;
    }

    /**
     * Get users with email contained in a given list of emails. Each email can be associated
     * with multiple users. The output will be an array of Users per login, nested inside a main
     * array.
     *
     * @param array $email_array Array of user emails
     *
     * @return UBUser[] Returns an array of arrays of User objects
     */
    static function get_by_email($email_array){
        $user_array = array();
        foreach($email_array as $email){
            $elgg_user_array = get_user_by_email($email);
            if(!$elgg_user_array){
                $user_array[$email] = null;
            } else{
                $temp_array = array();
                foreach($elgg_user_array as $elgg_user){
                    $temp_array[] = new static((int)$elgg_user->guid);
                }
                $user_array[$email] = $temp_array;
            }
        }
        return $user_array;
    }

    /**
     * Get users with role contained in a given list of roles.
     *
     * @param array $role_array Array of user roles
     *
     * @return UBUser[] Returns an array of arrays of User objects
     */
    static function get_by_role($role_array){
        $user_array = array();
        foreach($role_array as $role){
            $elgg_user_array = elgg_get_entities_from_metadata(
                array(
                    'type' => static::TYPE,
                    'subtype' => static::SUBTYPE,
                    'metadata_names' => array("role"),
                    'metadata_values' => array($role)
                )
            );
            if(!$elgg_user_array){
                $user_array[$role] = null;
            } else{
                $temp_array = array();
                foreach($elgg_user_array as $elgg_user){
                    $temp_array[] = new static($elgg_user->guid);
                }
                $user_array[$role] = $temp_array;
            }
        }
        return $user_array;
    }

    static function get_last_login($id){
        $user = new static($id);
        return $user->last_login;
    }
}