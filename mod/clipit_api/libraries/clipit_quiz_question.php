<?php
/**
 * @package clipit\quiz\question
 */
namespace clipit\quiz\question;

    /**
     * ClipIt - JuxtaLearn Web Space
     * PHP version:     >= 5.2
     * Creation date:   2013-10-10
     * Last update:     $Date$
     *
     * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, JuxtaLearn Project
     * @version         $Version$
     * @link            http://juxtalearn.org
     * @license         GNU Affero General Public License v3
     *                  (http://www.gnu.org/licenses/agpl-3.0.txt)
     *                  This program is free software: you can redistribute it and/or modify
     *                  it under the terms of the GNU Affero General Public License as
     *                  published by the Free Software Foundation, version 3.
     *                  This program is distributed in the hope that it will be useful,
     *                  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *                  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     *                  GNU Affero General Public License for more details.
     *                  You should have received a copy of the GNU Affero General Public License
     *                  along with this program. If not, see
     *                  http://www.gnu.org/licenses/agpl-3.0.txt.
     */

/**
 * Lists the properties contained in this class.
 *
 * @return array Array of properties with type and default value
 */
function list_properties(){
    return get_class_vars(__NAMESPACE__."\\ClipitQuizQuestion");
}

/**
 * Get the values for the specified properties of a QuizQuestion.
 *
 * @param int $id Id from QuizQuestion
 * @param array $prop_array Array of property names to get values from
 * @return array|bool Returns array of [property => value] pairs, or false if error.
 * If a property does not exist, the returned array will show null as that propertie's value.
 */
function get_properties($id, $prop_array){
    $quiz_question = new ClipitQuizQuestion($id);
    if(!$quiz_question){
        return false;
    }
    $value_array = array();
    for($i = 0; $i < count($prop_array); $i++){
        $value_array[$i] = $quiz_question->$prop_array[$i];
    }
    return array_combine($prop_array, $value_array);
}

/**
 * Set values to specified properties of a QuizQuestion.
 *
 * @param int $id Id from QuizQuestion
 * @param array $prop_array Array of properties to set values into
 * @param array $value_array Array of associated values to set into properties
 * @return bool Returns true if success, false if error
 * @throws \InvalidParameterException If count(prop_array) != count(value_array)
 */
function set_properties($id, $prop_array, $value_array){
    if(count($prop_array) != count($value_array)){
        throw(new \InvalidParameterException(
            "ERROR: The length of prop_array and value_array must match."));
    }
    $quiz_question = new ClipitQuizQuestion($id);
    if(!$quiz_question){
        return false;
    }
    for($i = 0; $i < count($prop_array); $i++){
        $quiz_question->$prop_array[$i] = $value_array[$i];
    }
    if(!$quiz_question->save()){
        return false;
    }
    return true;
}

/**
 * Create a new ClipitQuizQuestion instance, and save it into the system.
 *
 * @param string $question Question in text form to be presented to the user taking the quiz
 * @param array $option_array Array of Options presented to the user to choose from
 * @param string $option_type Type of Options (select 1 only, select 2, select any...)
 * @param array $taxonomy_tag_array Array of tags linking the question to the taxonomy (optional)
 * @param int $video Id of video to which the question relates to (optional)
 * @return bool|int Returns the new Quiz Question Id, or false if error
 */
function create($question,
                $option_array,
                $option_type,
                $taxonomy_tag_array = array(),
                $video = -1){
    $quiz_question = new ClipitQuizQuestion();
    $quiz_question->question = $question;
    $quiz_question->option_array = $option_array;
    $quiz_question->option_type = $option_type;
    $quiz_question->taxonomy_tag_array = $taxonomy_tag_array;
    $quiz_question->video = $video;
    return $quiz_question->save();
}

/**
 * Delete a Quiz Question from the system.
 *
 * @param int $id Id from the Quiz Question to delete
 * @return bool True if success, false if error
 */
function delete($id){
    if(!$quiz_question = new ClipitQuizQuestion($id)){
        return false;
    }
    return $quiz_question->delete();
}

/**
 * Add a list of Tags from the Taxonomy to a Quiz Question.
 *
 * @param int $id Id of the Quiz Question
 * @param array $taxonomy_tag_array Array of Taxonomy Tags to add to the Quiz Question
 * @return bool True if success, false if error
 */
function add_taxonomy_tags($id, $taxonomy_tag_array){
    if(!$quiz_question = new ClipitQuizQuestion($id)){
        return false;
    }
    if(!$quiz_question->taxonomy_tag_array){
        $quiz_question->taxonomy_tag_array = $taxonomy_tag_array;
    } else{
        $quiz_question->taxonomy_tag_array = array_merge($quiz_question->taxonomy_tag_array, $taxonomy_tag_array);
    }
    if(!$quiz_question->save()){
        return false;
    }
    return true;
}

/**
 * Get all Quiz Questions from the system.
 *
 * @param int $limit Number of results to show, default= 0 [no limit] (default)
 * @return array Returns an array of ClipitQuizQuestion objects
 */
function get_all($limit = 0){
    $quiz_question_array = array();
    $elgg_object_array = elgg_get_entities(array('type' => ClipitQuizQuestion::TYPE,
                                                 'subtype' => ClipitQuizQuestion::SUBTYPE,
                                                 'limit' => $limit));
    if(!$elgg_object_array){
        return $quiz_question_array;
    }
    $i = 0;
    foreach($elgg_object_array as $elgg_object){
        $quiz_question_array[$i] = new ClipitQuizQuestion($elgg_object->guid);
        $i++;
    }
    return $quiz_question_array;
}

/**
 * Get all Quiz Results from a specified Quiz Question.
 *
 * @param int $id Id of Quiz Question to get Results form
 * @param int $limit Number of results to show, default = 0 [no limit] (optional)
 * @return array|bool Array of Quiz Results, or false if error
 */
function get_results($id, $limit = 0){
    return $quiz_result_array = \clipit\quiz\result\get_from_question($id, $limit);
}

/**
 * Get all Quizz Questions with Id contained in a given list.
 *
 * @param array $id_array Array of Quiz Question Ids
 * @return array Returns an array of ClipitQuizQuestion objects
 */
function get_by_id($id_array){
    $quiz_question_array = array();
    $i = 0;
    foreach($id_array as $id){
        if(elgg_entity_exists((int) $id)){
            $quiz_question_array[$i] = new ClipitQuizQuestion((int) $id);
        } else{
            $quiz_question_array[$i] = null;
        }
        $i++;
    }
    return $quiz_question_array;
}

