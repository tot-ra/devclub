{include file='helpers/head.tpl'}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div id="navbar" class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Devclub: копилка докладов</a>

			<ul class="nav">
				<li>
					<a href="#" class="login logged_out" title="Mozilla Persona" style="{if $email}display: none;{/if}"><i class="icon-user icon-white"></i>
						Войти</a></li>

				<li>
					<a class="story_form_trigger logged_in" href="#" style="{if !$email}display: none;{/if}"><i class="icon-plus icon-white"></i>
						Предложить свой доклад</a></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle"
					   data-toggle="dropdown">
						Сортировка
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#sort/absolute">По числу голосов</a></li>
						<li><a href="#sort/arithmetic">Арифметическая по позициям</a></li>
						<li><a href="#sort/geometric">Геометрическая по позициям</a></li>
						<li><a href="#sort/harmonic">Гармоническая по позициям</a></li>
						<li><a href="#sort/harmonic_weight">Гармоническая взвешенная</a></li>
					</ul>
				</li>

			</ul>

			<ul class="nav pull-right">
				<li><a href="#"><strong id="mail">{$email}</strong></a></li>
				<li><a href="https://github.com/Gratheon/devclub">About</a></li>
				<li class="logged_in" style="{if !$email}display: none;{/if}">
					<a href="#" id="logout" title="Mozilla Persona"> Выйти
						<i class="icon-off icon-white"></i></a></li>
			</ul>
		</div>
	</div>
</div>

<form class="well" id="story_form" style="display: none;">
	<h1 style="margin-bottom: 6px;">Новый доклад</h1>

	<div class="alert alert-block alert-error" style="display: none;">
		<p class="msg"></p>
	</div>

	<input type="text" name="title" placeholder="Название"/>
	<input type="text" name="authors" placeholder="Автор(ы)"/>
	<select name="duration">
		<option value="40">40 мин</option>
		<option value="5">пятиминутка</option>
		<option value="0">openspace</option>
	</select>

	<textarea name="description" placeholder="Описание" style="width:100%;height: 110px;"></textarea>

	<a href="#" class="btn btn-primary">Предложить доклад</a>
	<a href="#" class="btn btn-cancel" style="display: none;">Cancel</a>
</form>

<div class="alert alert-error isAdmin hidden">
	<button class="close" data-dismiss="alert">×</button>
	Ты теперь <strong>необычный</strong> и можешь навсегда удалять чужие доклады. Помни об ответственности,
	spiderman
</div>

<ul class="nav nav-pills">
	<li class="active"><a data-toggle="public" href="#"> Публичный рейтинг {if $distinct_users}
		<span class="label" rel="tooltip" title="Число проголосовавших">{$distinct_users}</span>{/if}</a></li>
	<li class=""><a data-toggle="personal" href="#"> Личный топ</a></li>
	<li class=""><a data-toggle="plans" href="#"> Планируется</a></li>
	<li class=""><a data-toggle="completed" href="#"> Прошедшие</a></li>
	<li class=""><a data-toggle="openspace" href="#"> Openspace</a></li>
</ul>

<div class="row">
	<div id="public" class="col span5">
		<ul id="public_ul" data-status="icebox"></ul>
	</div>

	<div id="personal" class="col hidden span5">
	{if !$voted}
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			За эти темы, о которых авторы готовы рассказать, <strong>можно голосовать</strong> упорядочивая
			<i class="icon-resize-vertical"></i> список согласно вашему интересу
		</div>
	{/if}
		<ul id="personal_ul" class="sortable" data-status="icebox"></ul>
	</div>

	<div id="plans" class="col hidden span5">

		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			Сюда попадают отобранные организаторами темы для будущих встреч
		</div>
		<ul id="backlog" class="sortable" data-status="backlog"></ul>
	</div>

	<div id="completed" class="col hidden span5">
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			Прошедшие темы можно выдвигать на <strong>доклад года</strong>. Голосование отдельное, <strong>скрытое</strong>, простым большинством
		</div>
		<ul id="completed_ul" class="sortable" data-status="completed"></ul>
	</div>

	<div id="openspace" class="col hidden span5">
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			Темы которые вы бы хотели услышать, как вариант - темы для <strong>openspace</strong>
		</div>
		<ul id="openspace_ul" class="sortable" data-status="openspace"></ul>
	</div>
</div>
</div>

{literal}
<script type="text/template" id="story_item_template">

	<img src="/devclub/vendor/Gratheon/Devclub/assets/img/drag_icon.gif" class="draghandle" />
	<strong class="draghandle"><%=title%></strong>

	<% if(typeof(owner)!='undefined' && owner){ %>
	<a class="close logged_in" href="#">&times;</a>
	<i class="icon-pencil logged_in"></i>
	<% } %>

	<br /> 	<% if(status=='icebox'){ %>
		<a class="vote btn btn-mini logged_in" href="#"><i class="icon-thumbs-up"></i></a>
		<a class="unvote btn btn-mini logged_in" href="#"><i class="icon-minus"></i></a>
		<% } %>

	<%=authors%>

	<% if(rate) { %>
	<span style="border-radius: 0 5px 5px 0;" class="label label-success" rel="tooltip" title="среднее по позициям: <%=distribution%>"><%=rate%></span>
	<% } %>
	<span class="label" style="border-radius:5px 0 0 5px;">
		<i class="icon-time"></i> <%=duration%>
		<% if(rate) { %>
		<i rel="tooltip" title="число голосовавших" class="icon-user"></i> <%=votes%>
		<% } %>
	</span>

	<% if(status=='completed'){ %>
	<a class="yearvote btn btn-mini logged_in" href="#"><i class="icon-plus-sign"></i></a>
	<a class="yearunvote btn btn-mini logged_in" href="#"><i class="icon-minus"></i></a>
	<span class="label label-important" rel="tooltip" title="число голосовавших скрыто"><i class="icon-user"></i> <%=votes%></span>
	<% } %>


	<div style="display:none;" class="extra">
		<% if(typeof(gravatar)!='undefined'){%>
		<img src="https://gravatar.com/avatar/<%=gravatar%>?s=40" style="float:right;margin-left:3px;"/>
		<%}%>
		<em style="padding:5px 0; display:block;"><%=description%></em>

		<div style="clear:both;"></div>
	</div>
</script>
{/literal}

{include file='helpers/js.tpl'}
</body>
</html>