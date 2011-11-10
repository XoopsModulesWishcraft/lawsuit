<?php
// $Id: form_render.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


if( preg_match('/form_render.php/', $_SERVER['PHP_SELF']) ){
	die('Access denied');
}

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$lawsuit_ele_mgr =& xoops_getmodulehandler('elements');
include_once LAWSUIT_ROOT_PATH.'class/elementrenderer.php';
if( empty($page) || !$form =& $lawsuit_form_mgr->get($page->getVar('form_id')) || $lawsuit_form_mgr->getSingleFormPermission($page->getVar('form_id')) == false ){
	header("Location: ".LAWSUIT_URL);
	exit();
} else {
	if ($GLOBALS['xoopsModuleConfig']['htaccess']) {
		$url = $page->getURL();
		if (!strpos($url, $_SERVER['REQUEST_URI'])) {
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header('Location: '.$url);
			exit(0);
		}
	}

	$xoopsOption['template_main'] = 'lawsuit_page.html';
	include_once XOOPS_ROOT_PATH.'/header.php';
	$xoTheme->addStylesheet(XOOPS_URL."/modules/lawsuit/templates/lawsuit_style.css");
		
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('form_id', $page->getVar('form_id')));
	$criteria->add(new Criteria('ele_display', 1));
	$criteria->setSort('ele_order');
	$criteria->setOrder('ASC');
	$elements =& $lawsuit_ele_mgr->getObjects($criteria, true);
	$form_output = new XoopsThemeForm($form->getVar('form_title'), 'lawsuit_'.$form->getVar('form_id'), LAWSUIT_URL.'index.php');
	foreach( $elements as $i ){
		$renderer =& new LawsuitElementRenderer($i);
		$form_ele =& $renderer->constructElement('ele_'.$i->getVar('ele_id'));
		$req = intval($i->getVar('ele_req'));
		$form_output->addElement($form_ele, $req);
		unset($form_ele);
	}
	$form_output->addElement(new XoopsFormCaptcha(), true);
	$form_output->addElement(new XoopsFormHidden('form_id', $form->getVar('form_id')));
	$form_output->addElement(new XoopsFormHidden('page_id', $page->getVar('pid')));	
	$submitbutton = new XoopsFormButton('', 'submit', $form->getVar('form_submit_text'), 'submit');
	if ($GLOBALS['LawsuitHasValidation']==true) 
		$submitbutton->setExtra('disabled="disabled"');
	$form_output->addElement($submitbutton);
	// $form_output->assign($xoopsTpl);
	
	$c = 0;
	$eles = array();
	foreach( $form_output->getElements() as $e ){
		$id = $req = $name = $ele_type = false;
		$name = $e->getName();
		$caption = $e->getCaption();
		if( !empty($name) ){
			$id = str_replace('ele_', '', $e->getName());
		}elseif( method_exists($e, 'getElements') ){
			$obj =& $e->getElements();
			$id = str_replace('ele_', '', $obj[0]->getName());
			$id = str_replace('[]', '', $id);
		}
		if( isset($elements[$id]) ){
			$req = $elements[$id]->getVar('ele_req') ? true : false;
			$ele_type = $elements[$id]->getVar('ele_type');
		}else{
			$req = false;
		}
		$eles[$c]['caption']  = $caption;
		$eles[$c]['name']	  = $name;
		$eles[$c]['body']	  = $e->render();
		$eles[$c]['hidden']	  = $e->isHidden();
		$eles[$c]['required'] = $req;
		$eles[$c]['ele_type'] = $ele_type;
		$c++;
	}
	$js = $form_output->renderValidationJS();
	$xoopsTpl->assign('form_output', array('title' => $form_output->getTitle(), 'name' => $form_output->getName(), 'action' => $form_output->getAction(),  'method' => $form_output->getMethod(), 'extra' => $form_output->getExtra(), 'javascript' => $js, 'elements' => $eles));
	
	$xoopsTpl->assign('form_req_prefix', $xoopsModuleConfig['prefix']);
	$xoopsTpl->assign('form_req_suffix', $xoopsModuleConfig['suffix']);
	$xoopsTpl->assign('form_intro', $form->getVar('form_intro'));
	$xoopsTpl->assign('form_text_global', $myts->makeTareaData4Show($xoopsModuleConfig['global']));
	if( $form->getVar('form_order') == 0 ){
		if( !isset($xoopsUser) || !is_object($xoopsUser) || !$xoopsUser->isAdmin() ){
			header("Location: ".LAWSUIT_URL);
			exit();
		}
		$xoopsTpl->assign('form_is_hidden', _LAWSUIT_FORM_IS_HIDDEN);
	}
}
$xoopsTpl->assign('page_content', $page->getVar('html'));
$xoopsTpl->assign('xoops_pagetitle', $page->getVar('title'));
?>