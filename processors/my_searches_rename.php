<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/my_searches_rename.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(14);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_searches.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['search_id']=sanitize_and_format_gpc($_POST,'search_id',TYPE_INT,0,0);
	$input['title']=sanitize_and_format_gpc($_POST,'title',TYPE_STRING,HTML_TEXTFIELD,'');

	if (empty($input['title'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter a title for this search.';	// translate this
	}

	if (!$error) {
		$query="UPDATE `{$dbtable_prefix}user_searches` SET `title`='".$input['title']."' WHERE `search_id`='".$input['search_id']."' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Search title saved';
	} else {
		$nextpage='my_searches.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

if (!isset($_POST['silent'])) {
	redirect2page($nextpage,$topass,$qs);
} else {
	echo $topass['message']['text'];
	die;
}
?>