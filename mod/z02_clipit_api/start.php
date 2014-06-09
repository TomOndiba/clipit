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
 * Register the init method
 */
elgg_register_event_handler('init', 'system', 'clipit_api_init');

/**
 * Initialization method which loads objects, libraries, exposes the REST API, and registers test classes.
 */
function clipit_api_init(){
    loadFiles(elgg_get_plugins_path() . "z02_clipit_api/libraries/");
    loadFiles(elgg_get_plugins_path() . "z02_clipit_api/libraries/juxtalearn-cookie-authentication/");
    expose_clipit_api();
    rename_subtypes(); // temporal to avoid loosing data
    //include_once(elgg_get_plugins_path() . "z02_clipit_api/activate.php");

    elgg_register_page_handler('youtube_auth', 'youtube_auth_page_handler');
}

function youtube_auth_page_handler($page){
    $title = "YouTube Authentication";
    $params = array(
        'content' => elgg_view("youtube_auth"),
        'title' => $title,
        'filter' => "",
    );
    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page($title, $body);
}

/**
 * Replace old subtypes with class names, for easier reference.
 */
function rename_subtypes(){
    global $CONFIG;
    $replace["clipit_activity"] = ClipitActivity::SUBTYPE;
    $replace["clipit_chat"] = ClipitChat::SUBTYPE;
    $replace["clipit_comment"] = ClipitComment::SUBTYPE;
    $replace["clipit_example"] = ClipitExample::SUBTYPE;
    $replace["clipit_file"] = ClipitFile::SUBTYPE;
    $replace["clipit_group"] = ClipitGroup::SUBTYPE;
    $replace["clipit_la"] = ClipitLA::SUBTYPE;
    $replace["clipit_performance_item"] = ClipitPerformanceItem::SUBTYPE;
    $replace["clipit_performance_rating"] = ClipitPerformanceRating::SUBTYPE;
    $replace["clipit_post"] = ClipitPost::SUBTYPE;
    $replace["clipit_quiz"] = ClipitQuiz::SUBTYPE;
    $replace["clipit_quiz_question"] = ClipitQuizQuestion::SUBTYPE;
    $replace["clipit_quizquestion"] = ClipitQuizQuestion::SUBTYPE;
    $replace["clipit_quiz_result"] = ClipitQuizResult::SUBTYPE;
    $replace["clipit_quizresult"] = ClipitQuizResult::SUBTYPE;
    $replace["clipit_rating"] = ClipitRating::SUBTYPE;
    $replace["clipit_site"] = ClipitSite::SUBTYPE;
    $replace["clipit_sta"] = ClipitSTA::SUBTYPE;
    $replace["clipit_storyboard"] = ClipitStoryboard::SUBTYPE;
    $replace["clipit_tag"] = ClipitTag::SUBTYPE;
    $replace["clipit_tag_rating"] = ClipitTagRating::SUBTYPE;
    $replace["clipot_tagrating"] = ClipitTagRating::SUBTYPE;
    $replace["clipit_task"] = ClipitTask::SUBTYPE;
    $replace["clipit_tricky_topic"] = ClipitTrickyTopic::SUBTYPE;
    $replace["clipit_trickytopic"] = ClipitTrickyTopic::SUBTYPE;
    $replace["clipit_video"] = ClipitVideo::SUBTYPE;
    foreach($replace as $old => $new){
        $query = "update {$CONFIG->dbprefix}entity_subtypes set subtype = \"{$new}\" where subtype = \"{$old}\";";
        update_data($query);
    }
}

