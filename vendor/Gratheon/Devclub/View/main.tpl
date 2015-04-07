{include file='helpers/head.tpl'}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

{include file='helpers/menu.tpl'}
{include file='helpers/form.tpl'}

<div class="alert alert-error isAdmin hidden">
	<button class="close" data-dismiss="alert">×</button>
	Ты теперь <strong>необычный</strong> и можешь навсегда удалять чужие доклады. Помни об ответственности,
	spiderman
</div>

<ul class="nav nav-pills" id="list_type_selection">
	<li class="active"><a data-toggle="public" href="#">Публичный рейтинг {if $distinct_users}
		<span class="label" rel="tooltip" title="Число проголосовавших">{$distinct_users}</span>{/if}</a></li>
	<li class=""><a data-toggle="personal" href="#">Личный топ</a></li>
	<li class=""><a data-toggle="plans" href="#">Планируется</a></li>
	<li class=""><a data-toggle="completed" href="#">Прошедшие</a></li>
	{*<li class=""><a data-toggle="openspace" href="#">Пожелания</a></li>*}
</ul>



{include file='helpers/storyTable.tpl'}
{include file='helpers/story.tpl'}
{include file='helpers/js.tpl'}
</body>
</html>