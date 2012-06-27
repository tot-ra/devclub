{include file='helpers/head.tpl'}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Devclub backlog</a>
		{*
			  <div class="btn-group pull-left">
				  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					  <i class="icon-user"></i> Authenticate <span class="caret"></span>
				  </a>

				  <ul class="dropdown-menu">
					  <li><a href="#google_auth">Google</a></li>
					  <li><a href="#facebook_auth">Facebook</a></li>
					  <li class="divider"></li>
				  </ul>
			  </div>

  *}

			<ul class="nav">
				<li><a href="#" class="login" title="Sign-in with BrowserID" style="{if $email}display: none;{/if}"><i
						class="icon-user icon-white"></i> Sign in</a></li>
				<li><a href="#"><strong id="mail">{$email}</strong></a></li>
				<li><a href="#" style="{if !$email}display: none;{/if}" id="logout" title="Sign-in with BrowserID">Sign
					out <i class="icon-off icon-white"></i></a></li>
			</ul>
		</div>
	</div>
</div>

<section class="row-fluid">
	<div class="col span4"><h1>Темы</h1>
	{if !$email}
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			<a href="#" class="login label label-info">Sign in</a> with Mozilla BrowserID to add new presentation.<br/>
			<i class="icon-resize-vertical"></i>Then you <strong>can vote</strong> by reordering list below
		</div>

		{elseif !$voted}
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			<i class="icon-resize-vertical"></i>You <strong>can vote</strong> by reordering this list
		</div>

	{/if}

		<ul id="icebox" class="sortable"></ul>
	</div>

	<div class="col span4"><h1>В подготовке</h1>
		<ul id="backlog" class="sortable"></ul>
	</div>

	<div class="col span4"><h1>Выступили</h1>
		<ul id="current" class="sortable"></ul>
	</div>

</section>


<form class="well" id="story_form" style="{if !$email}display: none;{/if}">
	<div class="alert alert-block alert-error" style="display: none;">
		<p class="msg"></p>
	</div>

	<input type="text" name="title" placeholder="Title"/>
	<input type="text" name="authors" placeholder="Author(s)"/>
	<select name="duration">
		<option value="40">40 min</option>
		<option value="5">5 min</option>
	</select>

	<textarea name="description" placeholder="Description" style="width:100%;height: 110px;"></textarea>

	<a href="#" class="btn btn-primary">Add story</a>
	<a href="#" class="btn btn-cancel" style="display: none;">Cancel</a>
</form>

{literal}
<script type="text/template" id="story_item_template">

	<% if(typeof(owner)!='undefined'){ %>

	<a class="close" href="#">&times;</a>
	<i class="icon-pencil"></i>
	<% } %>

	<% if(status=='icebox'){ %>
	<a class="vote btn btn-mini" href="#">Vote</a>
	<% } %>

	<% if(rate) { %><span class="badge badge-success" style="float:right;" rel="tooltip"
						  title="average position, based on <%=votes%> vote<% if(votes>1){%>s<%}%>"><%=rate%></span> <%
	} %>

	<strong><%=title%></strong>

	<div style="display:none;" class="extra">
		<em style="padding:5px 0; display:block;"><%=description%></em>
		<i class="icon-user"></i> <%=authors%> | <i class="icon-time"></i> <%=duration%> min
	</div>
</script>
{/literal}

{include file='helpers/js.tpl'}
</body>
</html>