<?php

if(!function_exists('css'))
{
	function css($filename)
	{
		$link = base_url("assets/css/{$filename}");
		return "<link href=\"{$link}\" rel=\"stylesheet\"/>";
	}
}

if(!function_exists('script'))
{
	function script($filename)
	{
		$link = base_url("assets/js/{$filename}");
		return "<script type=\"text/javascript\" src=\"{$link}\"></script>";
	}
}

if(!function_exists('plugin_script'))
{
	function plugin_script($filename)
	{
		$link = base_url("assets/plugins/{$filename}");
		return "<script type=\"text/javascript\" src=\"{$link}\"></script>";
	}
}

if(!function_exists('plugin_css'))
{
	function plugin_css($filename)
	{
		$link = base_url("assets/plugins/{$filename}");
		return "<link href=\"{$link}\" rel=\"stylesheet\"/>";
	}
}

if(!function_exists('image'))
{
	function image($filename, $width, $height, $style = FALSE)
	{
		$link = base_url("assets/img/reports/{$filename}");
		return  "<img src=\"{$link}\" class=\"img-rounded\" width=\"{$width}\" height=\"{$height}\" style=\"{$style}\">";
	}
}