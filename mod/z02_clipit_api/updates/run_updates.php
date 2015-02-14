<?php
// CHANGE FROM HERE ...
$VERSION = "2.3";
$update_files = array(
    "2.2.0" => null,
    "2.2.1" => "update_2.2.1.php",
    "2.2.2" => "update_2.2.2.php",
    "2.2.3" => null,
    "2.2.4" => "update_2.2.4.php",
    "2.2.5" => "update_2.2.5.php",
    "2.3" => null
    // add here future updates: version => file
);
// ... TO HERE


$old_version = get_config("clipit_version");

// If no clipit_version in config, then it's a new install, set the version and exit.
if(empty($old_version)){
    set_config("clipit_version", $VERSION);
    return;
}

print_r("<p>Current version: $old_version<br>New version: $VERSION</p>");

if($VERSION === $old_version) return;



if(!empty($old_version)) {
    // advance until old version
    while (key($update_files) != $old_version) {
        next($update_files);
    }
    // skip old version update file
    next($update_files);
}
// apply all updates from there onwards
while(key($update_files) != null){
    $value = current($update_files);
    if(!empty($value)){
        include_once((string)$value);
        print_r("<p>Applied patch: $value</p>");
    }
    next($update_files);
};
set_config("clipit_version", $VERSION);
print_r("<p>Updated to version: $VERSION</p>");


