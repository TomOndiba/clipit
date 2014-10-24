<?php
$quiz = get_entity(get_input('id_quiz'));
$id_quiz = get_input('id_quiz');

$title = $quiz->name;

$params = array(
    'content'   => elgg_view("results/index_quiz", array('entity' => $quiz, 'id' => $id_quiz)),
    'filter'    => '',
    'title'     => $title,
);
$body = elgg_view_layout('content', $params);
echo elgg_view_page($params['title'], $body);

?>
