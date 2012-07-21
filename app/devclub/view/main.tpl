{include file='helpers/head.tpl'}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div id="navbar" class="navbar">
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
	<div class="col span4" style="{if !$email}display: none;{/if}">
		<h1>Интересные мне</h1>
		{if !$voted}
			<div class="alert alert-info">
				<button class="close" data-dismiss="alert">×</button>
				За эти темы, о которых авторы готовы рассказать, <strong>можно голосовать</strong> упорядочивая <i class="icon-resize-vertical"></i> список согласно вашему интересу
			</div>
		{/if}

		<div class="alert alert-error isAdmin" style="display: none;">
			<button class="close" data-dismiss="alert">×</button>
			Ты теперь <strong>необычный</strong> и можешь навсегда удалять чужие доклады. Помни об ответсвенности, spiderman
		</div>
		<ul id="icebox" class="sortable"></ul>
	</div>

	<div class="span4">
		<div class="col">
			<h1>Интересные всем <a rel="tooltip" title="JSON API source" href="/devclub/list_public_stories/"><img src="/app/devclub/img/json_icon.png"></a></h1>

			{if !$email}
				<div class="alert alert-info">
					<button class="close" data-dismiss="alert">×</button>
					Войдите с Mozilla BrowserID что-бы добавить новую тему
				</div>
			{/if}

			<ul id="public"></ul>
		</div>
	</div>


	<div class="span4">
		<div class="col"><h1>В подготовке</h1>
			<div class="alert alert-info">
				<button class="close" data-dismiss="alert">×</button>
				Сюда попадают отобранные организаторами темы
			</div>
			<ul id="backlog" class="sortable"></ul>
		</div>

		<div class="col"><h1>Хочется послушать</h1>
			<div class="alert alert-info">
				<button class="close" data-dismiss="alert">×</button>
				Темы которые вы бы хотели услышать, как вариант - темы для <strong>openspace</strong>
			</div>
			<ul id="openspace" class="sortable"></ul>
		</div>
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
		<option value="0">openspace</option>
	</select>

	<textarea name="description" placeholder="Description" style="width:100%;height: 110px;"></textarea>

	<a href="#" class="btn btn-primary">Add story</a>
	<a href="#" class="btn btn-cancel" style="display: none;">Cancel</a>
</form>


{literal}
<script type="text/template" id="story_item_template">


	<% if(status=='icebox'){ %>
	<a class="vote btn btn-mini" href="#">Vote</a>
	<% } %>

	<% if(typeof(owner)!='undefined'){ %>

	<a class="close" href="#">&times;</a>
	<i class="icon-pencil"></i>
	<% } %>


	<span class="badge" rel="tooltip" title="duration in minutes"><i class="icon-time"></i> <%=duration%></span>

	<% if(rate) { %>
		<span class="badge" rel="tooltip" title="number of votes"><i class="icon-user"></i> <%=votes%></span>
		<span class="badge badge-success" rel="tooltip" title="average of positions:<%=distribution%>"><%=rate%></span>
	<%
	} %>




	<strong><%=title%></strong> &mdash; <%=authors%>

	<div style="display:none;" class="extra">
		<em style="padding:5px 0; display:block;"><%=description%></em>
	</div>
</script>
{/literal}

{include file='helpers/js.tpl'}
</body>
</html>