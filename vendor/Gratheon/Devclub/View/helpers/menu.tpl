<div id="navbar" class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#"><img src="/vendor/Gratheon/Devclub/assets/img/devclub_mini_logo.png" alt="" /> Devclub: копилка докладов</a>

			<ul class="nav">

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
						<li><a href="#sort/date">По дате добавления</a></li>
					</ul>
				</li>

				<li>
					<a href="#" class="login logged_out" style="{if $email}display: none;{/if}">
						<img style="height: 26px;" src="/vendor/Gratheon/Devclub/assets/img/persona.png"> Mozilla Persona</a>
				</li>

				<li style="padding:17px 0 0;" class="logged_out">
					<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
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