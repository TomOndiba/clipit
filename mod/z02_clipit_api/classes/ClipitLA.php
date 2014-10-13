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
 * Learning Analytics instance and interface class, for working with the external Learning Analytics Toolkit.
 */
class ClipitLA extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitLA";
    public $status_code = 0;
    public $metric_id = 0;
    public $file_id = 0;
    public $metric_received = false;


    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggObject $elgg_object Elgg Object to load parameters from.
     */
    protected function copy_from_elgg($elgg_object) {
        parent::copy_from_elgg($elgg_object);
        $this->status_code = (int)$elgg_object->get("status_code");
        $this->metric_id = (int)$elgg_object->get("metric_id");
        $this->file_id = (int)$elgg_object->get("file_id");
        $this->metric_received = (bool)$elgg_object->get("metric_received");
    }

    /**
     * Copy $this file parameters into an Elgg File entity.
     *
     * @param ElggObject $elgg_object Elgg object instance to save $this to
     */
    protected function copy_to_elgg($elgg_object) {
        parent::copy_to_elgg($elgg_object);
        $elgg_object->set("status_code", (int)$this->status_code);
        $elgg_object->set("metric_id", (int)$this->metric_id);
        $elgg_object->set("file_id", (int)$this->file_id);
        $elgg_object->set("metric_received", (bool)$this->metric_received);
    }

    /*
     */
    static function get_metric($metric_id, $context) {
        if(!$la_metrics_class = elgg_get_config("la_metrics_class")){
            return null;
        }
        $prop_value_array["metric_id"] = $metric_id;
        $return_id = static::set_properties(null, $prop_value_array);
        $la_metrics_class::get_metric($metric_id, $return_id, $context);
        return $return_id;
    }

    static function save_metric($return_id, $data, $status_code) {
        $prop_value_array["data"] = $data;
        $la_file_id = ClipitFile::set_properties(null, $prop_value_array);
        $prop_value_array = array();
        $prop_value_array["file_id"] = $la_file_id;
        $prop_value_array["status_code"] = (int)$status_code;
        $prop_value_array["metric_received"] = true;
        return static::set_properties($return_id, $prop_value_array);
    }
}