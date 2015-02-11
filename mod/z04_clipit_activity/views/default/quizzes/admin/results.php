<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   17/11/2014
 * Last update:     17/11/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$quiz_id = get_input('quiz');
$question_id = get_input('question');
$task_id = get_input('task');
$users = get_input('users');

$default = "";
switch($type = get_input('type')){
    case 'pending':
    case 'correct':
    case 'error':
        $users = array_filter($users);
        foreach ($users as $key => $user_id):
            if(is_array($user_id)){
                $user_id = array_pop($user_id);
                $key = 'not_answered';
            }
            $user = array_pop(ClipitUser::get_by_id(array($user_id)));
            echo elgg_view('quizzes/admin/user_results', array(
                'user' => $user,
                'type' => $type,
                'quiz_id' => $quiz_id,
                'subtype' => $key,
            ));
        endforeach;
        break;
    case 'answer':
        $finished = true;
        $result = ClipitQuizResult::get_from_question_user((int)$question_id, get_input('user'));
        $question = array_pop(ClipitQuizQuestion::get_by_id(array($question_id)));
        if($result) {
            echo elgg_view('output/url', array(
                'title' => elgg_echo('quiz:question:annotate'),
                'text' => '<i class="fa fa-edit"></i> <strong>' . elgg_echo('quiz:question:annotate') . '</strong>',
                'href' => 'javascript:;',
                'class' => 'btn btn-xs btn-border-blue pull-right',
                'onclick' => '$(this).parent(\'.answer\').find(\'.annotate\').toggle()',
            ));
        }
        echo '<div class="clearfix"></div>';
        echo elgg_view_form('quiz/question_annotate', array(
            'body' => elgg_view('quizzes/admin/annotation', array('result' => $result, 'question' => $question))
        ));
        break;
    default:
        $task = array_pop(ClipitTask::get_by_id(array($task_id)));
        $quiz = array_pop(ClipitQuiz::get_by_id(array($quiz_id)));
        $entities = ClipitActivity::get_students($task->activity);
        $results = array_pop(ClipitQuizResult::get_by_quiz_question(array($question_id)));
        $finished_task = $task->end <= time() ? true : false;

        $users = array();
        $us = array();
        foreach($results as $result){
            if($result->correct) {
                $users['correct'][] = $result->owner_id;
            } else {
                $users['error'][] = $result->owner_id;;
            }
            $us[] = $result->owner_id;
        }
        foreach(array_diff($entities, $us) as $user_pending){
            if(ClipitQuiz::has_finished_quiz($quiz->id, $user_pending) || $finished_task){
                $users['error'][]['not_answered'] = $user_pending;
                $us[] = $user_pending;
            }
        }
        $users['pending'] = array_diff($entities, $us);
        if($vars['count']){
            echo json_encode(array(
                'error' => count($users['error']),
                'correct' => count($users['correct']),
                'pending' => count($entities) - count($us),
            ));
            die;
        }
        $id = uniqid();
    ?>
    <div class="panel-group panel-results" data-question="<?php echo $question_id;?>">
        <div class="panel panel-default" style="border:0;border-radius: 0;box-shadow: none;">
            <div class="panel-heading cursor-pointer results-filter"
                 data-users="<?php echo htmlentities(json_encode($users['error']));?>"
                 data-type="error">
                <h4 class="panel-title blue" href="#e_<?php echo $id;?>" data-toggle="collapse" data-parent=".panel-results" style="font-size: 18px;">
                    <div class="pull-right">
                        <span class="text-muted margin-right-10 count">
                            <?php echo count($users['error']);?>
                        </span>
                        <i class="fa fa-angle-down blue"></i>
                    </div>
                    <i class="fa fa-times blue"></i>
                    Incorrect
                </h4>
            </div>
            <div class="panel-collapse collapse" id="e_<?php echo $id;?>">
            <ul class="panel-body"></ul>
            </div>
        </div>
        <div class="panel panel-default " style="border:0;border-radius: 0;box-shadow: none;">
            <div class="panel-heading cursor-pointer results-filter"
                 data-users="<?php echo htmlentities(json_encode($users['pending']));?>"
                 data-type="pending">
                <h4 class="panel-title blue" data-toggle="collapse" href="#p_<?php echo $id;?>" data-parent=".panel-results" style="font-size: 18px;">
                    <div class="pull-right">
                        <span class="text-muted margin-right-10 count">
                            <?php echo (count($entities) - count($us));?>
                        </span>
                        <i class="fa fa-angle-down blue"></i>
                    </div>
                    <i class="fa fa-clock-o blue"></i>
                    Pending
                </h4>
            </div>
            <div class="panel-collapse collapse" id="p_<?php echo $id;?>">
                <ul class="panel-body" style="max-height: 300px;overflow-y: auto;"></ul>
            </div>
        </div>
        <div class="panel panel-default" style="border:0;border-radius: 0;box-shadow: none;">
            <div class="panel-heading cursor-pointer results-filter"
                 data-users="<?php echo htmlentities(json_encode($users['correct']));?>"
                 data-type="correct">
                <h4 class="panel-title blue" href="#c_<?php echo $id;?>" data-toggle="collapse" data-parent=".panel-results" style="font-size: 18px;">
                    <div class="pull-right">
                        <span class="text-muted margin-right-10 count">
                            <?php echo count($users['correct']);?>
                        </span>
                        <i class="fa fa-angle-down blue"></i>
                    </div>
                    <i class="fa fa-check blue"></i>
                    Correct
                </h4>
            </div>
            <div class="panel-collapse collapse" id="c_<?php echo $id;?>">
                <ul class="panel-body"></ul>
            </div>
        </div>
    </div>
    <?php
        break;
    case 'results':
        $user_id = get_input('user');
        $quiz = array_pop(ClipitQuiz::get_by_id(array($quiz_id)));
    ?>
        <ul style="background: rgb(236, 247, 252);padding: 10px;margin-left: 35px;">
            <?php
            $num = 1;
            $questions = ClipitQuizQuestion::get_by_id($quiz->quiz_question_array);
            foreach($questions as $question):
                $result = ClipitQuizResult::get_from_question_user($question->id, $user_id);
                $params = array(
                    'finished_task' => true,
                    'finished' => true,
                    'question' => $question,
                    'result' => $result,
                );
                ?>
                <li class="list-item answer">
                    <div class="pull-right">
                        <?php
                        echo elgg_view('output/url', array(
                            'title' => elgg_echo('quiz:question:annotate'),
                            'text' => '',
                            'href' => 'javascript:;',
                            'class' => 'fa fa-edit btn btn-xs btn-border-blue btn-icon',
                            'onclick' => '$(this).closest(\'.answer\').find(\'.annotate\').toggle()',
                        ));
                        ?>
                        <i class="fa fa-angle-down blue margin-left-10"></i>
                    </div>
                        <span class="pull-left margin-right-5">
                            <?php if(!$result):?>
                                <i class="fa fa-minus yellow"></i>
                            <?php elseif($result->correct):?>
                                <i class="fa fa-check green"></i>
                            <?php else: ?>
                                <i class="fa fa-times red"></i>
                            <?php endif;?>
                            <strong class="text-muted">
                                <?php echo $num;?>.
                            </strong>
                        </span>
                        <span class="cursor-pointer blue show content-block"
                              data-target="#question-result-<?php echo $result->id;?>"
                              data-toggle="collapse">
                            <?php echo $question->name;?>
                        </span>
                    <div class="clearfix"></div>
                    <div id="question-result-<?php echo $result->id;?>" class="question-result collapse" style="background: #fff;padding: 5px 10px;margin-top: 5px;">
                        <?php switch($question->option_type):
                            case ClipitQuizQuestion::TYPE_SELECT_MULTI:
                                echo elgg_view('quizzes/types/select_multi', $params);
                                break;
                            case ClipitQuizQuestion::TYPE_SELECT_ONE:
                                echo elgg_view('quizzes/types/select_one', $params);
                                break;
                            case ClipitQuizQuestion::TYPE_TRUE_FALSE:
                                echo elgg_view('quizzes/types/true_false', $params);
                                break;
                            case ClipitQuizQuestion::TYPE_NUMBER:
                                echo elgg_view('quizzes/types/number', $params);
                                break;
                        endswitch;
                        ?>
                    </div>

                    <?php
                    echo elgg_view_form('quiz/question_annotate', array(
                        'body' => elgg_view('quizzes/admin/annotation', array('result' => $result, 'question' => $question))
                    ));
                    ?>
                </li>
            <?php
                $num++;
            endforeach;
            ?>
        </ul>
<?php
}
//die;
//echo elgg_view('quizzes/list', array(
//    'quiz' => $quiz,
//    'task_id' => $task->id,
//    'user_id' => $user_id,
//    'finished_task' => ClipitQuiz::has_finished_quiz($quiz, $user_id),
//    'finished' => true,
//    'admin' => true
//))

?>
