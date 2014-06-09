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
$english = array(
    'view'  => 'View',
    // Activity
    'my_activities' => 'My activities',
    'activities:none' => 'There are no activities...',
    'explore' => 'Explore',
    'past' => 'Finished',
    'activity:profile' => 'Activity home',
    'activity:groups' => 'Groups',
    'activity:discussion' => 'Activity discussion',
    'activity:stas' => 'Materials',
    'activity:publications' => 'Publications',
    'activity:join' => 'Join activity',
    'activity:actual_deadline' => 'Actual deadline',
    'activity:next_deadline' => 'Next deadline',
    'activity:quiz' => 'Activity Quiz',
    'activity:teachers' => 'Teachers',
    // Activity status
    'status:enroll' => 'Enrolling',
    'status:active' => 'Active',
    'status:closed' => 'Finished',

    'group:join' => 'Join',
    'group:leave' => 'Leave',
    'group:leave:me' => 'Leave group',
    'group:join_to' => 'Join to group',
    'group:cantcreate' => 'You can not create a group.',
    'group:created' => 'Group created',
    'group:joined' => 'Successfully joined group!',
    'group:cantjoin' => 'Can not join group',
    'group:left' => 'Successfully left group',
    'group:cantleave' => 'Could not leave group',
    'group:member:remove' => 'Remove from group',
    'group:member:cantremove' => 'Cannot remove user from group',
    'group:member:removed' => 'Successfully removed %s from group',
    // Quizz
    'quiz' => 'Quiz',

    // Group tools
    'group:menu' => 'Group menu',
    'group:tools' => 'Group tools',
    'group:discussion' => 'Discussions',
    'group:files' => 'Multimedia',
    'group:home' => 'Group home',
    'group:activity_log' => 'Activity log',
    'group:progress' => 'Progress',
    'group:edit' => 'Group edit',
    'group:members' => 'Members',
    // Discussion
    'discussions:none' => 'No discussions',
    'discussion:created' => 'Discussion created',
    'discussion:cantcreate' => 'You can not create a discussion',
    'discussion:edit' => 'Edit topic',
    'discussion:title_topic' => 'Topic title',
    'discussion:text_topic' => 'Topic text',
    // Multimedia
    'url'   => 'Url',
    'multimedia:files' => 'Files',
    'multimedia:videos' => 'Videos',
    'multimedia:storyboards' => 'Storyboards',
    'multimedia:links' => 'Interesting links',
    // Files
    'files' => 'Files',
    'file' => 'File',
    'multimedia:file:description' => 'File description',
    'multimedia:files:add' => 'Add files',
    'file:nofile' => 'No file',
    'file:removed' => 'File %s removed',
    'file:cantremove' => 'Can not remove file',
    'file:edit' => 'Edit file',
    'file:none' => "No files",
    /* File types */
    'file:general' => 'File',
    'file:document' => 'Document',
    'file:image' => 'Image',
    'file:video' => 'Video',
    'file:audio' => 'Audio',
    'file:compressed' => 'Compressed file',
    // Videos
    'videos' => 'Videos',
    'video' => 'Video',
    'videos:none' => 'No videos',
    'video:url:error' => 'Incorrect url or video not found',
    'video:edit' => 'Edit video',
    'video:add' => 'Add video url',
    'video:url' => 'Video url',
    'video:title' => 'Video title',
    'video:tags' => 'Video tags',
    'video:description' => 'Video description',
    // Storyboards
    'storyboards:none' => 'No storyboards',
    'multimedia:storyboards:add' => 'Add storyboards',
    // Tricky Topic
    'tricky_topic' => 'Tricky Topic',
    // Publications
    'publications:no_evaluated' => 'Not evaluated',
    'publications:evaluated' => 'Evaluated',
    'publications:rating' => 'Rating',
    'publications:rating:name' => '%s\'s Rating',
    'publications:rating:list' => 'All evaluations',
    'publications:starsrequired' => 'Stars rating required',
    'publications:cantrating' => 'Can not rating',
    'publications:rated' => 'Successfully evaluated',
    'publications:my_rating' => 'My rating',
    'publications:question:tricky_topic' => 'Does this video help you understand the %s Tricky Topic?',
    'publications:question:sb' => 'Why is/isn\'t this tag correctly covered?',
    'publications:question:if_covered' => 'Check if each topic was covered in this video, and explain why:',
    'input:no' => 'No',
    'input:yes' => 'Yes',
    'publish'   => 'Publish',
    'published'   => 'Published',
    'publish:to_activity'   => 'Publish %s in %s',
    'publish:video'   => 'Publish video',
    // Labels
    'labels' => 'Labels',
    // Tags
    'tags' => 'Tags',
    'tags:commas:separated' => 'Separated by commas',
    // Performance items
    'performance_items' => 'Performance items',
    'performance_item:select' => 'Select performance items',
    'performance_item:info' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dapibus lacus at nisl pharetra faucibus dapibus lacus',
    // Tasks
    'activity:tasks' => 'Tasks',
    'activity:task' => 'Task',
);

add_translation('en', $english);
