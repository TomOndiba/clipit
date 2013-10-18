<?php

/**
 * [Short description/title for module]
 *
 * [Long description for module]
 *
 * PHP version:      >= 5.2
 *
 * Creation date:    [YYYY-MM-DD]
 * Last update:      $Date$
 *
 * @category         [name]
 * @package          [name]
 * @subpackage       [name]
 * @author           Pablo Llinás Arnaiz <pebs74@gmail.com>
 * @version          $Version$
 * @link             [URL description]
 *
 * @license          GNU Affero General Public License v3
 * http://www.gnu.org/licenses/agpl-3.0.txt
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, version 3. *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details. *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see
 * http://www.gnu.org/licenses/agpl-3.0.txt.
 */

class ClipitUser{

    // Class properties
    public $id;
    public $login;
    public $password;
    public $password_hash;
    public $description;
    public $email;
    public $name;
    public $role;
    public $time_created;

    function __construct($id = null){
        $this->description = "";
        $this->email = "";
        $this->name = "";
        $this->id = -1;
        $this->login = "";
        $this->password = "";
        $this->password_hash = "";
        $this->role = "basic";
        $this->time_created = -1;
        if(!$id){
            $elgg_user = new ElggUser();
            $id = $elgg_user->save();
            $this->id = $id;
            $this->login = "user_".$id;
            $this->save();
        }
        $this->load($id);
    }

    function save(){
        $elgg_user = new ElggUser($this->id);
        if(!$elgg_user){
            return false;
        }
        $elgg_user->set("description", $this->description);
        $elgg_user->set("email", $this->email);
        $elgg_user->set("name", $this->name);
        $elgg_user->set("username", $this->login);
        $elgg_user->set("password", $this->password);
        $elgg_user->set("salt", $this->password_hash);
        $elgg_user->set("role", $this->role);
        return $elgg_user->save();
    }

    function load($id){
        $elgg_user = new ElggUser($id);
        if(!$elgg_user || !is_a($elgg_user, "ElggUser")){
            return null;
        }
        $this->description = $elgg_user->get("description");
        $this->email = $elgg_user->get("email");
        $this->name = $elgg_user->get("name");
        $this->id = $elgg_user->get('guid');
        $this->login = $elgg_user->get("username");
        $this->password = $elgg_user->get("password");
        $this->password_hash = $elgg_user->salt;
        $this->role = $elgg_user->get("role");
        $this->time_created = $elgg_user->get("time_created");
        return $this;
    }
}