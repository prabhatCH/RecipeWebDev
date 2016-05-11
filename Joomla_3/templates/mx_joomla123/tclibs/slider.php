<?php
/**
* @subpackage  mx_joomla123 Template
*/

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();//define path
$base_url = $this->baseurl;
$tpl_name = $this->template;

$caption         = $this->params->get ('caption');
$menu            = $this->params->get ('menu');
$timer	     = $this->params->get('timer');
$tredeff	     = $this->params->get('tredeff');
$speed	     = $this->params->get('speed');
$sliders_thumb1 	= $this->params->get('sliders_thumb1', '' );
$sliders_thumb2 	= $this->params->get('sliders_thumb2', '' );
$sliders_thumb3 	= $this->params->get('sliders_thumb3', '' );
$sliders_thumb4 	= $this->params->get('sliders_thumb4', '' );
$sliders_thumb5 	= $this->params->get('sliders_thumb5', '' );
$sliders_thumb6 	= $this->params->get('sliders_thumb6', '' );
$sliders_thumb7 	= $this->params->get('sliders_thumb7', '' );
$sliders_thumb8 	= $this->params->get('sliders_thumb8', '' );
$sliders_thumb9 	= $this->params->get('sliders_thumb9', '' );
$sliders_texts1 	= $this->params->get('sliders_texts1', '' );
$sliders_texts2 	= $this->params->get('sliders_texts2', '' );
$sliders_texts3 	= $this->params->get('sliders_texts3', '' );
$sliders_texts4 	= $this->params->get('sliders_texts4', '' );
$sliders_texts5 	= $this->params->get('sliders_texts5', '' );
$sliders_texts6 	= $this->params->get('sliders_texts6', '' );
$sliders_texts7 	= $this->params->get('sliders_texts7', '' );
$sliders_texts8 	= $this->params->get('sliders_texts8', '' );
$sliders_texts9 	= $this->params->get('sliders_texts9', '' );

if ($sliders_thumb1 || $sliders_thumb2 || $sliders_thumb3 || $sliders_thumb4 || $sliders_thumb5) {
// use images from template manager
} else {
// use default images
$sliders_thumb1 = $this->baseurl . '/templates/' . $this->template . '/slider/header1.jpg';
$sliders_thumb2 = $this->baseurl . '/templates/' . $this->template . '/slider/header2.jpg';
$sliders_thumb3 = $this->baseurl . '/templates/' . $this->template . '/slider/header3.jpg';
}

(($this->countModules('slider') && $slides == 2) || ($slides == 1) ?

$tcParams .= '<div class="mx_wrapper_slider"><div class="container"><div class="carousel-container"><div id="icarousel" class="icarousel">'
.($sliders_thumb1 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb1 ? '<img src="'.$sliders_thumb1.'" width="480" height="360" />' : '')
.($sliders_texts1 ? '<h5><span>'.$sliders_texts1.'</span></h5>' : '')
.($sliders_thumb1 ? '</div>' : '')

.($sliders_thumb2 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb2 ? '<img src="'.$sliders_thumb2.'" width="480" height="360" />' : '')
.($sliders_texts2 ? '<h5><span>'.$sliders_texts2.'</span></h5>' : '')
.($sliders_thumb2 ? '</div>' : '')

.($sliders_thumb3 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb3 ? '<img src="'.$sliders_thumb3.'" width="480" height="360" />' : '')
.($sliders_texts3 ? '<h5><span>'.$sliders_texts3.'</span></h5>' : '')
.($sliders_thumb3 ? '</div>' : '')

.($sliders_thumb4 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb4 ? '<img src="'.$sliders_thumb4.'" width="480" height="360" />' : '')
.($sliders_texts4 ? '<h5><span>'.$sliders_texts4.'</span></h5>' : '')
.($sliders_thumb4 ? '</div>' : '')

.($sliders_thumb5 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb5 ? '<img src="'.$sliders_thumb5.'" width="480" height="360" />' : '')
.($sliders_texts5 ? '<h5><span>'.$sliders_texts5.'</span></h5>' : '')
.($sliders_thumb5 ? '</div>' : '')

.($sliders_thumb6 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb6 ? '<img src="'.$sliders_thumb6.'" width="480" height="360" />' : '')
.($sliders_texts6 ? '<h5><span>'.$sliders_texts6.'</span></h5>' : '') 
.($sliders_thumb6 ? '</div>' : '')

.($sliders_thumb7 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb7 ? '<img src="'.$sliders_thumb7.'" width="480" height="360" />' : '')
.($sliders_texts7 ? '<h5><span>'.$sliders_texts7.'</span></h5>' : '')
.($sliders_thumb7 ? '</div>' : '')

.($sliders_thumb8 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb8 ? '<img src="'.$sliders_thumb8.'" width="480" height="360" />' : '')
.($sliders_texts8 ? '<h5><span>'.$sliders_texts8.'</span></h5>' : '')
.($sliders_thumb8 ? '</div>' : '')

.($sliders_thumb9 ? '<div class="slide" data-pausetime="'.$speed.'">' : '')
.($sliders_thumb9 ? '<img src="'.$sliders_thumb9.'" width="480" height="360" />' : '')
.($sliders_texts9 ? '<h5><span>'.$sliders_texts9.'</span></h5>' : '')
.($sliders_thumb9 ? '</div>' : '').
'</div></div></div></div>
  ' : '')
?>

      