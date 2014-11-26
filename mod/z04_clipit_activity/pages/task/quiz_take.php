<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   10/10/2014
 * Last update:     10/10/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$href = "clipit_activity/{$activity->id}/quizzes";
$quiz = $task->quiz;

$finished_task = $task->end <= time() ? true : false;
$finished = false;
if(ClipitQuiz::has_finished_quiz($quiz, $user_id) || $finished_task){
    $finished = true;
}

$body = elgg_view_form('quiz/take',
    array('body' =>
        elgg_view('quizzes/list', array(
            'quiz' => $quiz,
            'href' => $href,
            'task_id' => $task->id,
            'user_id' => elgg_get_logged_in_user_guid(),
            'finished_task' => $finished_task,
            'finished' => $finished
        ))
    ));

// Teacher view
if($user->role == ClipitUser::ROLE_TEACHER){
    $users = ClipitActivity::get_students($activity->id);
    if($users){
        $body = elgg_view('tasks/admin/quiz_take', array(
            'activity' => $activity,
            'entities'    => $users,
            'quiz' => array_pop(ClipitQuiz::get_by_id(array($quiz))),
            'task' => $task,
        ));
    } else {
        $body = elgg_view('output/empty', array('value' => elgg_echo('users:none')));
    }
}