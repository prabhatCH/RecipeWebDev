<?php
defined('_JEXEC') or die('Restricted access');
///  Renderer modules  //
function TCShowModule($name, $style, $customClass = '', $compos = -1){
$modmodule = $modstep = $showlogo = $showmenu = $tcExtPosition = $tcDesktop = $tcTablet = $tcPhone = $tcCustomClass = '';
jimport( 'joomla.application.module.helper' );
$customParams = JFactory::getApplication()->getTemplate(true)->params;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();

//// MENU /////
$menucontrol = $customParams->get("menucontrol", 1);
$menu_name = $customParams->get("menutype", "mainmenu");
$renderer	= $doc->loadRenderer('module');
$module = JModuleHelper::getModule('mod_menu', '$menu_name');
$attribs['style'] = 'none';
$module->params	= "menutype=$menu_name\nshowAllChildren=1\nstartLevel=0\nendLevel=10\nclass_sfx=mx_nav mx-menu\ntag_id=tcdefaultmenu";
if($menucontrol == 1)
$showmenu = '<div id="mx_main_menu" class="mx_menufix clearfix">'.$renderer->render($module, $attribs).'</div>';


//// LOGO /////
if($customParams->get('logo') == 2)
$showlogo .= '<a id="mx_logo" href="'.JURI::root().'"><h1>'.$customParams->get('logotext').'</h1><span>'.$customParams->get('slogan').'</span></a>';
elseif($customParams->get('logo') == 1)
$showlogo .= '<a id="mx_logo" href="'.JURI::root().'"><img src="'.$customParams->get('logoimage').'" alt="Logo" /></a>';
else
$showlogo .= '<a id="mx_logo" href="'.JURI::root().'"><img src="'.JURI::root().'templates/'.$app->getTemplate().'/images/logo.png" alt="Logo" /></a>';


//// MODULES /////
$logopos = $customParams->get('logopos', 'adverts1');
$menupos = $customParams->get('menupos', 'header1');
$posValue = explode("|",$customParams->get($name, '1|'.$name+'1:12/0/0/0/'));
$posCount = $posValue[0];
$posName = (isset($posValue[1]) ? $posValue[1] : $name+'1:12/0/0/0/');
$modulecount = 0;
for ($i = 0; $i < $posCount; $i++) {
$tcCountPositions = explode(",", $posName);
$tcCountPosition = (isset($tcCountPositions[$i])) ? $tcCountPositions[$i] : '';
if($tcCountPosition != ''){
$tcPositionName = explode(":", $tcCountPosition);
$tcPositionNameValue = $tcPositionName[0];
if(isset($tcPositionName[1]) != ''){
$tcExtPosition = explode("/", $tcPositionName[1]);
if (isset($tcExtPosition[0])) $tcPositionNameGrid = $tcExtPosition[0];
if (isset($tcExtPosition[1])) $tcDesktop = $tcExtPosition[1];
if (isset($tcExtPosition[2])) $tcTablet = $tcExtPosition[2];
if (isset($tcExtPosition[3])) $tcPhone = $tcExtPosition[3];
if (isset($tcExtPosition[4])) $tcCustomClass = $tcExtPosition[4];
}
}
if (count(JModuleHelper::getModules($tcPositionNameValue)) || $logopos == ($tcPositionNameValue) || $menupos == ($tcPositionNameValue) || ($i == $compos && $compos >= 0)) :
$modmodule .='<div class="col-md-'.$tcPositionNameGrid.''.($tcDesktop ? 'hidden-desktop hidden-md hidden-lg' : '').''.($tcTablet ? 'hidden-tablet hidden-sm' : '').''.($tcPhone ? 'hidden-phone hidden-xs' : '').' '.$tcCustomClass.''.($i > 0 ? 'separator_'.$name : '').''.(($i == $compos && $compos >= 0) ? 'mx_component':'mx_block').'">';
$modmodule .=($logopos == $tcPositionNameValue ? $showlogo : '').($menupos == $tcPositionNameValue ? $showmenu : '');
$modmodule .=(($i == $compos && $compos >= 0) ? '<jdoc:include type="message" /><jdoc:include type="component" />' : '');
if (count(JModuleHelper::getModules($tcPositionNameValue)))
$modmodule .='<jdoc:include type="modules" name="'.$tcPositionNameValue.'" style="'.$style.'" />';
$modmodule .='</div>';
$modulecount = $modulecount + 1;
endif;
}
if($modulecount > 0)
$modmodule = '<section class="mx_wrapper_'.$name.' mx_section"><div class="'.$customClass.' mx_group"><div id="mx_'.$name.'" class="mx_'.$name.' row-fluid clearfix">'
.$modmodule.
'</div></div></section>';

return $modmodule;
}
///  Cookies  //
$cookie_prefix = $this->template;
$cookie_time = time()+30000000;
$mx_temp = array('TemplateStyle','Layout');
foreach ($mx_temp as $tprop) {
$mx_session = JFactory::getSession();

if (isset($_REQUEST[$tprop])) {
$$tprop = JRequest::getString($tprop, null, 'get');
$mx_session->set($cookie_prefix.$tprop, $$tprop);
setcookie ($cookie_prefix. $tprop, $$tprop, $cookie_time, '/', false);   
global $$tprop; 
}
}
jimport( 'joomla.application.module.helper' );
$customParams = JFactory::getApplication()->getTemplate(true)->params;
$pageview = JRequest::getVar('view', '');
$pageoption = JRequest::getVar('option', '');
$pageID = JRequest::getVar('Itemid', '');
$slides	     = $this->params->get('slides');
$template_baseurl = $this->baseurl.'/templates/'.$this->template;
$Default_Layout	= $this->params->get("layout", "lbr");
$copyright = $this->params->get("copyright", 1);
$cpright = $this->params->get("cpright", "");
$logo = $this->params->get("logo", 0);
$menuStick = $this->params->get("menuStick", 1);
$pos_menu = $customParams->get("pos_menu", "");
//FEATURES
$menucontrol = $this->params->get("menucontrol", 1);
$totop = $this->params->get("totop", 1);
$jquery = $this->params->get("jquery", 0);
$document	= JFactory::getDocument();
$jversion = new JVersion;
$document->addStyleSheet($template_baseurl.'/css/bootstrap/css/bootstrap.css');
$document->addStyleSheet($template_baseurl.'/tclibs/menus/css/menu.css');
$document->addStyleSheet($template_baseurl.'/css/template.css');
$document->addStyleSheet($template_baseurl.'/css/font-awesome/css/font-awesome.min.css');
if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php')) {
$document->addStyleSheet($template_baseurl.'/css/k2.css');
}

if ($jquery == 1) 
$document->addScript($template_baseurl.'/tclibs/helper/jquery-1.11.1.min.js');
if ($jquery == 2){
if (version_compare($jversion->getShortVersion(), '3.0.0', '<'))
$document->addScript($template_baseurl.'/tclibs/helper/jquery-1.11.1.min.js');
}
$document->addScript($template_baseurl.'/css/bootstrap/js/bootstrap.min.js');
$document->addScript($template_baseurl.'/tclibs/helper/browser-detect.js');
if ($menucontrol == 1) {
$document->addScript($template_baseurl.'/tclibs/menus/jquery.hoverIntent.minified.js');
$document->addScript($template_baseurl.'/tclibs/menus/jquery.menu.js');
}
if(($this->countModules('slider') && $slides == 2) || ($slides == 1)){ 
$document->addStyleSheet($template_baseurl.'/slider/css/icarousel.css.css');
$document->addStyleSheet($template_baseurl.'/slider/css/light.css');
$document->addScript($template_baseurl.'/slider/js/raphael-min.js');
$document->addScript($template_baseurl.'/slider/js/jquery.mousewheel.js');
$document->addScript($template_baseurl.'/slider/js/icarousel.packed.js');
$document->addScriptDeclaration("
jQuery(document).ready(function(){
jQuery('#icarousel').iCarousel({
easing: 'easeInOutQuint',
slides: 3,
animationSpeed: 700,
pauseTime: 5000,
perspective: 75,
slidesSpace: 450,
pauseOnHover: true,
direction: 'ltr',
timer: 'Pie',
timerOpacity: 0.4,
timerDiameter: 20,
keyboardNav: true,
mouseWheel: true,
touchNav: true,
timerPadding: 3,
timerStroke: 4,
timerBarStroke: 0,
timerColor: '#FFF',
timerPosition: 'top-right',
timerX: 15,
timerY: 30,
make3D: true,
directionNav: false,
autoPlay: true,
randomStart: false,
startSlide: 0});
});");}
if ($totop == 1) 
$document->addScript($template_baseurl.'/tclibs/helper/scrolltotop.js');
if ($totop == 1) 
$document->addScriptDeclaration("
jQuery(document).ready(function() {
jQuery(document.body).SLScrollToTop({
'text':			'Go to Top',
'title':		'Go to Top',
'className':	'scrollToTop',
'duration':		500
});
});");
if ($menucontrol == 1) 
$document->addScriptDeclaration("
var tcDefaultMenu = jQuery.noConflict();
jQuery(document).ready(function(){
jQuery('#tcdefaultmenu').oMenu({
theme: 'default-menu',
effect: 'slide',
mouseEvent: 'hover'
});
});");
require_once (dirname(__FILE__).DS.'sett.php');
require_once (dirname(__FILE__).DS.'browsers.php');
?>


