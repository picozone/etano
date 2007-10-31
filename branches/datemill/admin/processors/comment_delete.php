<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/comment_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$comment_id=isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
$m=sanitize_and_format_gpc($_GET,'m',TYPE_STRING,0,'');
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

switch ($m) {

	case 'blog':
		$table="`{$dbtable_prefix}blog_comments`";
		$parent_table="`{$dbtable_prefix}blog_posts`";
		$parent_key="`post_id`";
		break;

	case 'photo':
		$table="`{$dbtable_prefix}photo_comments`";
		$parent_table="`{$dbtable_prefix}user_photos`";
		$parent_key="`photo_id`";
		break;

	case 'user':
		$table="`{$dbtable_prefix}profile_comments`";
		$parent_table="`{$dbtable_prefix}user_profiles`";
		$parent_key="`fk_user_id`";
		break;

}

on_before_delete_comment(array($comment_id),$m);

$query="DELETE FROM $table WHERE `comment_id`=$comment_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Comment deleted.';

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/comment_search.php';
}
redirect2page($nextpage,$topass,'',true);