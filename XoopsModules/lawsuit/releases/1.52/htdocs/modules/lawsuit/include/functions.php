<?php
// $Id: functions.php,v 1.3 2009/06/23 17:30:00 wishcraft Exp $

if( preg_match('/functions.php/', $_SERVER['PHP_SELF']) ){
	die('Access denied');
}

if (!function_exists('xoops_sef')) {
	function xoops_sef($datab, $char ='-')
	{
		$datab = urldecode(strtolower($datab));
		$datab = str_replace(urlencode('æ'),'ae',$datab);
		$datab = str_replace(urlencode('ø'),'oe',$datab);
		$datab = str_replace(urlencode('å'),'aa',$datab);
		$replacement_chars = array(' ', '|', '=', '/', '\\', '+', '-', '_', '{', '}', ']', '[', '\'', '"', ';', ':', '?', '>', '<', '.', ',', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '`', '~', ' ', '', '¡', '¦', '§', '¨', '©', 'ª', '«', '¬', '®', '­', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿');
		$return_data = str_replace($replacement_chars,$char,urldecode($datab));
		$datab = urlencode($datab);
		#print $return_data."<BR><BR>";
		switch ($char) {
		default:
			return urldecode($return_data);
			break;
		case "-";
			return urlencode($return_data);
			break;
		}
	}
}

function xoops_module_install_lawsuit(&$module){
	$perm_handler =& xoops_gethandler('groupperm');
	for( $i=1; $i<4; $i++ ){
		$perm =& $perm_handler->create();
		$perm->setVar('gperm_name', 'lawsuit_form_access');
		$perm->setVar('gperm_itemid', 1);
		$perm->setVar('gperm_groupid', $i);
		$perm->setVar('gperm_modid', $module->getVar('mid'));
		$perm_handler->insert($perm);
	}
	
	return true;
}

if (!function_exists("lawsuit_adminMenu")) {
	function lawsuit_adminMenu ($page, $currentoption = 0)  {
		$module_handler = xoops_gethandler('module');
		$GLOBALS['lawsuitModule'] = $module_handler->getByDirname('lawsuit');
		  /* Nice buttons styles */
		echo "
	    	<style type='text/css'>
			#form {float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/".$GLOBALS['lawsuitModule']->getVar('dirname')."/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;}
			 	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
	    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/".$GLOBALS['lawsuitModule']->getVar('dirname')."/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 0px; border-bottom: 1px solid black; }
	    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
			  #buttonbar li { display:inline; margin:0; padding:0; }
			  #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/".$GLOBALS['lawsuitModule']->getVar('dirname')."/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px;  text-decoration:none; }
			  #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/".$GLOBALS['lawsuitModule']->getVar('dirname')."/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
			  /* Commented Backslash Hack hides rule from IE5-Mac \*/
			  #buttonbar a span {float:none;}
			  /* End IE5-Mac hack */
			  #buttonbar a:hover span { color:#333; }
			  #buttonbar #current a { background-position:0 -150px; border-width:0; }
			  #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
			  #buttonbar a:hover { background-position:0% -150px; }
			  #buttonbar a:hover span { background-position:100% -150px; }
			  </style>";
		
		$myts = &MyTextSanitizer::getInstance();
		$tblColors = Array();
		if (file_exists(XOOPS_ROOT_PATH . '/modules/'.$GLOBALS['lawsuitModule']->getVar('dirname').'/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php')) {
			include_once XOOPS_ROOT_PATH . '/modules/'.$GLOBALS['lawsuitModule']->getVar('dirname').'/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php';
		} else {
			include_once XOOPS_ROOT_PATH . '/modules/'.$GLOBALS['lawsuitModule']->getVar('dirname').'/language/english/modinfo.php';
		}
		echo "<div id='buttontop'>";
		echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
		echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $GLOBALS['lawsuitModule']->getVar('mid') . "\">" . _PREFERENCES . "</a></td>";
		echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($GLOBALS['lawsuitModule']->name()) ."</td>";
		echo "</tr></table>";
		echo "</div>";
		echo "<div id='buttonbar'>";
		echo "<ul>";
		foreach ($GLOBALS['lawsuitModule']->getAdminMenu() as $key => $value) {
			$tblColors[$key] = '';
			$tblColors[$currentoption] = 'current';
		  	echo "<li id='" . $tblColors[$key] . "'><a href=\"" . XOOPS_URL . "/modules/".$GLOBALS['lawsuitModule']->getVar('dirname')."/".$value['link']."\"><span>" . $value['title'] . "</span></a></li>";
		}
			 
		echo "</ul></div>";
		echo "<div id='navigation' style=\"clear:both;height:48px;\">";
		$indexAdmin = new ModuleAdmin();
		echo $indexAdmin->addNavigation($page);
		echo "</div>";
	  	
 	}
  
	function lawsuit_footer_adminMenu()
	{
		echo "<div align=\"center\"><a href=\"http://www.xoops.org\" target=\"_blank\"><img src=" . XOOPS_URL . '/' . $GLOBALS['lawsuitModule']->getInfo('icons32') . '/xoopsmicrobutton.gif'.' '." alt='XOOPS' title='XOOPS'></a></div>";
		echo "<div class='center smallsmall italic pad5'><strong>" . $GLOBALS['lawsuitModule']->getVar("name") . "</strong> is maintained by the <a class='tooltip' rel='external' href='http://www.xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a> and <a class='tooltip' rel='external' href='http://www.chronolabs.coop/' title='Visit Chronolabs Co-op'>Chronolabs Co-op</a></div>";
	}
	
}

function chronolabs_inline($flash = false)
{

	$ret = '<div style="clear:both; height 10px;">&nbsp;</div>
<div style="clear:both; height 10px;"><center><img src="http://www.chronolabs.coop/images/banners/loader/supportimage.php?flash=false" /></center></div>
<div style="clear:both;">Chronolabs offer limited free support should you want some development work done please contact us <a href="http://www.chronolabs.coop/liaise/">on the question for a quote form.</a> We offer a wide range of XOOPS Professional Solution and have options for Basic SEO and marketing of your site as well as Search Engine Optimization for <a href="http://www.xoops.org/">XOOPS</a>. If you are looking for work done with this module/application or are looking for development on your site please contact us.</div>';
	return $ret;
}

// Version 1.52
if (!function_exists("lawsuit_object2array")) {
	function lawsuit_object2array($objects) {
		$ret = array();
		foreach((array)$objects as $key => $value) {
			if (is_object($value)) {
				$ret[$key] = lawsuit_object2array($value);
			} elseif (is_array($value)) {
				$ret[$key] = lawsuit_object2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once $GLOBALS['xoops']->path('/modules/lawsuit/include/JSON.php');
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once $GLOBALS['xoops']->path('/modules/lawsuit/include/JSON.php');
		$json = new Services_JSON();
		return $json->decode($data);
	}
}


?>