<?php
// $Id: xoops_version.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


$modversion['name'] = _MI_LAWSUIT_NAME;
$modversion['version'] = 1.33;
$modversion['releasedate'] = "Friday: 23, July 2010";
$modversion['description'] = _MI_LAWSUIT_DESC;
$modversion['author'] = "Simon Roberts (aka wishcraft)";
$modversion['credits'] = "<a href='http://www.chronolabs.coop/' target='_blank'>Chronolabs International</a>";
$modversion['help'] = "";
$modversion['license'] = "<a href='http://creativecommons.org/licenses/GPL/2.0/' target='_blank'>Human-Readable Commons Deed</a><br /><a href='http://www.gnu.org/copyleft/gpl.html' target='_blank'>Full Legal Code</a>";
$modversion['official'] = true;
$modversion['image'] = "images/lawsuit_slogo.png";
$modversion['dirname'] = 'lawsuit';

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "lawsuit_category";
$modversion['tables'][2] = "lawsuit_pages";
$modversion['tables'][3] = "lawsuit_response";
$modversion['tables'][4] = "lawsuit_forms";
$modversion['tables'][5] = "lawsuit_formelements";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu -- content in main menu block
$modversion['hasMain'] = 1;

$modversion['onInstall'] = 'include/functions.php';

$module_handler =& xoops_gethandler('module');
$xoModule = $module_handler->getByDirname('lawsuit');
if (is_object($xoModule)) {
	$lawsuit_category_handler =& xoops_getmodulehandler('category', 'lawsuit');
	$criteria = new Criteria('domain', urlencode(XOOPS_URL));
	$categories = $lawsuit_category_handler->getObjects($criteria);
	foreach($categories as $id => $category) {
		$modversion['sub'][$id]['name'] = $category->getVar('title');
		$modversion['sub'][$id]['url'] = "index.php?cid=".$category->getVar('cid');
	}
}
	
// Templates
$modversion['templates'][1]['file'] = 'lawsuit_index.html';
$modversion['templates'][1]['description'] = _MI_LAWSUIT_TMPL_MAIN_DESC;
$modversion['templates'][2]['file'] = 'lawsuit_form.html';
$modversion['templates'][2]['description'] = _MI_LAWSUIT_TMPL_FORM_DESC;
$modversion['templates'][3]['file'] = 'lawsuit_error.html';
$modversion['templates'][3]['description'] = _MI_LAWSUIT_TMPL_ERROR_DESC;
$modversion['templates'][4]['file'] = 'lawsuit_page.html';
$modversion['templates'][4]['description'] = _MI_LAWSUIT_TMPL_PAGE_DESC;
$modversion['templates'][5]['file'] = 'lawsuit_reference.html';
$modversion['templates'][5]['description'] = _MI_LAWSUIT_TMPL_REFERENCE_DESC;
//	Module Configs
// $xoopsModuleConfig['t_width']
$modversion['config'][1]['name'] = 't_width';
$modversion['config'][1]['title'] = '_MI_LAWSUIT_TEXT_WIDTH';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = '35';

// $xoopsModuleConfig['t_max']
$modversion['config'][2]['name'] = 't_max';
$modversion['config'][2]['title'] = '_MI_LAWSUIT_TEXT_MAX';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = '255';

// $xoopsModuleConfig['ta_rows']
$modversion['config'][3]['name'] = 'ta_rows';
$modversion['config'][3]['title'] = '_MI_LAWSUIT_TAREA_ROWS';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = '5';

// $xoopsModuleConfig['ta_cols']
$modversion['config'][4]['name'] = 'ta_cols';
$modversion['config'][4]['title'] = '_MI_LAWSUIT_TAREA_COLS';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = '35';

// $xoopsModuleConfig['moreinfo']
$modversion['config'][5]['name'] = 'moreinfo';
$modversion['config'][5]['title'] = '_MI_LAWSUIT_MOREINFO';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'select_multi';
$modversion['config'][5]['valuetype'] = 'array';
$modversion['config'][5]['default'] = array('user', 'ip', 'agent');
$modversion['config'][5]['options'] = array(_MI_LAWSUIT_MOREINFO_USER => 'user', _MI_LAWSUIT_MOREINFO_IP => 'ip', _MI_LAWSUIT_MOREINFO_AGENT => 'agent', _MI_LAWSUIT_MOREINFO_FORM => 'form');

// $xoopsModuleConfig['mail_charset']
$modversion['config'][6]['name'] = 'mail_charset';
$modversion['config'][6]['title'] = '_MI_LAWSUIT_MAIL_CHARSET';
$modversion['config'][6]['description'] = '_MI_LAWSUIT_MAIL_CHARSET_DESC';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = _CHARSET;

// $xoopsModuleConfig['prefix']
$modversion['config'][7]['name'] = 'prefix';
$modversion['config'][7]['title'] = '_MI_LAWSUIT_PREFIX';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'textbox';
$modversion['config'][7]['valuetype'] = 'text';
$modversion['config'][7]['default'] = '';

// $xoopsModuleConfig['suffix']
$modversion['config'][8]['name'] = 'suffix';
$modversion['config'][8]['title'] = '_MI_LAWSUIT_SUFFIX';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = '*';

// $xoopsModuleConfig['intro']
$modversion['config'][9]['name'] = 'intro';
$modversion['config'][9]['title'] = '_MI_LAWSUIT_INTRO';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'textarea';
$modversion['config'][9]['valuetype'] = 'text';
$modversion['config'][9]['default'] = _MI_LAWSUIT_INTRO_DEFAULT;

// $xoopsModuleConfig['global']
$modversion['config'][10]['name'] = 'global';
$modversion['config'][10]['title'] = '_MI_LAWSUIT_GLOBAL';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['formtype'] = 'textarea';
$modversion['config'][10]['valuetype'] = 'text';
$modversion['config'][10]['default'] = _MI_LAWSUIT_GLOBAL_DEFAULT;

// $xoopsModuleConfig['uploaddir']
$modversion['config'][11]['name'] = 'uploaddir';
$modversion['config'][11]['title'] = '_MI_LAWSUIT_UPLOADDIR';
$modversion['config'][11]['description'] = '_MI_LAWSUIT_UPLOADDIR_DESC';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['default'] = XOOPS_UPLOAD_PATH.'/'.uniqid(rand());

xoops_load('XoopsEditorHandler');
$editor_handler = XoopsEditorHandler::getInstance();
foreach ($editor_handler->getList(false) as $id => $val)
	$options[$val] = $id;
	
$modversion['config'][12]['name'] = 'editor';
$modversion['config'][12]['title'] = "_MI_LAWSUIT_EDITORS";
$modversion['config'][12]['description'] = "_MI_LAWSUIT_EDITORS_DESC";
$modversion['config'][12]['formtype'] = 'select';
$modversion['config'][12]['valuetype'] = 'text';
$modversion['config'][12]['default'] = 'ckeditor';
$modversion['config'][12]['options'] = $options;

?>