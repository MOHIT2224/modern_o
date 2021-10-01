<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('generate_hash_token'))
{
	function generate_hash_token($string)
	{
		return password_hash($string, PASSWORD_BCRYPT);
	}   
}

if ( ! function_exists('get_user_token'))
{
	function get_user_token($user_id="", $user_name="")
	{
		return generate_hash_token(json_encode(array("user_id"=>$user_id, "user_name"=>$user_name)));
		/* // Get a reference to the controller object
		$CI = get_instance();

		// You may need to load the model if it hasn't been pre-loaded
		$CI->load->model('my_model');

		// Call a function of the model
		$CI->my_model->do_something(); */
	}   
}
