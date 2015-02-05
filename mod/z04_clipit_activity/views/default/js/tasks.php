<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   23/07/14
 * Last update:     23/07/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
?>
$(document).on("click", ".feedback-check",function(){
    var parent = $(this).closest(".task");
    var feedback_content = parent.find(".feedback_form");
    if($(this).find("input").is(':checked')){
        var task_end = parent.find(".input-task-end").val();
        if(task_end.length > 0){
            feedback_content.find(".input-task-start").val(task_end);
        }
        feedback_content
        .show()
        .find(".input-task-title")
        .focus();
    } else {
        feedback_content.hide();
    }
});

$(document).on("change", ".task-types",function(){
    var task_types = ['video_upload', 'storyboard_upload'];
    var content = $(this).closest(".task");
    var that = $(this);
    content_feedback = content.find(".feedback-module");
    var tricky_topic_val = '',
        input_prefix_val = '';
    if($("#tricky-topic").val()){
        tricky_topic_val = $("#tricky-topic").val();
    }
    var input_prefix = content.find("input[name='input_prefix']");
    if(input_prefix.length > 0){
        input_prefix_val = input_prefix.val();
    }
    $(this).closest(".task").find(".task-type-container").html('').hide();
    if($.inArray($(this).val(), task_types) != -1){
        switch($(this).val()){
            case "video_upload":
                content.find('.feedback_form .task-types').val('video_feedback');
                break;
            case "storyboard_upload":
                content.find('.feedback_form .task-types').val('storyboard_feedback');
                break;
        }
        content_feedback.show();
    } else {
        content_feedback.hide();
        content.find('.feedback_form').hide();
        content_feedback.find("input[type=checkbox]").prop('checked', false);
    }
    content.find(".quiz-module").hide();
    if($(this).val() == 'quiz_take'){
        content.find(".quiz-module").show();
<!--        elgg.get('ajax/view/activity/admin/tasks/quiz/quiz', {-->
        elgg.get('ajax/view/quiz/list', {
            data: {
                'activity_create': true,
                'tricky_topic': tricky_topic_val,
                'input_prefix': input_prefix_val
            },
            success: function (data) {
                that.closest(".task").find(".task-type-container").html(data).show();
            }
        });
    }

});

$(document).on("click", ".quiz-select",function(){
    var task_container = $(this).closest('.task-type-container'),
        quiz_id = $(this).closest('tr').attr('id');
    task_container.find('table tr').not('#'+ quiz_id).fadeOut(300, function(){$(this).remove();});
<!--    task_container.find('.input-quiz').val(quiz_id);-->
    // Change button to unselect type
    $(this).removeClass('quiz-select')
            .addClass('quiz-unselect btn-border-red')
            .text('<?php echo elgg_echo('btn:remove');?>');
});
$(document).on("click", ".quiz-unselect",function(){
    var task_container = $(this).closest('.task-type-container');
    task_container.closest('.task').find('.task-types').trigger('change');
});
$(document).on("click", "#add_task",function(){
    var content = $(".task-list");
    $.get( "ajax/view/activity/create/task_list", function( data ) {
        content.append(data);
    });
});