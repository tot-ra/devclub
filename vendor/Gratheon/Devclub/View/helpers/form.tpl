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