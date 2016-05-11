<?php
/**
* @subpackage  mx_joomla123 Template
*/

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();//define path
$base_url = $this->baseurl;
$tpl_name = $this->template;
$css_urls = ''.$base_url.'/templates/'.$tpl_name.'/css/';


$socialCode = $this->params->get('socialCode');
$blogger_icon = $this->params->get('blogger_icon');
$digg_icon = $this->params->get('digg_icon');
$facebook_icon = $this->params->get('facebook_icon');
$flickr_icon = $this->params->get('flickr_icon');
$google_icon = $this->params->get('google_icon');
$linkedin_icon = $this->params->get('linkedin_icon');
$myspace_icon = $this->params->get('myspace_icon');
$pinterest_icon = $this->params->get('pinterest_icon');
$stumble_icon = $this->params->get('stumble_icon');
$twitter_icon = $this->params->get('twitter_icon');
$rssfeed_icon = $this->params->get('rssfeed_icon');

if($socialCode !='0') {
$doc->addStyleSheet($css_urls.'social.css');

$tcParams .= '
<div id="social-bookmarks">
<ul class="social-bookmarks">'
.($facebook_icon ? '<li class="facebook"><a href="'.$facebook_icon.'">Follow via facebook</a></li>' : '')
.($twitter_icon ? '<li class="twitter"><a href="'.$twitter_icon.'">Follow via twitter</a></li>' : '')
.($blogger_icon ? '<li class="blogger"><a href="'.$blogger_icon.'">Follow via blogger</a></li>' : '')
.($digg_icon ? '<li class="digg"><a href="'.$digg_icon.'">Follow via digg</a></li>' : '')
.($flickr_icon ? '<li class="flickr"><a href="'.$flickr_icon.'">Follow via flickr</a></li>' : '')
.($google_icon ? '<li class="google"><a href="'.$google_icon.'">Follow via google</a></li>' : '')
.($linkedin_icon ? '<li class="linkedin"><a href="'.$linkedin_icon.'">Follow via linkedin</a></li>' : '')
.($myspace_icon ? '<li class="myspace"><a href="'.$myspace_icon.'">Follow via myspace</a></li>' : '')
.($pinterest_icon ? '<li class="pinterest"><a href="'.$pinterest_icon.'">Follow via pinterest</a></li>' : '')
.($stumble_icon ? '<li class="stumbleupon"><a href="'.$stumble_icon.'">Follow via stumble</a></li>' : '')
.($rssfeed_icon ? '<li class="rss"><a href="'.$rssfeed_icon.'">Follow via rss</a></li>' : ''). 
'</ul>
</div> ';
}
?>

      