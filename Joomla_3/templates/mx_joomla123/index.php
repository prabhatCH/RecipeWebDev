<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language;?>" >
<?php
/* @package     mx_joomla123 Template
 * @author		mixwebtemplates http://www.mixwebtemplates.com
 * @copyright	Copyright (c) 2006 - 2012 mixwebtemplates. All rights reserved
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')){define('DS',DIRECTORY_SEPARATOR);}
$tcParams = '';
include_once(dirname(__FILE__).DS.'tclibs'.DS.'head.php');
include_once(dirname(__FILE__).DS.'tclibs'.DS.'settings.php');
$tcParams .= '<body id="tc">';
$tcParams .= TCShowModule('adverts', 'mx_xhtml', 'container');
$tcParams .= '<div id="mx_wrapper" class="mx_wrapper">';
$tcParams .=  TCShowModule('header', 'mx_xhtml', 'container');
include_once(dirname(__FILE__).DS.'tclibs'.DS.'slider.php');
include_once(dirname(__FILE__).DS.'tclibs'.DS.'social.php');
$tcParams .=  TCShowModule('slider', 'mx_xhtml', 'container');
$tcParams .=  TCShowModule('top', 'mx_xhtml', 'container');
$tcParams .=  TCShowModule('info', 'mx_xhtml', 'container');
$tcParams .=  TCShowModule('maintop', 'mx_xhtml', 'container');
$tcParams .= '<main class="mx_main container clearfix">'.$component.'</main>';
$tcParams .=  TCShowModule('mainbottom', 'mx_xhtml', 'container').
TCShowModule('feature', 'mx_xhtml', 'container').
TCShowModule('bottom', 'mx_xhtml', 'container').
TCShowModule('footer', 'mx_xhtml', 'container');
$tcParams .= '<footer class="mx_wrapper_copyright mx_section">'.
'<div class="container clearfix">'.
'<div class="col-md-12">'.($copyright ? '<div style="padding:10px;">'.$cpright.' </div>' : ''). /* You CAN NOT remove (or unreadable) this without mixwebtemplates.com permission */'<div style="padding-bottom:10px; text-align:right; ">Designed by <a href="http://www.mixwebtemplates.com/" title="Visit mixwebtemplates.com!" target="blank">mixwebtemplates.com</a></div>'.
'</div>'.
'</div>'.
'</footer>';
$tcParams .='</div>';	   
include_once(dirname(__FILE__).DS.'tclibs'.DS.'debug.php');
$tcParams .='</body>';
$tcParams .='</html>';
echo $tcParams;
?>