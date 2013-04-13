<div id="navbar" class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#"><img src="/vendor/Gratheon/Devclub/assets/img/devclub_mini_logo.png" alt="" /> Devclub: копилка докладов</a>

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