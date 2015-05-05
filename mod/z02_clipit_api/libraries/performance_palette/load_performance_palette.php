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
$file_path = "z02_clipit_api/libraries/performance_palette/";
$file_name_en = "performance_palette_en.xlsx";
$file_name_es = "performance_palette_es.xlsx";
$file_name_de = "performance_palette_de.xlsx";
$file_name_pt = "performance_palette_pt.xlsx";
$file_name_pt = "performance_palette_sv.xlsx";
$key_name = "performance_palette";

// Check if Performance Palette was already loaded.
if(get_config($key_name) === true) {
    return;
} else{
    set_config($key_name, true);
}

// Load Performance Palette for site language
switch(get_config("language")){
    case "en":
        input_performance_palette_file(elgg_get_plugins_path() . $file_path . $file_name_en);
        break;
    case "es":
        input_performance_palette_file(elgg_get_plugins_path() . $file_path . $file_name_es);
        break;
    case "de":
        input_performance_palette_file(elgg_get_plugins_path() . $file_path . $file_name_de);
        break;
    case "pt":
        input_performance_palette_file(elgg_get_plugins_path() . $file_path . $file_name_pt);
        break;
    case "sv":
        input_performance_palette_file(elgg_get_plugins_path() . $file_path . $file_name_sv);
        break;
}

/**
 * Add Performance Items from an Excel file
 *
 * @param string $file Local file path
 *
 * @return array|null Array of User IDs, or null if error.
 */
function input_performance_palette_file($file){
    $php_excel = PHPExcel_IOFactory::load($file);
    $row_iterator = $php_excel->getSheet()->getRowIterator();
    while ($row_iterator->valid()) {
        parse_performance_palette_row($row_iterator->current());
        $row_iterator->next();
    }
}

/**
 * Parse a single role from an Excel file, containing one performance palette item, and add it to ClipIt
 *
 * @param PHPExcel_Worksheet_Row $row_iterator
 *
 * @return int|false ID of User contained in row, or false in case of error.
 */
function parse_performance_palette_row($row_iterator) {
    $prop_value_array = array();
    $cell_iterator = $row_iterator->getCellIterator();

    //columns: reference	language	name	description	example category	category_description

    // reference column (equal across all languages)
    // Check for title or empty rows
    $value = $cell_iterator->current()->getValue();
    if (empty($value) || strtolower($value) == "reference") {
        return null;
    }

    // name column
    $value = $cell_iterator->current()->getValue();
    $prop_value_array["item_name"] = (string)$value;
    $cell_iterator->next();

    // description column
    $value = $cell_iterator->current()->getValue();
    $prop_value_array["item_description"] = (string)$value;
    $cell_iterator->next();

    // example column
    $value = $cell_iterator->current()->getValue();
    $prop_value_array["example"] = (string)$value;
    $cell_iterator->next();

    // category column
    $value = $cell_iterator->current()->getValue();
    $prop_value_array["category"] = (string)$value;
    $cell_iterator->next();

    // category_description
    $value = $cell_iterator->current()->getValue();
    $prop_value_array["category_description"] = (string)$value;

    // Add Performance Item to ClipIt
    ClipitPerformanceItem::create($prop_value_array);
}