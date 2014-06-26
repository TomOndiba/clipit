<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, URJC JuxtaLearn Team
 * @version         $Version$
 * @link            http://www.juxtalearn.eu
 * @license         GNU Affero General Public License v3
 * @package         ClipIt
 * @subpackage      clipit_api
 */

/**
 * Class ClipitVideo
 *
 */
class ClipitVideo extends ClipitResource{
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "ClipitVideo";

    const REL_RESOURCE_TAG = "video-tag";
    const REL_RESOURCE_LABEL = "video-label";
    const REL_RESOURCE_COMMENT = "video-comment";
    const REL_RESOURCE_PERFORMANCE = "video-performance";

    const REL_GROUP_RESOURCE = ClipitGroup::REL_GROUP_VIDEO;
    const REL_TASK_RESOURCE = ClipitTask::REL_TASK_VIDEO;
    const REL_ACTIVITY_RESOURCE = ClipitActivity::REL_ACTIVITY_VIDEO;
    const REL_SITE_RESOURCE = ClipitSite::REL_SITE_VIDEO;

    public $preview = "";
    public $duration = 0;

    /**
     * Loads object parameters stored in Elgg
     *
     * @param ElggEntity $elgg_entity Elgg Object to load parameters from.
     */
    protected function load_from_elgg($elgg_entity){
        parent::load_from_elgg($elgg_entity);
        $this->preview = (string)$elgg_entity->get("preview");
        $this->duration = (int)$elgg_entity->get("duration");
    }

    /**
     * Copy $this object parameters into an Elgg entity.
     *
     * @param ElggEntity $elgg_entity Elgg object instance to save $this to
     */
    protected function save_to_elgg($elgg_entity){
        parent::save_to_elgg($elgg_entity);
        $elgg_entity->set("preview", (string)$this->preview);
        $elgg_entity->set("duration", (int)$this->duration);
    }

    /**
     * Uploads to YouTube a video file from a local path in the server.
     *
     * @param string $local_video_path Local server path of the video
     * @param string $title Video title
     * @return string YouTube video URL
     */
    static function upload_to_youtube($local_video_path, $title){
        if(!get_config("google_refresh_token")){
            return false;
        }
        set_include_path( get_include_path() . PATH_SEPARATOR . elgg_get_plugins_path() . "z02_clipit_api/libraries/google_api/src/");
        require_once '../libraries/google_api/src/Google/Client.php';
        require_once '../libraries/google_api/src/Google/Service/YouTube.php';
        $client = new Google_Client();
        $client->setClientId(get_config("google_id"));
        $client->setClientSecret(get_config("google_secret"));
        try {
            $client->setAccessToken(get_config("google_token"));
        } catch(Exception $e){
            error_log($e);
        }
        if($client->isAccessTokenExpired()){
            $refresh_token = get_config("google_refresh_token");
            $client->refreshToken($refresh_token);
        }
        if (!$client->getAccessToken()) {
            return null;
        }

        // Define an object that will be used to make all API requests.
        $youtube = new Google_Service_YouTube($client);

        // Create a snippet with title, description, tags and category ID
        // Create an asset resource and set its snippet metadata and type.
        // This example sets the video's title, description, keyword tags, and
        // video category.
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($title);

        // Numeric video category. See
        // https://developers.google.com/youtube/v3/docs/videoCategories/list
        $snippet->setCategoryId("28");

        // Set the video's status to "public". Valid statuses are "public",
        // "private" and "unlisted".
        $status = new Google_Service_YouTube_VideoStatus();
        $status->privacyStatus = "unlisted";

        // Associate the snippet and status objects with a new video resource.
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        // Specify the size of each chunk of data, in bytes. Set a higher value for
        // reliable connection as fewer chunks lead to faster uploads. Set a lower
        // value for better recovery on less reliable connections.
        $chunkSizeBytes = 1 * 1024 * 1024;

        // Setting the defer flag to true tells the client to return a request which can be called
        // with ->execute(); instead of making the API call immediately.
        $client->setDefer(true);

        // Create a request for the API's videos.insert method to create and upload the video.
        var_dump($insertRequest = $youtube->videos->insert("status,snippet", $video));

        // Create a MediaFileUpload object for resumable uploads.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($local_video_path));

        // Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($local_video_path, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);

        // If you want to make other calls after the file upload, set setDefer back to false
        $client->setDefer(false);

        $_SESSION['token'] = $client->getAccessToken();
        set_config("google_token", $_SESSION['token']);

        return (string) "http://www.youtube.com/watch?v=".$status['id'];
    }
}