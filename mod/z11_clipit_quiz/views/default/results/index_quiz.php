<?php

use ClipitTrickyTopic;

// Obtengo el quiz y su ID
$quiz = elgg_extract('entity', $vars);
$id = elgg_extract('id', $vars);

// Establezco la URL para realizar el quiz según su modo de visualización
$do_quiz_url = elgg_get_site_url()."results/do_quiz?id_quiz={$id}&option=list";
if ($quiz->view_mode == ClipitQuiz::VIEW_MODE_PAGED){
    $do_quiz_url = elgg_get_site_url()."results/do_quiz?id_quiz={$id}&option=paged";
}

// Link a los resultados del quiz
$results_url = elgg_get_site_url()."results/results?id_quiz={$id}";

//Obtengo el TrickyTopic del quiz
$id_tt = $quiz->tricky_topic;
$tt = ClipitTrickyTopic::get_by_id(array($id_tt));
?>

<div class="quiz">
    
        <div class="content-block">

            <div>
                <p><strong>Tricky topic: </strong>
                    <?php 
                        echo elgg_view('output/text', array('value' => $tt[$id_tt]->name));?>
                </p>
                <p><?php echo $quiz->description;?></p>
            </div>
            <br>        

            <small>
                <i>Creado por <?php echo elgg_view('output/text', array('value' => $quiz->author_name)) . " "
                                 . elgg_view('output/friendlytime', array('time' => $quiz->time_created));?>
                </i>
            </small>
            <br><br>
            <p>
                <?php
            echo "<a href='$do_quiz_url' class='elgg-button'>
                Do quiz
            </a>";
                    ?>
            </p>

            <p>
                <?php
            echo "<a href='$results_url' class='elgg-button'>
                View results
            </a>";
                    ?>
            </p>
            
            <br>
        </div>
</div>