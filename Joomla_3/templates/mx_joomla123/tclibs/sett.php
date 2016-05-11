<?php
defined( '_JEXEC' ) or die( 'Restricted index access' );
$Layout = $Default_Layout;
$mainurl = $_SERVER['PHP_SELF'] . rebuildQueryString($mx_temp);
foreach ($mx_temp as $tprop) {
$mx_session = JFactory::getSession();
if ($mx_session->get($cookie_prefix.$tprop)) {
$$tprop = $mx_session->get($cookie_prefix.$tprop);
} elseif (isset($_COOKIE[$cookie_prefix. $tprop])) {
$$tprop = JRequest::getVar($cookie_prefix. $tprop, '', 'COOKIE', 'STRING');
}    
}
function rebuildQueryString($mx_temp) {
if (!empty($_SERVER['QUERY_STRING'])) {
$parts = explode("&", $_SERVER['QUERY_STRING']);
$newParts = array();
foreach ($parts as $val) {
$val_parts = explode("=", $val);
if (!in_array($val_parts[0], $mx_temp)) {
array_push($newParts, $val);
}
}
if (count($newParts) != 0) {
$qs = implode("&amp;", $newParts);
} else {
return "?";
}
return "?" . $qs . "&amp;";
} else {
return "?";
} 
}
/////// Select Layouts ///////
if($this->countModules('right and left') && $Layout == "lbr") :
$component = TCShowModule('lbr', 'mx_xhtml', 'mx_aside', 1);
else :
if(($this->countModules('right') && $Layout == "br") || ($this->countModules('right') && $Layout == "lbr")) :
$component = TCShowModule('br', 'mx_xhtml', 'mx_aside', 0);
else :
if(($this->countModules('left') && $Layout == "lb") || ($this->countModules('left') && $Layout == "lbr")) :
$component = TCShowModule('lb', 'mx_xhtml', 'mx_aside', 1);
else :
if((!$this->countModules('right and left') && ($Layout == "lbr")) || (!$this->countModules('right') && ($Layout == "br")) || (!$this->countModules('left') && ($Layout == "lb"))) :
$component = '<div class="col-md-12 mx_component"><jdoc:include type="component" /><jdoc:include type="modules" name="inset" style="mx_xhtml" /></div>';
endif;
endif;
endif;
endif;
?>