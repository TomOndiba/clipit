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
 * Class ClipitUser
 *
 */
class ClipitUser extends UBUser{
    /**
     * @const Role name for Students
     */
    const ROLE_STUDENT = "student";
    /**
     * @const Role name for Teachers
     */
    const ROLE_TEACHER = "teacher";
    /**
     * @const Role name for Administrators
     */
    const ROLE_ADMIN = "admin";
    /**
     * @const Default cookie token duration in minutes
     */
    const COOKIE_TOKEN_DURATION = 60;

    /**
     * Saves this instance into the system.
     *
     * @return bool|int Returns id of saved instance, or false if error.
     */
    protected function save(){
        $id = parent::save();
        switch(strtolower($this->role)){
            case static::ROLE_STUDENT:
                remove_user_admin($this->id);
                break;
            case static::ROLE_TEACHER:
                make_user_admin($this->id);
                break;
            case static::ROLE_ADMIN:
                make_user_admin($this->id);
                break;
        }
        return $id;
    }

    /**
     * Add Users from an Excel file, and return an array of User Ids from those created or selected from the file.
     *
     * @param string $file_path Local file path
     * @return array|null Array of User IDs, or null if error.
     */
    static function add_from_excel($file_path){
        $xlsx = new SimpleXLSX($file_path);
        $user_array = array();
        foreach($xlsx->rows() as $row){
            if (empty($row[0]) || strtolower($row[0]) == "users" || strtolower($row[0]) == "name") {
                continue;
            } else {
                $row_result = static::process_excel_row($row);
                if (empty($row_result)){
                    return null;
                }
                $user_array[] = (int)$row_result;
            }
        }
        return $user_array;
    }

    /**
     * Process a single role from an Excel file, containing one user
     *
     * @param array $row
     * @return int|false ID of new object created, or false in case of error.
     */
    private function process_excel_row($row){
        $prop_value_array = array();
        // name
        $prop_value_array["name"] = (string) reset($row);
        // login
        $login = (string)next($row);
        $user_array = static::get_by_login(array($login));
        if(!empty($user_array[$login])){ // user already exists, no need to create it
            return (int)$user_array[$login]->id;
        }
        $prop_value_array["login"] = $login;
        // password
        $prop_value_array["password"] = (string) next($row);
        // email
        $prop_value_array["email"] = (string) next($row);
        // role
        $prop_value_array["role"] = (string) next($row);
        return static::create($prop_value_array);
    }

    static function login($login, $password, $persistent = false){
        if(!parent::login($login, $password, $persistent)){
            return false;
        }
        static::create_cookies($login, $password);
        return true;
    }

    static function logout(){
        static::delete_cookies();
        return parent::logout();
    }

    static function create_cookies($login, $password){
        $user = static::get_by_login(array($login));
        $user = $user[$login];
        $token = UBSite::get_token($login, $password, static::COOKIE_TOKEN_DURATION);
        $jxl_cookie_auth = new JuxtaLearn_Cookie_Authentication(get_config("jxl_secret"), ClipitSite::get_domain());
        $jxl_cookie_auth->set_required_cookie($user->login, $user->role, $user->id);
        $jxl_cookie_auth->set_name_cookie($user->name);
        $jxl_cookie_auth->set_token_cookie($token);
        $jxl_cookie_auth->set_mail_cookie($user->email);
    }

    static function delete_cookies(){
        $jxl_cookie_auth = new JuxtaLearn_Cookie_Authentication(get_config("jxl_secret"), ClipitSite::get_domain());
        $jxl_cookie_auth->delete_cookies();
    }

    /**
     * Get all Group Ids in which a user is member of.
     *
     * @param int $user_id Id of the user to get groups from.
     *
     * @return array Returns an array of Group IDs the user is member of.
     */
    static function get_groups($user_id){
        return UBCollection::get_items($user_id, ClipitGroup::REL_GROUP_USER, true);
    }

    /**
     * Get all Activity Ids in which a user is member of, or a teacher is in charge of.
     *
     * @param int $user_id Id of the user to get activities from.
     * @param bool $joined_only Only returnes Activities where a Student user has joined to a group.
     *
     * @return array Returns an array of Activity IDs the user is member of.
     */
    static function get_activities($user_id, $joined_only = false){
        $prop_value_array = static::get_properties($user_id, array("role"));
        $user_role = $prop_value_array["role"];
        switch ($user_role){
            case static::ROLE_STUDENT:
                if($joined_only){
                    $group_ids = static::get_groups($user_id);
                    if(empty($group_ids)){
                        return false;
                    }
                    foreach($group_ids as $group_id){
                        $activity_array[] = ClipitGroup::get_activity($group_id);
                    }
                    if(!isset($activity_array)){
                        return false;
                    }
                    return $activity_array;
                } else{
                    return UBCollection::get_items($user_id, ClipitActivity::REL_ACTIVITY_USER, true);
                }
            case static::ROLE_TEACHER:
                return UBCollection::get_items($user_id, ClipitActivity::REL_ACTIVITY_TEACHER, true);
        }
        return null;
    }

    /**
     * Sets a User role to Student.
     *
     * @param int $user_id User Id.
     *
     * @return int Returns the User Id if set correctly.
     */
    static function set_role_student($user_id){
        $prop_value_array["role"] = static::ROLE_STUDENT;
        return static::set_properties($user_id, $prop_value_array);
    }

    /**
     * Sets a User role to Teacher.
     *
     * @param int $user_id User Id.
     *
     * @return int Returns the User Id if set correctly.
     */
    static function set_role_teacher($user_id){
        $prop_value_array["role"] = static::ROLE_TEACHER;
        return static::set_properties($user_id, $prop_value_array);
    }

    /**
     * Sets a User role to Admin.
     *
     * @param int $user_id User Id.
     *
     * @return int Returns the User Id if set correctly.
     */
    static function set_role_admin($user_id){
        $prop_value_array["role"] = static::ROLE_ADMIN;
        return static::set_properties($user_id, $prop_value_array);
    }
}