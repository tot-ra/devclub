<!DOCTYPE HTML>
<html>
<head>
	<title>{$title}</title>

	<meta name="Author" content="Artjom Kurapov - http://kurapov.name" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<link rel="icon" href="/favicon.ico" type="image/x-icon" />

	{foreach from=$controller->arrStyles item=item}
		<link rel="stylesheet" type="text/css" href="{$item.url}" media="{$item.media}" />
	{/foreach}

	<script>
		//for dumb IE
	  document.createElement('header');
	  document.createElement('footer');
	  document.createElement('section');
	  document.createElement('aside');
	  document.createElement('nav');
	  document.createElement('article');
	</script>