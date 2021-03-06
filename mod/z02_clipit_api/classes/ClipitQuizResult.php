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
 * A Student Result for a Quiz Question, with a link to it, and a boolean value to show whether it is Correct or not.
 */
class ClipitQuizResult extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitQuizResult";
    const REL_QUIZRESULT_CLIPITUSER = "ClipitQuizResult-ClipitUser";
    const REL_QUIZRESULT_QUIZQUESTION = "ClipitQuizResult-ClipitQuizQuestion";
    /**
     * @var int Id of ClipitQuizQuestion this ClipitQuizResult is related to
     */
    public $quiz_question = 0;

    // can be different types, depending on the question type
    public $answer;
    /**
     * @var bool Determines if this Result is correct (true) or incorrect (false) - computed by "evaluate_result"
     */
    public $correct = false;

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->answer = $elgg_entity->get("answer");
        $this->correct = (bool)$elgg_entity->get("correct");
        $this->quiz_question = (int)static::get_quiz_question($this->id);
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity) {
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("answer", $this->answer);
        $elgg_entity->set("correct", (bool)$this->correct);
    }

    /**
     * Saves this instance to the system.
     * @param  bool $double_save if $double_save is true, this object is saved twice to ensure that all properties are updated properly. E.g. the time created property can only beset on ElggObjects during an update. Defaults to false!
     * @return bool|int Returns the Id of the saved instance, or false if error
     */
    protected function save($double_save=false) {
        // no quiz question specified: just save object and exit.
        if(empty($this->quiz_question)) {
            return parent::save($double_save);
        }
        // get possible previous result for this user and for target question.
        $prev_result = static::get_from_question_user($this->quiz_question, elgg_get_logged_in_user_guid());
        // if no previous result, save new object, add relationship and exit.
        if(empty($prev_result)){
            parent::save($double_save);
            ClipitQuizQuestion::add_quiz_results($this->quiz_question, array($this->id));
            return $this->id;
        }
        // if there is a previous result with different id than this->id (which may be empty yet),
        // we take the existing result->id as this->id, and save the current properties onto that id.
        if(!empty($prev_result) && $prev_result->id !== $this->id){
            $this->id = $prev_result->id;
        }
        // else if a previous result exists but has the same id as $this (older version of us),
        // then update with current properties and exit.
        return parent::save($double_save);
    }

    /**
     * Sets values to specified properties of an Item
     *
     * @param int   $id Id of Item to set property values
     * @param array $prop_value_array Array of property=>value pairs to set into the Item
     *
     * @return int|bool Returns Id of Item if correct, or false if error
     * @throws InvalidParameterException
     */
    static function set_properties($id, $prop_value_array) {
        $new_prop_value_array = array();
        foreach($prop_value_array as $prop => $value) {
            if($prop == "correct") {
                if(strtolower($value) == "true") {
                    $new_prop_value_array["correct"] = true;
                } elseif(strtolower($value) == "false") {
                    $new_prop_value_array["correct"] = false;
                } else {
                    $new_prop_value_array["correct"] = (bool)$value;
                }
            } if(($prop == "answer") && (!is_array($value))){
                $new_prop_value_array["answer"] = json_decode($value);
            } else {
                $new_prop_value_array[$prop] = $value;
            }
        }
        return parent::set_properties($id, $new_prop_value_array);
    }

    static function evaluate_result($result_id){
        if(empty($result_id)){
            return null;
        }
        $result_properties = static::get_properties($result_id, array("answer", "quiz_question"));
        $answer = $result_properties["answer"];
        $quiz_question = $result_properties["quiz_question"];

        $question_properties = ClipitQuizQuestion::get_properties($quiz_question, array("option_type", "validation_array"));
        $option_type = (string)$question_properties["option_type"];
        $validation_array = $question_properties["validation_array"];

        switch($option_type){
            case ClipitQuizQuestion::TYPE_SELECT_ONE:
            case ClipitQuizQuestion::TYPE_SELECT_MULTI:
            case ClipitQuizQuestion::TYPE_TRUE_FALSE:
                // answer is correct until proven wrong
                $correct = true;
                if(!is_array($answer) || count($answer) != count($validation_array)){
                    $correct = false;
                } else{
                    $pos = 0;
                    while($pos < count($answer)){
                        if((bool)$answer[$pos] != (bool)$validation_array[$pos]){
                            $correct = false;
                            break;
                        } else{
                            $pos++;
                        }
                    }
                }
                return static::set_properties($result_id, array("correct" => $correct));
            case ClipitQuizQuestion::TYPE_NUMBER:
                if((float)$answer == (float)$validation_array[0]){
                    $correct = true;
                } else{
                    $correct = false;
                }
                return static::set_properties($result_id, array("correct" => $correct));
        }
        return null;
    }

    static function get_quiz_question($id) {
        $rel_array = get_entity_relationships($id, true);
        foreach($rel_array as $rel) {
            if($rel->relationship == ClipitQuizQuestion::REL_QUIZQUESTION_QUIZRESULT) {
                return $question_id = $rel->guid_one;
            }
        }
        return 0;
    }

    /**
     * Get Quiz Results by Quiz Questions
     *
     * @param array $quiz_question_array Array of Quiz Question IDs to get Results form
     *
     * @return array|bool Array of Quiz Results nested per Quiz Question IDs, or false if error
     */
    static function get_by_quiz_question($quiz_question_array) {
        $quiz_result_array = array();
        foreach($quiz_question_array as $quiz_question_id) {
            $result_array = ClipitQuizQuestion::get_quiz_results($quiz_question_id);
            $quiz_result_array[$quiz_question_id] = static::get_by_id($result_array);
        }
        return $quiz_result_array;
    }

    /**
     * Returns the ClipitQuizResult ID for a Quiz Question submitted by a User
     *
     * @param int $quiz_question_id
     * @param int $user_id
     * @return static The Quiz Result, or 0 if none found.
     */
    static function get_from_question_user($quiz_question_id, $user_id){
        $result_array = static::get_by_owner(array($user_id));
        if(!empty($result_array)&& !empty($result_array[$user_id])) {
            foreach ($result_array[$user_id] as $quiz_result) {
                if ($quiz_result->quiz_question === $quiz_question_id) {
                    return $quiz_result;
                }
            }
        }
        return 0;
    }
}