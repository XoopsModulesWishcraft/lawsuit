<?php
// $Id: index.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $

include 'header.php';
error_reporting(0);
$myts =& MyTextSanitizer::getInstance();
if( empty($_POST['submit']) ){
	$page_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$category_id = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 0;
	if ( $category_id!=0 ) {
		
		if ($GLOBALS['xoopsModuleConfig']['htaccess']) {
			$category_handler =& xoops_getmodulehandler('category', 'lawsuit');
			$category = $category_handler->get($category_id);
			$url = $category->getURL();
			if (!strpos($url, $_SERVER['REQUEST_URI'])) {
				header( "HTTP/1.1 301 Moved Permanently" ); 
				header('Location: '.$url);
				exit(0);
			}
		}
		
		$criteria = new Criteria('cid', $category_id);
		$pages =& $lawsuit_pages_mgr->getObjects($criteria);
		$xoopsOption['template_main'] = 'lawsuit_index.html';
		include_once XOOPS_ROOT_PATH.'/header.php';
		if( count($pages) > 0 ){
			foreach( $pages as $page ){
				$xoopsTpl->append('lawsuits',
								array(	'title' => $page->getVar('title'),
										'desc' => $page->getVar('description'),
										'id' => $page->getVar('pid'),
										'url' => $page->getURL()
								)
						);
			}
			$xoopsTpl->assign('lawsuit_intro', $myts->makeTareaData4Show($xoopsModuleConfig['intro']));
		}
	} elseif( empty($page_id) ){
		$pages =& $lawsuit_pages_mgr->getPermittedPages();
		if( false != $pages && count($pages) === 1 ){
			$page_id = $pages[0];
			$page = $lawsuit_pages_mgr->get($page_id);
			include 'include/form_render.php';
		}else{
			if ($GLOBALS['xoopsModuleConfig']['htaccess']) {
				$url = XOOPS_URL.'/'.$GLOBALS['xoopsModuleConfig']['baseurl'].'/index'.$GLOBALS['xoopsModuleConfig']['endofurl'];
				if (!strpos($url, $_SERVER['REQUEST_URI'])) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}
			}
				
			$xoopsOption['template_main'] = 'lawsuit_index.html';
			include_once XOOPS_ROOT_PATH.'/header.php';
			if( count($pages) > 0 ){
				foreach( $pages as $page ){
					if ($page->getVar('default'))
						$xoopsTpl->append('lawsuits',
										array(	'title' => $page->getVar('title'),
												'desc' => $page->getVar('description'),
												'id' => $page->getVar('pid'),
												'url' => $page->getURL()
										)
								);
				}
				$xoopsTpl->assign('lawsuit_intro', $myts->makeTareaData4Show($xoopsModuleConfig['intro']));
			}
		}
	}else{
		if( !$page =& $lawsuit_pages_mgr->get($page_id) ){
			header("Location: ".LAWSUIT_URL);
			exit();
		}
		
		if( false != $lawsuit_pages_mgr->getSinglePagePermission($page_id) ){
			include 'include/form_render.php';
		}else{
			header("Location: ".LAWSUIT_URL);
			exit();
		}
	}
	include XOOPS_ROOT_PATH.'/footer.php';
}else{
	$form_id = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0;
	$page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
	if( empty($form_id) || !$form =& $lawsuit_form_mgr->get($form_id) || $lawsuit_form_mgr->getSingleFormPermission($form_id) == false ) {
		header("Location: ".LAWSUIT_URL);
		exit();
	}
	if(  empty($page_id) || !$page =& $lawsuit_pages_mgr->get($page_id) || $lawsuit_pages_mgr->getSinglePagePermission($page_id) == false ) {
		header("Location: ".LAWSUIT_URL);
		exit();
	}

	include 'include/form_execute.php';
}

?>