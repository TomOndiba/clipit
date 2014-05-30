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
 * Class ClipitQuizResult
 *
 */
class ClipitQuizResult extends UBItem{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "ClipitQuizResult";
    /**
     * @var int Id of ClipitQuizQuestion this ClipitQuizResult is related to
     */
    public $quiz_question = 0;
    /**
     * @var int Id of User who posted this Quiz Result
     */
    public $user = 0;
    /**
     * @var bool Determines if this Result is correct (true) or incorrect (false)
     */
    public $correct = false;

    protected function load_from_elgg($elgg_object){
        parent::load_from_elgg($elgg_object);
        $this->user = (int)$elgg_object->get("user");
        $this->correct = (bool)$elgg_object->get("correct");
        $this->quiz_question = (int)static::get_quiz_question($this->id);
    }

    /**
     * @param ElggObject $elgg_object Elgg object instance to save Item to
     */
    protected function copy_to_elgg($elgg_object){
        parent::copy_to_elgg($elgg_object);
        $elgg_object->set("correct", (bool)$this->correct);
        $elgg_object->set("user", (int)$this->user);
    }

    protected function save(){
        parent::save();
        if($this->quiz_question != 0){
            ClipitQuizQuestion::add_quiz_results($this->quiz_question, array($this->id));
        }
        return $this->id;
    }

    /**
     * Sets values to specified properties of an Item
     *
     * @param int   $id Id of Item to set property valyes
     * @param array $prop_value_array Array of property=>value pairs to set into the Item
     *
     * @return int|bool Returns Id of Item if correct, or false if error
     */
    static function set_properties($id, $prop_value_array){
        $new_prop_value_array = array();
        foreach($prop_value_array as $prop => $value){
            if($prop == "correct"){
                if($value == "true"){
                    $new_prop_value_array["correct"] = true;
                } elseif($value == "false"){
                    $new_prop_value_array["correct"] = false;
                } else{
                    $new_prop_value_array["correct"] = (bool)$value;
                }
            } else{
                $new_prop_value_array[$prop] = $value;
            }
        }
        return parent::set_properties($id, $new_prop_value_array);
    }

    static function get_quiz_question($id){
        $rel_array = get_entity_relationships($id, true);
        foreach($rel_array as $rel){
            if($rel->relationship == ClipitQuizQuestion::REL_QUIZQUESTION_QUIZRESULT){
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
     * @return array|bool Array of nested arrays per question with Quiz Results, or false if error
     */
    static function get_by_quiz_question($quiz_question_array){
        $quiz_result_array = array();
        foreach($quiz_question_array as $quiz_question){
            $result_array = ClipitQuizQuestion::get_quiz_results($quiz_question);
            $quiz_result_array[$quiz_question] = static::get_by_id($result_array);
        }
        return $quiz_result_array;
    }

}