<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/file_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
$output['file']=str_replace('..','',preg_replace('~[^a-zA-Z0-9\._/-]~','',sanitize_and_format_gpc($_GET,'f',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'')));
if (!empty($output['file']) && $output['file']{0}=='/') {
	$output['file']=substr($output['file'],1);
}
$file=_BASEPATH_.'/'.$output['file'];

$mode=isset($_GET['m']) ? (int)$_GET['m'] : 1;

if (is_file($file)) {
	$output['file_content']=str_replace(array('{','}'),array('&#x007B;','&#x007D;'),sanitize_and_format(file_get_contents($file),TYPE_STRING,$__field2format[TEXT_DB2EDIT]));
}

$tpl->set_file('content','file_edit.html');
if ($mode==2) {
	$output['richedit']=true;
}
$output['path']=urlencode(pathinfo($output['file'],PATHINFO_DIRNAME));
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='File editor';
$tplvars['css']='file_edit.css';
include 'frame.php';
?>