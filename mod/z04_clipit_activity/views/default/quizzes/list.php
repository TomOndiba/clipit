<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   16/06/14
 * Last update:     16/06/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
elgg_load_js('fullcalendar:moment');
$quiz_id = elgg_extract("quiz", $vars);
$task_id = elgg_extract("task_id", $vars);
$user_id = elgg_extract("user_id" ,$vars);
$finished = elgg_extract("finished" ,$vars);
$finished_task = elgg_extract("finished_task" ,$vars);

$task = array_pop(ClipitTask::get_by_id(array($task_id)));
$quiz = array_pop(ClipitQuiz::get_by_id(array($quiz_id)));
$questions = ClipitQuizQuestion::get_by_id($quiz->quiz_question_array, 0, 0, 'order');
// if teacher set random questions
// shuffle($questions);
$quiz_start = ClipitQuiz::get_quiz_start($quiz->id, $user_id);
if(!$quiz_start && !$finished_task){
    ClipitQuiz::set_quiz_start($quiz->id, elgg_get_logged_in_user_guid());
    $quiz_start = ClipitQuiz::get_quiz_start($quiz->id, elgg_get_logged_in_user_guid());
}
$date = date("H:s, d/m/Y", $quiz_start + $quiz->max_time);
$count_answer = ClipitQuiz::questions_answered_by_user($quiz_id, $user_id);
?>
<style>
    .quiz-answer .fa-times, .quiz-answer .fa-check{
        width: 14px;
    }
    .quiz-admin .question-answer{
        display: none;
    }
    .quiz-admin .question{
        margin: 0 !important;
        padding-bottom: 0 !important;
        padding: 1px 10px;
    }
    .quiz-admin .question:nth-of-type(even){
        background: #f9f9f9;
        color: #000;
    }
    .quiz-admin .question h4{
        font-size: 16px;
    }
</style>

<script>
<?php if(!$finished && $quiz->max_time > 0):?>
    var eventTime= <?php echo $quiz_start + $quiz->max_time?>, // Timestamp - Sun, 21 Apr 2013 13:00:00 GMT
        currentTime = <?php echo time()?>, // Time()
        diffTime = eventTime - currentTime,
        duration = moment.duration(diffTime*1000, 'milliseconds'),
        interval = 1000,
        days = '',
        hours = '',
        d;
    countdown = setInterval(function(){
        duration = moment.duration(duration - interval, 'milliseconds');
        if(duration - interval <= 0){
            $(".quiz .question :input").prop("disabled", true);
            $('.countdown').text('<?php echo elgg_echo('closed');?>');
            clearTimeout(countdown);
            return false;
        }
        d = new Date(duration-interval);
        if(duration.days()){
            days = moment(d).format('DD') + "d ";
        } else if(duration.hours()){
            hours = moment.utc(d).format('HH') + "h ";
        }
        $('.countdown').text(days + hours + moment.utc(d).format('mm') + "m " + moment.utc(d).format('ss') + "s");
    }, interval);
<?php endif;?>
    $(function(){
        $(document).on("click", ".pagination li.page:not('.disabled')", function(){
            $(".question").hide();
            $(".pagination li").removeClass("active");
            $(this).addClass("active");
            var $question = $("[data-question="+$(this).attr("id")+"]");
            $question.show();
            $(".next-page").attr("id", parseInt($(this).attr("id")) + 1);
            $(".prev-page").attr("id", $(this).attr("id") - 1);

            if($(".page").length == $(this).attr("id")){
                $(".pagination .next-page").addClass("disabled");
            }else if($(this).attr("id") == 1){
                $(".pagination .prev-page").addClass("disabled");
            } else{
                $(".pagination .next-page, .pagination .prev-page").removeClass("disabled");
            }
        });
        $(document).on("click", ".pagination .prev-page, .pagination .next-page", function(){
            $(".pagination li#"+$(this).attr("id")+".page").click();
        });
    <?php if(!$finished && $quiz->max_time > 0):?>
        var typingTimer;                //timer identifier
        var doneTypingInterval = 500;  //time in ms, 5 second for example
        $(document).on("paste keyup change", ".quiz input[type=text], .quiz textarea", function(event){
            if(event.type == 'change' && $(this).is("textarea")){
                return false;
            }
            clearTimeout(typingTimer);
            var $obj = $(this);
            typingTimer = setTimeout(function(){ $obj.saveQuestion()}, doneTypingInterval);
        });
        $(document).on("keydown", ".quiz input, .quiz textarea", function(){
            clearTimeout(typingTimer);
        });
        $(document).on("click", ".quiz input[type=checkbox], .quiz input[type=radio]", function(){
            return $(this).saveQuestion();
        });
        $.fn.saveQuestion = function() {
            var $element = $(this);
            if($(this).attr("type") == 'checkbox'){
                var $element = $(".quiz input[type=checkbox]");
            }
            var form = $(this).closest("form").find($element.add("input:hidden"));
            var $container = $(this).closest(".question");
            $container.find(".loading-question").show()
            $container.find(".num-question").hide();
            elgg.action('quiz/take',{
                data: form.serialize(),
                success: function(json) {
                    $container.find(".loading-question").hide()
                    $container.find(".num-question").show();
                    $("#count-result").text(json.output);
                    // if finished
                    if (json.output == 'finished'){
                        window.location.href = '';
                    }
                }
            });
        };

        $("#finish-quiz").click(function(e){
            e.preventDefault();
            var that = $(this);
            var confirmOptions = {
                title: $("#questions-result").text(),
                buttons: {
                    ok: {
                        label: elgg.echo("input:ok")
                    }
                },
                message: elgg.echo("quiz:result:send"),
                callback: function(result) {
                    that.closest('form').submit();
                }
            };
            bootbox.alert(confirmOptions);
        });
    <?php endif;?>
    });
</script>

<?php if($quiz->view_mode == ClipitQuiz::VIEW_MODE_PAGED):?>
<script>
$(function(){
    $(".question").hide().first().show();
});
</script>
<?php endif;?>
<?php if(!$finished && $quiz->max_time > 0):?>
<div class="bg-info pull-right">
    <i class="fa fa-clock-o pull-left" style="font-size: 56px;"></i>
    <div class="content-block">
        <h4 class="margin-0"><?php echo elgg_echo('quiz:time:to_do');?></h4>
        <h3 class="text-muted margin-0 margin-top-10">
            <span class="countdown"><i class="fa fa-spinner fa-spin blue"></i></span>
        </h3>
        <small><?php echo elgg_echo('quiz:time:finish');?> <?php echo $date;?></small>
    </div>
</div>
<?php endif;?>

<?php if(!$vars['admin']):?>
    <h4><?php echo $quiz->name;?></h4>
    <?php echo $quiz->description;?>
<?php endif;?>

<div class="clearfix"></div>
<h4 id="questions-result">
    <?php if($finished_task && $finished):?>
        <span>
        <?php
            $correct = count(array_filter(ClipitQuiz::get_user_results_by_question($quiz_id, $user_id)));
            $total_correct = ($correct/count($questions))*100;
            echo round($total_correct)."%";
        ?>
            <?php echo elgg_echo('quiz:questions:answers:correct');?>
        </span>
        <span class="text-muted margin-left-5">
            (<strong><span id="count-result"><?php echo $count_answer;?></span>
                <?php echo elgg_echo('quiz:out_of');?>
                <?php echo count($questions);?>
            </strong>
            <?php echo elgg_echo('quiz:questions:answered');?>)
        </span>
    <?php else:?>
        <strong class="text-muted">
            <span id="count-result"><?php echo $count_answer;?></span>
            <?php echo elgg_echo('quiz:out_of');?>
            <?php echo count($questions);?>
        </strong>
        <?php echo elgg_echo('quiz:questions:answered');?>
    <?php endif;?>
</h4>
<?php if(!$vars['admin']):?>
    <div class="pull-right"><small style="text-transform: uppercase"><?php echo elgg_echo('difficulty');?></small></div>
<?php endif;?>
<hr>

<div class="quiz <?php echo $vars['admin']?'quiz-admin':'';?>">
<?php
$num = 1;

foreach($questions as $question):
    $result = ClipitQuizResult::get_from_question_user($question->id, $user_id);
    $params = array(
        'finished_task' => $finished_task,
        'finished' => $finished,
        'question' => $question,
        'result' => $result,
    );
?>
    <div class="question form-group border-bottom-blue-lighter" data-question="<?php echo $num;?>">
        <?php if(!$vars['admin']):?>
        <div class="text-center pull-right">
            <?php echo difficulty_bar($question->difficulty);?>
        </div>
        <?php endif;?>
        <h4 class="question-title">
            <?php if($finished_task):?>
                <?php if(!$result):?>
                    <i class="fa fa-minus yellow"></i>
                <?php elseif($result->correct):?>
                    <i class="fa fa-check green"></i>
                <?php else: ?>
                    <i class="fa fa-times red"></i>
                <?php endif;?>
            <?php endif;?>
            <strong class="text-muted inline-block">
                <span class="num-question"><?php echo $num;?>.</span>
                <i class="fa fa-spinner fa-spin blue loading-question" style="display: none;"></i>
            </strong>
            <?php echo $question->name;?>
        </h4>
        <div class="margin-left-20 quiz-answer ">
            <?php if($description = $question->description):?>
                <div class="text-muted margin-bottom-10" style="margin-top: -10px;">
                    <?php echo $description;?>
                </div>
            <?php endif;?>
            <div class="question-answer">
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
            </div> <!-- .question-answer -->
            <?php if($result->description):?>
            <div class="clearfix"></div>
            <hr class="margin-0 margin-top-10 margin-bottom-10">
            <i class="fa fa-user blue"></i> <small><?php echo elgg_echo('quiz:teacher_annotation');?>:</small>
            <div class="bg-blue-lighter_4" style="padding: 10px;">
                <?php echo $result->description;?>
            </div>
            <?php endif; ?>
        </div>
    </div>
<!--    <div class="clearfix"></div>-->
<?php
$num++;
endforeach;
    echo elgg_view('input/hidden', array(
        'name' => 'entity-id',
        'id' => 'entity-id',
        'value' => $quiz->id
    ));
    echo elgg_view('input/hidden', array(
        'name' => 'task-id',
        'id' => 'task-id',
        'value' => $task_id
    ));
?>
    <?php if($quiz->view_mode == ClipitQuiz::VIEW_MODE_PAGED && count($questions)>1):?>
        <div class="margin-top-20">
            <div class="text-center">
                <?php if(!$finished && !$finished_task):?>
                    <?php echo elgg_view('input/submit',
                        array(
                            'value' => elgg_echo('finish'),
                            'id' => 'finish-quiz',
                            'class' => "btn btn-primary pull-right"
                        ));
                    ?>
                <?php endif;?>
                <ul class="pagination margin-0">
                    <li class="prev-page disabled" id="1">
                        <a href="javascript:;"><i class="fa fa-angle-double-left"></i> <?php echo elgg_echo('prev');?></a>
                    </li>
                    <?php for($i=1; $i <= count($questions); $i++):?>
                        <li id="<?php echo $i;?>" class="<?php echo $i==1? 'active':'';?> page">
                            <a href="javascript:;"><?php echo $i;?></a>
                        </li>
                    <?php endfor;?>
                    <li class="next-page" id="2">
                        <a href="javascript:;"><?php echo elgg_echo('next');?> <i class="fa fa-angle-double-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif;?>
    <?php if((!$finished && !$finished_task) && $quiz->view_mode == ClipitQuiz::VIEW_MODE_LIST):?>
    <div class="margin-top-20">
        <?php echo elgg_view('input/submit',
            array(
                'value' => elgg_echo('finish'),
                'id' => 'finish-quiz',
                'class' => "btn btn-primary pull-right"
            ));
        ?>
    </div>
    <?php endif;?>
    <?php
    if($finished_task && $finished):
        // Show radar chart
        $data = ClipitQuiz::get_user_results_by_tag($quiz_id, $user_id);
        $labels = array();
        foreach($data as $tag_id => $value){
            $tag = ClipitSite::lookup($tag_id);
            $labels[] = $tag['name'];
        }
    ?>
        <div>
            <h4><?php echo elgg_echo('quiz:results:stumbling_block');?></h4>
            <?php echo elgg_view('quizzes/chart/radar', array('data' => $data, 'labels' => $labels, 'id' => $user_id));?>
        </div>
    <?php endif;?>
</div>
