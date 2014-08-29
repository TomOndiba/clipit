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
 * A Quiz Question containing a main question (in the "name" and "description" properties), with a set of Options,
 * a Validation array with the correct pattern of Options, a Difficulty value, can be Tagged, can be linked to a Video,
 * and contains links to all Results submitted by Students to this Question.
 */
class ClipitQuizQuestion extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitQuizQuestion";
    const REL_QUIZQUESTION_TAG = "ClipitQuizQuestion-ClipitTag";
    const REL_QUIZQUESTION_QUIZRESULT = "ClipitQuizQuestion-ClipitQuizResult";
    /**
     * @var array Array of options to chose from as an answer to the question
     */
    public $option_array = array();
    /**
     * @var array Array for validation of the options
     */
    public $validation_array = array();
    /**
     * @var string Type of Question: single choice, multiple choice, select 2...
     */
    public $option_type = "";
    /**
     * @var array Array of Tags relevant to this question
     */
    public $tag_array = array();
    /**
     * @var array Array of Quiz Results which answer this Quiz Question
     */
    public $quiz_result_array = array();
    /**
     * @var int ID of ClipitVideo refered to by this question (optional)
     */
    public $video = 0;
    /**
     * @var int Difficulty of the QuizQuestion, in an integer scale from 1 to 10.
     */
    public $difficulty = 0;

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_entity) {
        parent::copy_from_elgg($elgg_entity);
        $this->tag_array = static::get_tags($this->id);
        $this->quiz_result_array = static::get_quiz_results($this->id);
        $this->option_array = (array)$elgg_entity->get("option_array");
        $this->validation_array = (array)$elgg_entity->get("validation_array");
        $this->option_type = (string)$elgg_entity->get("option_type");
        $this->video = (int)$elgg_entity->get("video");
        $this->difficulty = (int)$elgg_entity->get("difficulty");
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity) {
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("option_array", (array)$this->option_array);
        $elgg_entity->set("validation_array", (array)$this->validation_array);
        $elgg_entity->set("option_type", (string)$this->option_type);
        $elgg_entity->set("video", (int)$this->video);
        $elgg_entity->set("difficulty", (int)$this->difficulty);
    }

    /**
    /**
     * Saves this instance to the system.
     * @param  bool $double_save if $double_save is true, this object is saved twice to ensure that all properties are updated properly. E.g. the time created property can only beset on ElggObjects during an update. Defaults to false!
     * @return bool|int Returns the Id of the saved instance, or false if error
     */
    protected function save($double_save=false) {
        parent::save($double_save);
        static::set_tags($this->id, $this->tag_array);
        static::set_quiz_results($this->id, $this->quiz_result_array);
        return $this->id;
    }

    /**
     * @param int   $id
     * @param array $result_array
     *
     * @return bool
     */
    static function add_quiz_results($id, $result_array) {
        return UBCollection::add_items($id, $result_array, static::REL_QUIZQUESTION_QUIZRESULT, true);
    }

    /**
     * @param int   $id
     * @param array $result_array
     *
     * @return bool
     */
    static function set_quiz_results($id, $result_array) {
        return UBCollection::set_items($id, $result_array, static::REL_QUIZQUESTION_QUIZRESULT, true);
    }

    /**
     * @param $id
     * @param $result_array
     *
     * @return bool
     */
    static function remove_quiz_results($id, $result_array) {
        return UBCollection::remove_items($id, $result_array, static::REL_QUIZQUESTION_QUIZRESULT);
    }

    /**
     * Get all Quiz Results from a specified Quiz Question.
     *
     * @param int $id Id of Quiz Question to get Results form
     *
     * @return array|bool Array of Quiz Result IDs, or false if error
     */
    static function get_quiz_results($id) {
        return UBCollection::get_items($id, static::REL_QUIZQUESTION_QUIZRESULT);
    }

    /**
     * Add a list of Stumbling Block Tags to a Quiz Question.
     *
     * @param int   $id        Id of the Quiz Question
     * @param array $tag_array Array of Tags to add to the Quiz Question
     *
     * @return bool True if success, false if error
     */
    static function add_tags($id, $tag_array) {
        return UBCollection::add_items($id, $tag_array, static::REL_QUIZQUESTION_TAG);
    }

    /**
     * Set a list of Stumbling Block Tags to a Quiz Question.
     *
     * @param int   $id        Id of the Quiz Question
     * @param array $tag_array Array of Tags to set to the Quiz Question
     *
     * @return bool True if success, false if error
     */
    static function set_tags($id, $tag_array) {
        return UBCollection::set_items($id, $tag_array, static::REL_QUIZQUESTION_TAG);
    }

    /**
     * Remove a list of Stumbling Block Tags from a Quiz Question.
     *
     * @param int   $id        Id of the Quiz Question
     * @param array $tag_array Array of Tags to remove from the Quiz Question
     *
     * @return bool True if success, false if error
     */
    static function remove_tags($id, $tag_array) {
        return UBCollection::remove_items($id, $tag_array, static::REL_QUIZQUESTION_TAG);
    }

    /**
     * Get all Stumbling Block Tags for a Quiz Question.
     *
     * @param int $id Id from the Quiz Question to get Stumbling Block Tags from
     *
     * @return array|bool Returns an array of Tag IDs, or false if error
     */
    static function get_tags($id) {
        return UBCollection::get_items($id, static::REL_QUIZQUESTION_TAG);
    }
}