<?php
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	set_context('admin');
	// Set admin user for user block
	set_page_owner($_SESSION['guid']);
	
	
	$title = elgg_view_title(elgg_echo('activitystreamer'));
	
	$data = elgg_get_entities(array("types"=>"object", "subtypes"=>"modactivitystreamer", "owner_guids"=> '0' , "order_by"=>"","limit"=>0));
	if(!isset($data[0])) {
	    	$entity = new ElggObject;
		$entity->subtype = 'modactivitystreamer';
		$entity->owner_guid = $_SESSION['user']->getGUID();
	    	$entity->showga = true;
	    	$entity->access_id = 2;
	    	if ($entity->save()) {
			system_message('DEBUG: Initial ElggObject created with guid as: ' . $entity->guid);
		} else {
			system_message(elgg_echo('activitystreamer:modify:failed'));
		}
    } else {
	    $entity = $data[0];
    }
    
    $showga = $entity->showga;
	
    //Vars required for action gatekeeper
	$ts = time();
	$token = generate_action_token($ts);
    
	$area2 = elgg_view("activitystreamer/admin", array('showga' => $showga, 'token' => $token, 'ts' => $ts));
	
	// Display main admin menu
	page_draw(elgg_echo('activitystreamer'),elgg_view_layout("two_column_left_sidebar", '', $title . $area2));
?>
