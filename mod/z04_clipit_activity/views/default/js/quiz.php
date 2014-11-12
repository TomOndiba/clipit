<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   11/11/2014
 * Last update:     11/11/2014
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
?>
$.fn.quiz = function (options) {
    var defaults = {};
    var opt =  $.extend({}, defaults, options),
        that = $(this),
        $quiz = $(this),
        $question = that.find('.question');
    $questions = that.find('.questions');

    function Question(object){
        var self = this;
        this.question;
        this.question_type_selected;
        this._init = function(){
            self.getNum();
            // Trigger events
            self.question.find(".remove-question").on("click", function(){
                return self.delete();
            });
            self.question.find(".add-result").on("click", function(){
                self.question_type_selected = $(this).closest(".show-question");
                return self.addResult();
            });
            // Tags chosen
            self.question.find(".tags-select").chosen({disable_search_threshold: 1});
            // jQuery UI slider
            self.difficultySlider( self.question.find(".difficulty-slider") );
            // Question types select
            self.question.find(".select-question-type").on("change", function(){
                var question_type = $("[data-question='"+$(this).val()+"']");
                self.question.find(".show-question").hide();
                if(self.question.find(question_type).length > 0) {
                    self.question.find(question_type).show();
                }
            });
        };
        this.difficultySlider = function($elem){
            return $elem.slider({
                range: "min",
                value: $elem.find("input").val(),
                min: 1,
                max: 10,
                step: 1,
                create: function(event, ui){
                    $elem.find("a").append($("<span/>"));
                    var value = $elem.find("input").val();
                    if(value < 5){
                        $elem.find(".ui-slider-range").addClass("green");
                    }else if(value >= 5 && ui.value < 8){
                        $elem.find(".ui-slider-range").addClass("yellow");
                    }else if(value >= 8){
                        $elem.find(".ui-slider-range").addClass("red");
                    }
                },
                slide: function( event, ui ) {
                    $elem.find("a span" ).text( ui.value );
                    $elem.find("input" ).val( ui.value );
                    $elem.find(".ui-slider-range").removeClass().addClass("ui-slider-range");
                    if(ui.value < 5){
                        $elem.find(".ui-slider-range").addClass("green");
                    }else if(ui.value >= 5 && ui.value < 8){
                        $elem.find(".ui-slider-range").addClass("yellow");
                    }else if(ui.value >= 8){
                        $elem.find(".ui-slider-range").addClass("red");
                    }
                }
            });
            $elem.find(" a span" ).text(  $(this).find("input").val() );
        };
        this.create = function(from_tag, value){
            if(!value && !from_tag){
                var from_tag = false,
                    value = null;
            }
            elgg.get('ajax/view/activity/admin/tasks/quiz/add_type',{
                data: {
                    type: "question",
                    tricky_topic: opt.tricky_topic,
                    question: value,
                    num: $question.length + 1
                },
                success: function(content){
                    var $content = $($.parseHTML(content));
                    if($quiz.find(".question").length > 0 ){
                        $quiz.find(".questions").append($content);
                    } else {
                        $quiz.find(".questions").html($content);
                    }

                    self.question = $content;
                    if(from_tag){
                        var question_type = self.question.find(".select-question-type").val();
                        self.question.find("[data-question='" + question_type + "']").show();
                        self.question.find("textarea").click();
                        $quiz.find('.questions-select')
                            .val('')
                            .trigger('chosen:updated');
                    }
                    return self._init();
                }
            });
        };
        this.delete = function(){
            self.question.remove();
            return self.getNum();
        };
        this.getNum = function(){
            return $quiz.find(".question").each(function(i){
                $(this).find(".question-num").text((i+1) + ".");
            });
        };
        this.addResult = function(){
            return elgg.get('ajax/view/activity/admin/tasks/quiz/add_type',{
                data: {
                    type:   self.question_type_selected.data("question"),
                    id:     self.question_type_selected.attr("id"),
                    num:    self.question_type_selected.find(".result").length + 1
                },
                success: function(content){
                    self.question_type_selected
                        .find(".results")
                        .append(content)
                        .find("input")
                        .focus();
                }
            });
        };
    };
    // Create a Question button
    that.find(".create-question").bind("click",function() {
        var q = new Question($(this));
        return q.create();
    });
    // Question select from tag
    that.find(".questions-select").chosen().change(function(){
        var q = new Question($(this));
        q.create(true, $(this).val());
    });
    return $quiz.find(".question").each(function(){
        var q = new Question();
        q.question = $(this);
        q._init();
    });
};
