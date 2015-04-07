<script type='text/javascript'>
	{foreach from=$controller->arrJSVars key=key item=item}
		var {$key}={$item};
	{/foreach}
</script>

<script type="text/javascript" src='http://devclub.gratheon.com/vendor/Gratheon/Devclub/assets/js/jquery-2.0.3.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/Gratheon/Devclub/assets/js/underscore-1.5.2.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/Gratheon/Devclub/assets/js/backbone-1.1.0.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/twitter/bootstrap/js/bootstrap.min.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.core.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.widget.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.mouse.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.autocomplete.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.draggable.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.droppable.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/jquery/jquery-ui/ui/jquery.ui.sortable.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/Gratheon/Devclub/assets/js/touch-punch.js'></script>
<script type="text/javascript" src='https://browserid.org/include.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/Gratheon/Devclub/assets/js/main.js'></script>
<script type="text/javascript" src='http://devclub.gratheon.com/vendor/Gratheon/Devclub/assets/js/facebook.js'></script>


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