<?php


/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   22/04/14
 * Last update:     22/04/14
 * @author          Miguel Ángel Gutiérrez <magutierrezmoreno@gmail.com>, URJC JuxtaLearn Project
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 */

function get_timestamp_from_string($string){
    return strtotime(str_replace('/', '-', $string));
}

/**
 * Get format file size
 *
 * @param int $size
 * @return string
 */
function formatFileSize( $bytes, $precision = 0) {
    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $e = floor(log($bytes)/log(1024));

    $output = sprintf("%.{$precision}f ".$s[$e], ($bytes/pow(1024, floor($e))));

    return $output;
}

/**
 * @param $id
 * @param $message_destination
 * @return mixed
 */
function get_text_from_quote($id, $message_destination){
    $post = array_pop(ClipitPost::get_by_destination(array($message_destination)));
    return $post[$id-1];
}

/**
 * @param $text_message
 * @return mixed
 */
function text_reference($text_message){
    if(preg_match('/(^|[^a-z0-9_])#([0-9_]+)/i', $text_message)){
        $prex = '/#([0-9_]+)/i';
        preg_match_all($prex, $text_message, $string_regex, PREG_PATTERN_ORDER);
        $string_regexs = $string_regex[1];
        foreach($string_regexs as $string){
            $text_message = preg_replace(
                "/\#$string\b/",
                '<strong class="quote-ref" data-quote-ref="'.$string.'">
                    <a class="btn">#'.$string.'</a>
                </strong>',
                $text_message);
        }


    }

    if(preg_match('/(^|[^a-z0-9_])@([a-z0-9_]+)/i',$text_message)){
        $prex = '/@([a-z0-9_]+)/i';
        preg_match_all($prex, $text_message, $string_regex, PREG_PATTERN_ORDER);
        $string_regexs = $string_regex[1];
        foreach($string_regexs as $string){
            /// OLD: "/(^|[^a-z0-9_])@".$string."/i"
            $user = array_pop(ClipitUser::get_by_login(array($string)));
            if(!empty($user)){
                $url_link = elgg_view('output/url', array(
                    'href'  => "profile/".$user->login,
                    'title' => "@".$user->login,
                    'text'  => $user->name,
                    'style' => 'border-radius:3px; background: #bae6f6;padding: 1px 5px;font-weight: bold;',
                ));
                $text_message = preg_replace("/\@".$user->login."\b/",'$1'.$url_link, $text_message);
            }
        }
    }
    return $text_message;
}

/**
 * URL video parser
 *
 * @param $url
 * @return array|bool
 */
function video_url_parser($url){
    if(!isset($url) || !filter_var($url, FILTER_VALIDATE_URL)){
        return false;
    }
    $video_patterns = array('#(((http://)?)|(^./))(((www.)?)|(^./))youtube\.com/watch[?]v=([^\[\]()<.,\s\n\t\r]+)#i'
    ,'#(((http://)?)|(^./))(((www.)?)|(^./))youtu\.be/([^\[\]()<.,\s\n\t\r]+)#i'
    ,'/(http:\/\/)(www\.)?(vimeo\.com\/groups)(.*)(\/videos\/)([0-9]*)/'
    ,'/(http:\/\/)(www\.)?(vimeo.com\/)([0-9]*)/');
    $parse_url = parse_url($url);
    $favicon_url_base = "http://www.google.com/s2/favicons?domain=";
    foreach($video_patterns as $video_pattern){
        if (preg_match($video_pattern, $url) > 0){
            // Youtube
            if (strpos($url, 'youtube.com') != false || strpos($url, 'youtu.be') != false) {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
                $output = array(
                    'id' => $matches[0],
                    'url'   => 'http://www.youtube.com/watch?v='.$matches[0],
                    'preview' => "http://i1.ytimg.com/vi/{$matches[0]}/mqdefault.jpg",
                    'favicon'   => $favicon_url_base.$parse_url['host']
                );
                // Vimeo
            } else if (strpos($url, 'vimeo.com') != false) {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=vimeo.com/)[^&\n]+#", $url, $matches);
                $data = file_get_contents("http://vimeo.com/api/v2/video/$matches[0].json");
                $data = array_pop(json_decode($data));
                $output = array(
                    'id' => $matches[0],
                    'url'   => "http://vimeo.com/{$matches[0]}",
                    'preview' => $data->thumbnail_large,
                    'favicon'   => $favicon_url_base.$parse_url['host']
                );
            }
        }
    }
    if(!$output['id']){
        return false;
    }
    // Video Data output
    return $output;
}
function get_video_url_embed($url){
    if (strpos($url, 'youtube.com') != false || strpos($url, 'youtu.be') != false) {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        $embed_url = "//youtube.com/embed/".$matches[0];
    } else if (strpos($url, 'vimeo.com') != false) {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=vimeo.com/)[^&\n]+#", $url, $matches);
        $embed_url = "//player.vimeo.com/video/".$matches[0];
    } else {
        $embed_url = elgg_get_site_url(). "mod/z04_clipit_activity/lib/ownvideo.php?v=".urlencode($url);
    }
    return $embed_url;
}
/**
 * Get formated time
 * @param int $seconds
 * @return string (hh:mm:ss|mm:ss)
 */
function get_format_time($seconds = 0){
    $time = gmdate("i:s", $seconds);
    if($seconds >=  3600){
        $time = gmdate("H:i:s", $seconds);
    }
    return $time;
}

/**
 * Star rating view
 *
 * @param float $average
 * @param int $number
 * @return string
 */
function star_rating_view($average = 0, $number = 5){
    $rest = number_format(($average - floor($average)), 2);
    $average_ceil = ceil($average);
    $qualifications = array(elgg_echo('qual:bad'), elgg_echo('qual:poor'), elgg_echo('qual:regular'), elgg_echo('qual:good'), elgg_echo('qual:gorgeous'));
    $output = "";
    for($i = 1; $i<=$number; $i++){
        $star_class = "fa-star empty";
        if($average_ceil >= $i){
            $star_class = "fa-star";
            if($rest > .25 && $average_ceil == $i){
                $star_class = "fa-star";
                if($rest < .76){
                    $star_class = "fa-star-half-o";
                } else if($rest < .6){
                    $star_class = "fa-star empty";
                }
            }
        }
        $output .= '<i class="fa '.$star_class.'" data-rating="'.$i.'"></i> ';
    }
    return $output;
}
function get_task_completed_count(ClipitTask $task){
    $activity = array_pop(ClipitActivity::get_by_id(array($task->activity)));
    switch($task->task_type){
        case ClipitTask::TYPE_VIDEO_UPLOAD:
            $text = count($task->video_array)."/".count($activity->group_array);
            $count  = (count($task->video_array)/count($activity->group_array)) * 100;
            break;
        case ClipitTask::TYPE_STORYBOARD_UPLOAD:
            $text = count($task->storyboard_array)."/".count($activity->group_array);
            $count  = (count($task->storyboard_array)/count($activity->group_array)) * 100;
            break;
        case ClipitTask::TYPE_VIDEO_FEEDBACK:
        case ClipitTask::TYPE_STORYBOARD_FEEDBACK:
            $completed = 0;
            foreach($activity->student_array as $user_id){
//                if(ClipitTask::get_completed_status($task->id, $user_id)
//                    && (count(ClipitTask::get_storyboards($task->parent_task)) > 0
//                        || count(ClipitTask::get_videos($task->parent_task)) > 0)){
                if(ClipitTask::get_completed_status($task->id, $user_id)){
                    $completed++;
                }
            }
            $text = $completed."/".count($activity->student_array);
            $count  = ($completed/count($activity->student_array)) * 100;
            break;
        case ClipitTask::TYPE_QUIZ_TAKE:
            $completed = 0;
            foreach($activity->student_array as $user_id){
                if(ClipitTask::get_completed_status($task->id, $user_id)){
                    $completed++;
                }
            }
            $text = $completed."/".count($activity->student_array);
            $count  = ($completed/count($activity->student_array)) * 100;
            break;
    }
    return array(
        'text' => $text,
        'count' => round($count)
    );
}
/**
 * Get task status
 *
 * @param ClipitTask $task
 * @param $activity_id
 * @return array
 */
function get_task_status(ClipitTask $task, $group_id = 0, $user_id = null){
    if(!$user_id){
        $user_id = elgg_get_logged_in_user_guid();
    }
    if(!$group_id){
        $group_id = ClipitGroup::get_from_user_activity($user_id, $task->activity);
    }
    $status = array(
        'icon' => '<i class="fa fa-minus yellow"></i>',
        'text' => elgg_echo('task:pending'),
        'color' => 'yellow',
        'status' => false
    );
    if(time() < $task->start && $task->task_type != ClipitTask::TYPE_OTHER){
        return $status;
    }
    switch($task->task_type){
        case ClipitTask::TYPE_OTHER:
            $status = array(
                'status' => false
            );
            return $status;
            break;
        case ClipitTask::TYPE_VIDEO_UPLOAD:
            foreach($task->video_array as $video_id){
                $group_video = ClipitVideo::get_group($video_id);
                if($group_id == $group_video){
                    $status = array(
                        'icon' => '<i class="fa fa-check green"></i>',
                        'text' => elgg_echo('task:completed'),
                        'color' => 'green',
                        'status' => ClipitTask::get_completed_status($task->id, $group_id),
                        'result' => $video_id
                    );
                }
            }
            break;
        case ClipitTask::TYPE_STORYBOARD_UPLOAD:
            foreach($task->storyboard_array as $storyboard_id){
                $group_sb = ClipitStoryboard::get_group($storyboard_id);
                if($group_id == $group_sb){
                    $status = array(
                        'icon' => '<i class="fa fa-check green"></i>',
                        'text' => elgg_echo('task:completed'),
                        'color' => 'green',
                        'status' => ClipitTask::get_completed_status($task->id, $group_id),
                        'result' => $storyboard_id
                    );
                }
            }
            break;
        case ClipitTask::TYPE_STORYBOARD_FEEDBACK:

            $entities = ClipitTask::get_storyboards($task->parent_task);
            $evaluation_list = get_filter_evaluations($entities, $task->activity, $user_id);
            $total = count($evaluation_list["evaluated"]) + count($evaluation_list["no_evaluated"]);
            $total_evaluated = count($evaluation_list["evaluated"]);
            $text = "";
            if($total > 0){
                $text = $total_evaluated."/".$total;
            }
            if(ClipitTask::get_completed_status($task->id, $user_id)){
                $custom = array(
                    'icon' => '<i class="fa fa-check green"></i>',
                    'text' => $text." ".elgg_echo('task:completed'),
                    'color' => 'green',
                );
            } else {
                $custom = array(
                    'icon' => '<i class="fa fa-minus yellow"></i>',
                    'text' => $text." ".elgg_echo('task:pending'),
                    'color' => 'yellow',
                );
            }
            $status = array_merge(
                array(
                    'count' => $text,
                    'status' => ClipitTask::get_completed_status($task->id, $user_id)
                ), $custom);
            break;
        case ClipitTask::TYPE_VIDEO_FEEDBACK:
            $entities = ClipitTask::get_videos($task->parent_task);
            $evaluation_list = get_filter_evaluations($entities, $task->activity, $user_id);
            $total = count($evaluation_list["evaluated"]) + count($evaluation_list["no_evaluated"]);
            $total_evaluated = count($evaluation_list["evaluated"]);
            $text = "";
            if($total > 0){
                $text = $total_evaluated."/".$total;
            }
            if(ClipitTask::get_completed_status($task->id, $user_id)){
                $custom = array(
                    'icon' => '<i class="fa fa-check green"></i>',
                    'text' => $text." ".elgg_echo('task:completed'),
                    'color' => 'green',
                );
            } else {
                $custom = array(
                    'icon' => '<i class="fa fa-minus yellow"></i>',
                    'text' => $text." ".elgg_echo('task:pending'),
                    'color' => 'yellow',
                );
            }
            $status = array_merge(
                array(
                    'count' => $text,
                    'status' => ClipitTask::get_completed_status($task->id, $user_id)
                ), $custom);

            break;
    }

    if($task->end <= time() && $status['status'] === false){
        $status = array(
            'icon' => '<i class="fa fa-times red"></i>',
            'text' => $text." ".elgg_echo('task:not_completed'),
            'count' => $text,
            'color' => 'red',
            'status' => false
        );
    }
    $role = array_pop(ClipitUser::get_properties($user_id, array('role')));
    if($role == 'teacher'){
//        $status['count'] = false;
        $status['text'] = false;
    }
    return $status;
}

/**
 * Get group progress from activity
 *
 * @param $group_id
 * @return float
 */
function get_group_progress($group_id){
    $activity_id = ClipitGroup::get_activity($group_id);
    $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
    $individual_tasks = array(ClipitTask::TYPE_VIDEO_FEEDBACK, ClipitTask::TYPE_STORYBOARD_FEEDBACK, ClipitTask::TYPE_QUIZ_TAKE);
    $completed = array();
    $total = 0;
    $group_users = ClipitGroup::get_users($group_id);
    foreach($activity->task_array as $task_id){
        $task = array_pop(ClipitTask::get_by_id(array($task_id)));
        if(time() > $task->start) {
            if (in_array($task->task_type, $individual_tasks)) {
                foreach ($group_users as $user_id) {
                    if (ClipitTask::get_completed_status($task_id, $user_id)) {
                        $completed[] = true;
                    }
                    $total++;
                }
            } else {
                $total++;
                if (ClipitTask::get_completed_status($task_id, $group_id)) {
                    $completed[] = true;
                }
            }
        }
    }
    $val = count($completed)/($total);
    return round($val*100);
}

/**
 * Get current activity status
 *
 * @param $status ClipitActivity->status
 * @return array
 */
function get_activity_status($status){
    switch($status){
        case ClipitActivity::STATUS_ENROLL:
            $output = array(
                'icon' => 'clock-o',
                'color' => 'yellow',
                'text' => elgg_echo('status:enroll'),
                'change_to' => elgg_echo('status:active'),
                'btn_change_to' => '<span class="change-status btn btn-border-green btn-xs" data-status="active"><strong><i class="fa fa-play green"></i> '.elgg_echo('status:active').'</strong></span>'
            );
            break;
        case ClipitActivity::STATUS_ACTIVE:
            $output = array(
                'icon' => 'play',
                'color' => 'green',
                'text' => elgg_echo('status:active'),
                'change_to' => elgg_echo('status:closed'),
                'btn_change_to' => '<span class="change-status btn btn-border-red btn-xs" data-status="closed"><strong><i class="fa fa-stop red"></i> '.elgg_echo('status:closed').'</strong></span>'
            );
            break;
        case ClipitActivity::STATUS_CLOSED:
            $output = array(
                'icon' => 'stop',
                'color' => 'red',
                'text' => elgg_echo('status:closed'),
                'change_to' => elgg_echo('status:active'),
                'btn_change_to' => '<span class="change-status btn btn-border-green btn-xs" data-status="active"><strong><i class="fa fa-play green"></i> '.elgg_echo('status:active').'</strong></span>'
            );
            break;
    }
    return $output;
}

/**
 * array_chunk() php function, balanced
 *
 * @param $l
 * @param $n
 * @return array
 */
function split_chunks($l, $n){

    $result = array_fill(0, $n, array());
    $sums   = array_fill(0, $n, 0);
    $c = 0;
    foreach ($l as $e){
        foreach ($sums as $i=>$sum){
            if ($c == $sum){
                $result[$i][] = $e;
                break;
            } // if
        } // foreach
        $sums[$i] += $e;
        $c = min($sums);
    } // foreach
    return $result;
}