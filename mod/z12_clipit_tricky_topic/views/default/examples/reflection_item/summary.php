<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   29/01/2015
 * Last update:     29/01/2015
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$items = elgg_extract('entities', $vars);
$items = ClipitReflectionItem::get_by_id($items);
$user_language = get_current_language();
$language_index = ClipitReflectionItem::get_language_index($user_language);
foreach($items as $item) {
    $categories[$item->category[$language_index]] = $item->category_description[$language_index];
}
?>
<?php
foreach($items as $item):
    if($item->category[$language_index] == $category):
        ?>
        <strong><?php echo $item->item_name[$language_index]; ?></strong>
        <div class="text-muted margin-bottom-10">
            <?php echo $item->item_description[$language_index]; ?>
        </div>
    <?php
    endif;
endforeach;
?>
<div style="
    background: #fff;
    padding: 5px;
    display: none;
" class="col-md-12 reflection-list">
<?php
    $x=1;
    foreach($categories as $category => $description):
        ?>


            <div style="
    background: #f1f2f7;
    padding: 10px;
    margin-bottom: 5px;
">

                <div class="row">
                    <div class="col-md-6">
                        <?php
                        foreach($items as $item):
                            if($item->category[$language_index] == $category):
                        ?>
                            <div class="margin-bottom-5" data-show="<?php echo $item->id;?>">
                                - <span class="blue cursor-default">
                                    <?php echo $item->item_name[$language_index]; ?>
                                </span>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <div class="col-md-6">
                        <strong class="show"><?php echo $category;?></strong>
                        <div>
                            <small><?php echo $description;?></small>
                        </div>
                        <?php
                        foreach($items as $item):
                            if($item->category[$language_index] == $category):
                        ?>
                            <div class="text-muted margin-bottom-10" id="<?php echo $item->id;?>" style="display: none;">
                                <?php echo $item->item_description[$language_index]; ?>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        <?php
        $x++;
    endforeach;
    ?>
</div>