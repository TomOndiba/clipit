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
 * Class ClipitRating
 *
 */
class ClipitRating extends UBItem{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "ClipitRating";

    const REL_RATING_TAGRATING = "rating-tag_rating";
    const REL_RATING_PERFORMANCERATING = "rating-performance_rating";

    public $target = 0;
    /**
     * @var int Overall rating opinionfrom 0 to 10
     */
    public $overall_rating = -1;
    /**
     * @var array Ratings about Tags used"
     */
    public $tag_rating_array = array();
    /**
     * @var array Ratings about Performance tips used"
     */
    public $performance_rating_array = array();


    protected function load_from_elgg($elgg_object){
        parent::load_from_elgg($elgg_object);
        $this->target = (int)$elgg_object->target;
        $this->overall_rating = (int)$elgg_object->overall_rating;
        $this->tag_rating_array = (array)static::get_tag_ratings($this->id);
        $this->performance_rating_array = (array)static::get_performance_ratings($this->id);
    }

    /**
     * @param ElggObject $elgg_object Elgg object instance to save Item to
     */
    protected function copy_to_elgg($elgg_object){
        parent::copy_to_elgg($elgg_object);
        $elgg_object->target = (int)$this->target;
        $elgg_object->overall_rating = (int)$this->overall_rating;
    }

    protected function save(){
        parent::save();
        static::set_tag_ratings($this->id, (array)$this->tag_rating_array, static::REL_RATING_TAGRATING);
        static::set_performance_ratings($this->id, (array)$this->performance_rating_array, static::REL_RATING_PERFORMANCERATING);
        return $this->id;
    }

    static function add_tag_ratings($rating_id, $tag_rating_array){
        return UBCollection::add_items($rating_id, $tag_rating_array, static::REL_RATING_TAGRATING);
    }

    static function set_tag_ratings($rating_id, $tag_rating_array){
        return UBCollection::set_items($rating_id, $tag_rating_array, static::REL_RATING_TAGRATING);
    }

    static function remove_tag_ratings($rating_id, $tag_rating_array){
        return UBCollection::remove_items($rating_id, $tag_rating_array, static::REL_RATING_TAGRATING);
    }

    static function get_tag_ratings($rating_id){
        return UBCollection::get_items($rating_id, static::REL_RATING_TAGRATING);
    }

    static function add_performance_ratings($rating_id, $performance_rating_array){
        return UBCollection::add_items($rating_id, $performance_rating_array, static::REL_RATING_PERFORMANCERATING);
    }

    static function set_performance_ratings($rating_id, $performance_rating_array){
        return UBCollection::set_items($rating_id, $performance_rating_array, static::REL_RATING_PERFORMANCERATING);
    }

    static function remove_performance_ratings($rating_id, $performance_rating_array){
        return UBCollection::remove_items($rating_id, $performance_rating_array, static::REL_RATING_PERFORMANCERATING);
    }

    static function get_performance_ratings($rating_id){
        return UBCollection::get_items($rating_id, static::REL_RATING_PERFORMANCERATING);
    }


}

