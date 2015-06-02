<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, URJC JuxtaLearn Team
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 * @subpackage      clipit_admin
 */

// UPDATE FROM HERE {
$VERSION = "2.3.18";
$update_files = array(
    // new versions must be inserted in to $update_files array, even if null
    "2.2.0" => null,
    "2.2.1" => "update_2.2.1.php",
    "2.2.2" => "update_2.2.2.php",
    "2.2.3" => null,
    "2.2.4" => "update_2.2.4.php",
    "2.2.5" => "update_2.2.5.php",
    "2.3" => null,
    "2.3.1" => null,
    "2.3.2" => null,
    "2.3.3" => "update_2.3.3.php",
    "2.3.4" => "update_2.3.4.php",
    "2.3.5" => "update_2.3.5.php",
    "2.3.6" => "update_2.3.6.php",
    "2.3.7" => "update_2.3.7.php",
    "2.3.8" => "update_2.3.8.php",
    "2.3.9" => "update_2.3.9.php",
    "2.3.9.1" => null,
    "2.3.10" => null,
    "2.3.11" => "update_2.3.11.php",
    "2.3.12" => "update_2.3.12.php",
    "2.3.13" => null,
    "2.3.14" => "update_2.3.14.php",
    "2.3.15" => "update_2.3.15.php",
    "2.3.16" => null,
    "2.3.17" => null,
    "2.3.18" => "update_2.3.18.php",
    // add here future updates: "version_number" => "file_name"
);
// } TO HERE

$old_version = get_config("clipit_version");

// If no clipit_version in config, then treat it as oldest version possible.
if(empty($old_version)) {
    $old_version = "2.2.0";
}

system_message("<p>Current version: $old_version<br>New version: $VERSION</p>");

// If already up-to-date, exit.
if($VERSION === $old_version){
    system_message("No updates to apply");
    return;
}

// set the new version to avoid overlapping updates
set_config("clipit_version", $VERSION);

// advance until old version
while (key($update_files) != $old_version) {
    next($update_files);
}
// skip old version's update file
next($update_files);
// apply all updates from there onwards
while(key($update_files) != null){
    var_dump($value = current($update_files));
    if(!empty($value)){
        include_once(elgg_get_plugins_path()."z40_clipit_admin/updates/$value");
        system_message("Applied update: $value");
    }
    next($update_files);
}

// Flush cache
system_message("<p>Flushing caches...");
elgg_invalidate_simplecache();
elgg_reset_system_cache();

// Update ClipIt version
system_message("<p>Updated to version: $VERSION</p>");
