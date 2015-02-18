<?php
 /**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   18/02/2015
 * Last update:     18/02/2015
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */
$entity_id = get_input('entity-id');
$file = $_FILES['file'];
$new_file_id = array();
for($i = 0;$i < count($file['name']);$i++){
    if($file['name'][$i]){
        $new_file_id[] = ClipitFile::create(array(
            'name' => $file['name'][$i],
            'temp_path'  => $file['tmp_name'][$i]
        ));
    }
}
ClipitTrickyTopic::add_files($entity_id, $new_file_id);
$storyboard = $_FILES['storyboard'];
$new_sb_id = array();
for($i = 0;$i < count($storyboard['name']);$i++){
    if($storyboard['name'][$i]){
        $file_id = ClipitFile::create(array(
            'name' => $storyboard['name'][$i],
            'temp_path'  => $storyboard['tmp_name'][$i]
        ));
        $new_sb_id[] = ClipitStoryboard::create(array(
            'name' => $storyboard['name'][$i],
            'file'  => $file_id
        ));
    }
}
ClipitTrickyTopic::add_storyboards($entity_id, $new_sb_id);

$video_title = get_input('video_title');
$video_file = $_FILES['video'];
$video_link = get_input('video_url');
$new_video_id = array();
for($i = 0;$i < count($video_title);$i++){
    if(trim($video_title[$i]) != '' ){
        // Video url (youtube|vimeo)
        if(trim($video_link[$i]) != "" && $video_data = video_url_parser($video_link[$i])){
            $video_url = $video_data['url'];
            // Video upload to youtube
        } elseif(!empty($video_file['tmp_name'][$i])){
            $video_url = ClipitVideo::upload_to_youtube($video_file['tmp_name'][$i], $video_title[$i]);
            $video_data = video_url_parser($video_url);
        }
        if(!$video_data){
            register_error(elgg_echo("video:cantadd"));
        } else {
            $new_video_id[] = ClipitVideo::create(array(
                'name' => $video_title[$i],
                'url' => $video_url,
                'preview' => $video_data['preview'],
                'duration' => $video_data['duration']
            ));
        }
    }
}
ClipitTrickyTopic::add_videos($entity_id, $new_video_id);