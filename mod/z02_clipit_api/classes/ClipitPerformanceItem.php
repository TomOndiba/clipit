<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 20/05/14
 * Time: 16:09
 */

class ClipitPerformanceItem extends UBItem{
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitPerformanceItem";

    public $category = "";
    public $category_description = "";
    public $example = "";

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function load_from_elgg($elgg_object){
        parent::load_from_elgg($elgg_object);
        $this->category = (string)$elgg_object->get("category");
        $this->category_description = (string)$elgg_object->get("category_description");
        $this->example = (string)$elgg_object->get("example");
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_entity){
        parent::copy_to_elgg($elgg_entity);
        $elgg_entity->set("category", (string)$this->category);
        $elgg_entity->set("category_description", (string)$this->category_description);
        $elgg_entity->set("example", (string)$this->example);
    }

    static function get_by_category($category = null){
        $performance_items = static::get_all();
        $category_array = array();
        if(empty($category)){
            foreach($performance_items as $performance_item){
                $category_array[$performance_item->category][] = $performance_item;
            }
        } else{
            foreach($performance_items as $performance_item){
                if($performance_item->category == $category){
                    $category_array[$category] = $performance_item;
                }
            }
        }
        return $category_array;
    }

} 