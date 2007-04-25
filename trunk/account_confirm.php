<?php
/******************************************************************************
newdsb
===============================================================================
File:                       account_confirm.php
$Revision: 94 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$uid=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$secret=sanitize_and_format_gpc($_GET,'secret',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

if (!empty($uid) && !empty($secret)) {
	$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `status`='".ASTAT_ACTIVE."' WHERE `user_id`='$uid' AND `status`=".ASTAT_UNVERIFIED." AND `temp_pass`='$secret' LIMIT 1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_affected_rows()) {
		$qs='type=acctok';
		redirect2page('info.php',array(),$qs);
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Sorry, your account has not been found. Maybe you have already activated your account?';
		redirect2page('info.php',$topass);
	}
}
?>