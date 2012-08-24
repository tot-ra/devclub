<script type='text/javascript'>
	{foreach from=$controller->arrJSVars key=key item=item}
		var {$key}={$item};
	{/foreach}
</script>

{foreach from=$controller->scripts item=item}
	<script type="text/javascript" src='{$item}'></script>
{/foreach}

{literal}
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30042696-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
{/literal}