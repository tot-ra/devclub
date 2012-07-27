{include file='helpers/head.tpl'}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div id="navbar" class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Devclub: копилка докадов</a>
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

				<li>
					<a href="#" class="login logged_out" title="Sign-in with BrowserID" style="{if $email}display: none;{/if}"><i class="icon-user icon-white"></i>
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
						<li><a href="#sort/absolute">Абсолютная</a></li>
						<li><a href="#sort/arithmetic">Арифметическая</a></li>
						<li><a href="#sort/geometric">Геометрическая</a></li>
						<li><a href="#sort/harmonic">Гармоническая</a></li>
					</ul>
				</li>

			</ul>

			<ul class="nav pull-right">
				<li><a href="#"><strong id="mail">{$email}</strong></a></li>
				<li><a class="about_trigger" href="#">About</a></li>
				<li class="logged_in" style="{if !$email}display: none;{/if}">
					<a href="#" id="logout" title="Sign-in with BrowserID"> Выйти
						<i class="icon-off icon-white"></i></a></li>
			</ul>
		</div>
	</div>
</div>

<div id="about" style="display: none;" class="well">
	<h1>Как оно работает?</h1>

	<p>Голосовалка написана на php, backbone.js, twitter bootstrap и крутится на amazon ec2 + rds. Писатель -
		<a href="http://kurapov.name/">Артём</a>, под чутким руководством <a href="http://asolntsev.livejournal.com/">Андрея</a>
		и <a href="https://groups.google.com/forum/#!topic/devclub-eu/5wyj2vBdlgY">ко</a>. Исходный код
		<a href="https://github.com/tot-ra/devclub/tree/master/app/devclub">частично открытый</a> и принимает
		pull-запросы. Голосование частично тайное (можно видеть позицию, но не явно его автора)</p>

	<p>Алгоритм простой - имеем <a href="https://github.com/tot-ra/devclub/blob/master/app/devclub/models/schema.sql">две
		таблицы</a>, в одних - доклады, в других - голоса. Когда человек голосует за доклад, формируется его список
		голосов с position от 0..N, где N - число докладов за которые он проголосовал. В публичном рейтинге высчитвается
		среднее арифметическое = AVG(position) и идёт сортировка по нему. </p>

	<p>Преимущества и недостатки очевидны - можно голосовать за любое число докладов привычным упорядочиванием, только
		что добавленные доклады могут легко привлечь к себе внимание оказавшись наверху, доклады не могут быть одинаково
		важны. Пока что нельзя отказаться от голоса за доклад и среднее арифметическое часто непропорционально влияет на
		результат (т.е. одна "тридцатка" может существенно опустить)</p>
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

<section class="row-fluid">
	<div class="col span4" style="{if !$email}display: none;{/if}">
		<h2>Интересные мне</h2>
	{if !$voted}
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			За эти темы, о которых авторы готовы рассказать, <strong>можно голосовать</strong> упорядочивая
			<i class="icon-resize-vertical"></i> список согласно вашему интересу
		</div>
	{/if}

		<div class="alert alert-error isAdmin" style="display: none;">
			<button class="close" data-dismiss="alert">×</button>
			Ты теперь <strong>необычный</strong> и можешь навсегда удалять чужие доклады. Помни об ответственности,
			spiderman
		</div>
		<ul id="icebox" class="sortable"></ul>
	</div>

	<div class="span4">
		<div class="col">
			<h2>Интересны {if $distinct_users}{$distinct_users} участникам{else}всем{/if}</a></h2>

		{if !$email}
			<div class="alert alert-info logged_out">
				<button class="close" data-dismiss="alert">×</button>
				Войдите с Mozilla BrowserID что-бы добавить новую тему
			</div>
		{/if}

			<ul id="public"></ul>
		</div>
	</div>


	<div class="span4">
		<div class="col"><h2>В подготовке</h2>

			<div class="alert alert-info">
				<button class="close" data-dismiss="alert">×</button>
				Сюда попадают отобранные организаторами темы
			</div>
			<ul id="backlog" class="sortable"></ul>
		</div>

		<div style="margin-top:20px;" class="col"><h2>Хочется послушать</h2>

			<div class="alert alert-info">
				<button class="close" data-dismiss="alert">×</button>
				Темы которые вы бы хотели услышать, как вариант - темы для <strong>openspace</strong>
			</div>
			<ul id="openspace" class="sortable"></ul>
		</div>
	</div>

</section>

{literal}
<script type="text/template" id="story_item_template">


	<% if(status=='icebox'){ %>
	<a class="vote btn btn-mini" href="#">Like</a>
	<a class="unvote btn btn-mini" href="#">unLike</a>
	<% } %>

	<% if(typeof(owner)!='undefined'){ %>

	<a class="close" href="#">&times;</a>
	<i class="icon-pencil"></i>
	<% } %>


	<% if(rate) { %>
	<span class="badge" rel="tooltip" title="число голосовавших"><i class="icon-user"></i> <%=votes%></span>
	<span class="badge badge-success" rel="tooltip" title="среднее по позициям: <%=distribution%>"><%=rate%></span>
	<%
	} %>


	<strong><%=title%></strong> &mdash; <%=authors%>

	<div style="display:none;" class="extra">
		<span class="badge"><i class="icon-time"></i> <%=duration%> мин</span>
		<em style="padding:5px 0; display:block;"><%=description%></em>
	</div>
</script>
{/literal}

{include file='helpers/js.tpl'}
</body>
</html>