<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   14/05/14
 * Last update:     14/05/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entity = elgg_extract("entity", $vars);
$type = elgg_extract("type", $vars);
$user_id = elgg_get_logged_in_user_guid();
$user = array_pop(ClipitUser::get_by_id(array($user_id)));

$owner_options = "";
if($entity->owner_id == $user_id || $user->role == ClipitUser::ROLE_TEACHER){
    $options = array(
        'entity' => $entity,
        'edit' => array(
            "data-target" => "#edit-{$type}-{$entity->id}",
            "href" => elgg_get_site_url()."ajax/view/modal/multimedia/{$type}/edit?id={$entity->id}",
            "data-toggle" => "modal"
        ),
    );
    if($entity->owner_id == $user_id){
        $options['remove'] = array("href" => "action/multimedia/{$type}s/remove?id={$entity->id}");
    }
    $owner_options = elgg_view("page/components/options_list", $options);
    // Remote modal, form content
    echo elgg_view("page/components/modal_remote", array('id'=> "edit-{$type}-{$entity->id}" ));
}

echo $owner_options;