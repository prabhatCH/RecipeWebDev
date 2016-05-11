


<?php
/**
* @subpackage  td_loex Template
*/

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();//define path
$base_url = $this->baseurl;
$tpl_name = $this->template;
$css_urla = ''.$base_url.'/templates/'.$tpl_name.'/slider/css/';
$scripts_urla = ''.$base_url.'/templates/'.$tpl_name.'/slider/js/';

$caption         = $this->params->get ('caption');
$menu            = $this->params->get ('menu');
$slider	     = $this->params->get('slider');
$slides	     = $this->params->get('slides');
$container_height = $this->params->get('container_height');
$elasticslideshow_SlideStyle 	= $this->params->get('elasticslideshow_SlideStyle', '' );
$elasticslideshow_AutoPlay 	= $this->params->get('elasticslideshow_AutoPlay', '' );
$elasticslideshow_Interval 	= $this->params->get('elasticslideshow_Interval', '' );
$elasticslideshow_S_Easing 	= $this->params->get('elasticslideshow_S_Easing', '' );
$elasticslideshow_T_Easing 	= $this->params->get('elasticslideshow_T_Easing', '' );
$elasticslideshow_T_Speed 	= $this->params->get('elasticslideshow_T_Speed', '' );
$sliders_thumb1 	= $this->params->get('sliders_thumb1', '' );
$sliders_thumb2 	= $this->params->get('sliders_thumb2', '' );
$sliders_thumb3 	= $this->params->get('sliders_thumb3', '' );
$sliders_thumb4 	= $this->params->get('sliders_thumb4', '' );
$sliders_thumb5 	= $this->params->get('sliders_thumb5', '' );
$sliders_thumb6 	= $this->params->get('sliders_thumb6', '' );
$sliders_texts1 	= $this->params->get('sliders_texts1', '' );
$sliders_texts2 	= $this->params->get('sliders_texts2', '' );
$sliders_texts3 	= $this->params->get('sliders_texts3', '' );
$sliders_texts4 	= $this->params->get('sliders_texts4', '' );
$sliders_texts5 	= $this->params->get('sliders_texts5', '' );
$sliders_texts6 	= $this->params->get('sliders_texts6', '' );
$sliders_tex1 	= $this->params->get('sliders_tex1', '' );
$sliders_tex2 	= $this->params->get('sliders_tex2', '' );
$sliders_tex3 	= $this->params->get('sliders_tex3', '' );
$sliders_tex4 	= $this->params->get('sliders_tex4', '' );
$sliders_tex5 	= $this->params->get('sliders_tex5', '' );
$sliders_tex6 	= $this->params->get('sliders_tex6', '' );
$sliders_text1 	= $this->params->get('sliders_text1', '' );
$sliders_text2 	= $this->params->get('sliders_text2', '' );
$sliders_text3 	= $this->params->get('sliders_text3', '' );
$sliders_text4 	= $this->params->get('sliders_text4', '' );
$sliders_text5 	= $this->params->get('sliders_text5', '' );
$sliders_text6 	= $this->params->get('sliders_text6', '' );

if ($sliders_thumb1 || $sliders_thumb2 || $sliders_thumb3 || $sliders_thumb4 || $sliders_thumb5) {
// use images from template manager
} else {
// use default images
$sliders_thumb1 = 'templates/' . $this->template . '/slider/header1.jpg';
$sliders_thumb2 = 'templates/' . $this->template . '/slider/header2.jpg';
}

$doc->addStyleSheet($css_urla.'style.css');
$doc->addScript($scripts_urla.'jquery.easing.min.js');
$doc->addScript($scripts_urla.'jquery.eislideshow.min.js');

$doc->addScriptDeclaration('
jQuery(document).ready(function($){
$(function() {
$("#elasticslideshow").eislideshow({
animation			: "center",
autoplay			: true,
slideshow_interval	: 5000,
titlesFactor		: 0.60,
easing				: "easeOutExpo",
titleeasing			: "easeOutExpo",
titlespeed			: 1200
});
});
});
');

?>

<div id="elslide">
<div id="elasticslideshow" class="ei-slider" style="height:440px">
<ul class="ei-slider-large">
<?php if ($sliders_thumb1): ?> <li class="slide_<?php echo $sliders_texts1; ?>"><img src="<?php echo $sliders_thumb1; ?>"/>
</li><?php endif;?>

<?php if ($sliders_thumb2): ?> <li class="slide_<?php echo $sliders_texts2; ?>"><img src="<?php echo $sliders_thumb2; ?>"/>
</li><?php endif;?>

<?php if ($sliders_thumb3): ?> <li class="slide_<?php echo $sliders_texts3; ?>"><img src="<?php echo $sliders_thumb3; ?>"/>
</li><?php endif;?>

<?php if ($sliders_thumb4): ?> <li class="slide_<?php echo $sliders_texts4; ?>"><img src="<?php echo $sliders_thumb4; ?>"/>
</li><?php endif;?>

<?php if ($sliders_thumb5): ?> <li class="slide_<?php echo $sliders_texts5; ?>"><img src="<?php echo $sliders_thumb5; ?>"/>
</li><?php endif;?>

</ul>

<div class="ei_slider_thumbs">
<ul class="ei-slider-thumbs">
<li class="ei-slider-element"></li>	
<?php if ($sliders_thumb1): ?><li><a href="#"></a><img src="<?php echo $sliders_thumb1; ?>"/></li><?php endif;?>
<?php if ($sliders_thumb2): ?><li><a href="#"></a><img src="<?php echo $sliders_thumb2; ?>"/></li><?php endif;?>
<?php if ($sliders_thumb3): ?><li><a href="#"></a><img src="<?php echo $sliders_thumb3; ?>"/></li><?php endif;?>
<?php if ($sliders_thumb4): ?><li><a href="#"></a><img src="<?php echo $sliders_thumb4; ?>"/></li><?php endif;?>
<?php if ($sliders_thumb5): ?><li><a href="#"></a><img src="<?php echo $sliders_thumb5; ?>"/></li><?php endif;?>
</ul> 
</div> 
</div> 
</div>

