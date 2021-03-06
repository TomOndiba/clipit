<?php
/**
 * Clipit - Clipit Web Space
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, URJC Clipit Team
 * @version         $Version$
 * @link            http://clipit.es
 * @license         GNU Affero General Public License v3
 * @package         Clipit
 * @subpackage      clipit_api
 */

/**
 * Expose all functions for the Clipit REST API
 */
// Load and call REST API Expose functions
loadFiles(elgg_get_plugins_path()."z02_clipit_api/libraries/clipit_rest_api/expose_functions/");
expose_activity_functions();
expose_chat_functions();
expose_comment_functions();
expose_event_functions();
expose_example_functions();
expose_example_type_functions();
expose_file_functions();
expose_group_functions();
expose_la_functions();
expose_label_functions();
expose_post_functions();
expose_quiz_functions();
expose_quiz_question_functions();
expose_quiz_result_functions();
expose_rating_functions();
expose_remote_tricky_topic_functions();
expose_remote_activity_functions();
expose_remote_video_functions();
expose_remote_file_functions();
expose_remote_site_functions();
expose_rubric_item_functions();
expose_rubric_rating_functions();
expose_site_functions();
expose_tag_functions();
expose_tag_rating_functions();
expose_task_functions();
expose_tricky_topic_functions();
expose_user_functions();
expose_video_functions();
