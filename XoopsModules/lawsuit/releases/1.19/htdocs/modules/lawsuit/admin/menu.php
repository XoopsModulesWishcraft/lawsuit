<?php
// $Id: menu.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $
global $adminmenu;
$adminmenu = array();
$adminmenu[0]['title'] = _MI_LAWSUIT_ADMENU1;
$adminmenu[0]['link'] = "admin/index.php?op=pages";
$adminmenu[0]['icon'] = "images/pages.png";
$adminmenu[1]['title'] = _MI_LAWSUIT_ADMENU2;
$adminmenu[1]['link'] = "admin/index.php?op=category";
$adminmenu[1]['icon'] = "images/categories.png";
$adminmenu[2]['title'] = _MI_LAWSUIT_ADMENU3;
$adminmenu[2]['link'] = "admin/index.php?op=list";
$adminmenu[2]['icon'] = "images/forms.png";
$adminmenu[3]['title'] = _MI_LAWSUIT_ADMENU4;
$adminmenu[3]['link'] = "admin/index.php?op=edit";
$adminmenu[3]['icon'] = "images/forms-new.png";
$adminmenu[4]['title'] = _MI_LAWSUIT_ADMENU5;
$adminmenu[4]['link'] = "admin/editelement.php";
$adminmenu[4]['icon'] = "images/elements.png";
//$adminmenu[5]['title'] = _MI_LAWSUIT_ADMENU6;
//$adminmenu[5]['link'] = "admin/reports.php";
//$adminmenu[5]['icon'] = "admin/";
$adminmenu[5]['title'] = _MI_LAWSUIT_ADMENU7;
$adminmenu[5]['link'] = "admin/permissions.php";
$adminmenu[5]['icon'] = "images/permissions.png";
?>