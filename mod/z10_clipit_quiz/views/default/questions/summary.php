<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   02/12/2014
 * Last update:     02/12/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */

$quiz = get_input('quiz');
$question_ids = ClipitQuiz::get_quiz_questions($quiz);
$questions = ClipitQuizQuestion::get_by_id($question_ids, 0, 0, 'order');
?>
<style>
    input[disabled]{
        margin-top: -2px;
        vertical-align: middle;
    }
</style>
<?php if($questions):?>
<table class="table">
    <thead>
    <tr>
        <th><?php echo elgg_echo('title');?></th>
        <th style="width: 100px;"><?php echo elgg_echo('difficulty');?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach($questions as $question):
    ?>
    <tr>
        <td>
            <strong class="margin-right-10 pull-left"><?php echo $i;?>.</strong>
            <div class="content-block">
                <strong>
                <?php echo elgg_view('output/url', array(
                    'href'  => "quizzes/questions/view/{$question->id}",
                    'title' => $question->name,
                    'text'  => $question->name,
                ));
                ?>
                </strong>
                <?php if($question->description):?>
                    <small class="show">
                        <?php echo $question->description;?>
                    </small>
                <?php endif;?>
                <?php echo elgg_view('tricky_topic/tags/view', array('tags' => $question->tag_array, 'limit' => 5)); ?>
                <div class="margin-top-5">
                <?php
                switch($question->option_type){
                    case ClipitQuizQuestion::TYPE_SELECT_MULTI:
                        echo elgg_view('quizzes/types/select_multi', array(
                            'question' => $question,
                            'finished' => true,
                            'finished_task' => true
                        ));
                        break;
                    case ClipitQuizQuestion::TYPE_TRUE_FALSE:
                        echo elgg_view('quizzes/types/true_false', array(
                            'question' => $question,
                            'finished' => true,
                            'finished_task' => true
                        ));
                        break;
                    case ClipitQuizQuestion::TYPE_SELECT_ONE:
                        echo elgg_view('quizzes/types/select_one', array(
                            'question' => $question,
                            'finished' => true,
                            'finished_task' => true
                        ));
                        break;
                    case ClipitQuizQuestion::TYPE_NUMBER:
                        echo elgg_view('quizzes/types/number', array(
                            'question' => $question,
                            'finished' => true,
                            'finished_task' => true
                        ));
                        break;
                }
                ?>
                </div>
            </div>
        </td>
        <td><?php echo difficulty_bar($question->difficulty);?></td>
    </tr>
    <?php
    $i++;
    endforeach;
    ?>
    </tbody>
</table>
<?php else: ?>
    <?php echo elgg_view('output/empty', array('value' => elgg_echo('examples:none')));;?>
<?php endif;?>