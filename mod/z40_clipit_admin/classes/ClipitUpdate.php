<?php

/**
 * Created by PhpStorm.
 * User: Pablo Llin�s
 * Date: 16/06/2015
 * Time: 11:37
 */
class ClipitUpdate
{
    const VERSIONS_FILE = "versions.json";

    static function set_clipit_version($clipit_version){
        return set_config("clipit_version", (string)$clipit_version);
    }

    static function update_clipit(){
        // Pull latest version from GitHub
        chdir(elgg_get_root_path());
        exec("git stash save");
        exec("git clean -f");
        exec("git fetch origin refs/tags/*:refs/tags/*");
        $clipit_tag_branch = get_config("clipit_tag_branch");
        $latest_tag = exec("git for-each-ref --sort=committerdate --format='%(refname:short)' refs/tags | grep $clipit_tag_branch | tail -1");
        exec("git checkout $latest_tag");
        exec("git submodule init");
        exec("git submodule update");
        return $latest_tag;
    }

    static function run_update_scripts(){
        $versions_obj = json_decode(
            file_get_contents(elgg_get_plugins_path()."z40_clipit_admin/updates/".static::VERSIONS_FILE));
        $new_version = (string)$versions_obj->clipit_version;
        $update_scripts = (array)$versions_obj->update_scripts;
        $old_version = get_config("clipit_version");

        // If no clipit_version in config, then treat it as oldest version possible.
        if(empty($old_version)) {
            $old_version = "2.2.0";
        }

        // If already up-to-date, exit.
        if($new_version === $old_version){
            return true;
        }

        // set the new version to avoid overlapping updates
        set_config("clipit_version", $new_version);

        // advance until old version
        while (key($update_scripts) != $old_version) {
            next($update_scripts);
        }
        // skip old version's update file
        next($update_scripts);
        // apply all updates from there onwards
        while(key($update_scripts) != null){
            $value = current($update_scripts);
            if(!empty($value)){
                include_once(elgg_get_plugins_path()."z40_clipit_admin/updates/$value");
            }
            next($update_scripts);
        }
        return true;
    }

    static function flush_caches(){
        elgg_invalidate_simplecache();
        elgg_reset_system_cache();
        return true;
    }
}