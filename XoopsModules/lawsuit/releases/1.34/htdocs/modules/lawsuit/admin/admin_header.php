<?php
// $Id: admin_header.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


include '../../../include/cp_header.php';
include '../include/common.php';
include '../include/forms.php';
define('LAWSUIT_ADMIN_URL', LAWSUIT_URL.'admin/index.php');

function adminHtmlHeader(){
	xoops_cp_header();
}
error_reporting(E_ALL);
?>