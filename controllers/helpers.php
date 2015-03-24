<?php

function userstatus() {
	global $helper;
	
	$loggedin = isLoggedIn();

	$user = getUserDetails();

	$helper->set('user',$user);
	return $helper->render();
}