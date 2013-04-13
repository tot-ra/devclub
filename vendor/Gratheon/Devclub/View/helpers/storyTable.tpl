<div class="storyRow">
	<div id="public" class="col">
		<ul id="public_ul" data-status="icebox"></ul>
	</div>

	<div id="personal" class="col hidden">
	{if !$voted}
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			За эти темы, о которых авторы готовы рассказать, <strong>можно голосовать</strong> упорядочивая
			<i class="icon-resize-vertical"></i> список согласно вашему интересу
		</div>
	{/if}
		<ul id="personal_ul" class="sortable" data-status="icebox"></ul>
	</div>

	<div id="plans" class="col hidden">

		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			Сюда попадают отобранные организаторами темы для будущих встреч
		</div>
		<ul id="backlog" class="sortable" data-status="backlog"></ul>
	</div>

	<div id="completed" class="col hidden">
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			Прошедшие темы можно выдвигать на <strong>доклад года</strong>. Голосование отдельное, <strong>скрытое</strong>, простым большинством
		</div>
		<ul id="completed_ul" class="sortable" data-status="completed"></ul>
	</div>

	<div id="openspace" class="col hidden">
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert">×</button>
			Темы которые вы бы хотели услышать, как вариант - темы для <strong>openspace</strong>
		</div>
		<ul id="openspace_ul" class="sortable" data-status="openspace"></ul>
	</div>
</div>