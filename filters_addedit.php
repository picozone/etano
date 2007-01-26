<?php
/******************************************************************************
newdsb
===============================================================================
File:                      	filters_addedit.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
require_once 'includes/tables/message_filters.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(11);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;
$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : 7;
$od=isset($_GET['od']) ? (int)$_GET['od'] : 1;

$filters=$message_filters_default['defaults'];
if (isset($_GET['filter_id']) && !empty($_GET['filter_id'])) {
	$filter_id=(int)$_GET['filter_id'];
	$query="SELECT a.*, b.`user` as `rule_value` FROM `{$dbtable_prefix}message_filters` a, `{$dbtable_prefix}user_accounts` b WHERE a.`filter_id`='$filter_id' AND a.`rule`=b.`user_id` AND a.`fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$filters=mysql_fetch_assoc($res);
		$filters=sanitize_and_format($filters,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
		$tpl->set_var('addedit_filter',true);
	} else {
		$tpl->set_var('no_filter',true);
	}
} else {
	$tpl->set_var('addedit_filter',true);
	if (isset($_SESSION['topass']['input'])) {
		$filters=$_SESSION['topass']['input'];
	}
}

$folders=array(_FOLDER_SPAMBOX_=>'Spambox');
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$folders[$rsrow[0]]=$rsrow[1];
//	$folders[$rsrow['folder_id']]=$rsrow['folder'];
}
$filters['fk_folder_id']=vector2options($folders,$filters['fk_folder_id']);

$tpl->set_file('content','filters_addedit.html');
$tpl->set_var('filters',$filters);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Add/Edit your filters';     // translate
include 'frame.php';
?>