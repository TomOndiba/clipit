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
 * A Stumbling Block identifier which is linked from one or more Tricky Topics, and which can be added as metadata to
 * Items or Resources.
 */
class ClipitTag extends UBItem {
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitTag";

    static function get_tricky_topics($id) {
        return UBCollection::get_items($id, ClipitTrickyTopic::REL_TRICKYTOPIC_TAG, true);
    }
}