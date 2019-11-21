Голосовалка за доклады для devclub.eu
=======
Голосовалка написана на php, backbone.js, twitter bootstrap и крутится на amazon ec2 + rds. Писатель -
		<a href="http://kurapov.name/">Артём</a>, под чутким руководством <a href="http://asolntsev.livejournal.com/">Андрея</a>
		и <a href="https://groups.google.com/forum/#!topic/devclub-eu/5wyj2vBdlgY">ко</a>. Исходный код
		<a href="https://github.com/Gratheon/devclub">частично открытый</a> и принимает
		pull-запросы. Голосование частично тайное (можно видеть позицию, но не явно его автора)

Алгоритм простой - имеем две
		таблицы, в одних - доклады, в других - голоса. Когда человек голосует за доклад, формируется его список
		голосов с position от 0..N, где N - число докладов за которые он проголосовал.

Публичный рейтинг высчитвается различными сортировками, что-бы максимально всем угодить

<strong>Среднее арифметическое</strong> - обычный AVG(position). Преимущества и недостатки очевидны - можно голосовать за любое число докладов привычным упорядочиванием, только
		что добавленные доклады могут легко привлечь к себе внимание оказавшись наверху, доклады не могут быть одинаково
		важны. Пока что нельзя отказаться от голоса за доклад и среднее арифметическое часто непропорционально влияет на
		результат (т.е. одна "тридцатка" может существенно опустить)
		
<strong>Среднее геометрическое</strong> - более хитрая: <pre>EXP(AVG(LN(position)))</pre>

<strong>Среднее гармоническое</strong> - ещё более хитрая, поскольку учитывает число голосов:
<pre>COUNT(storyID)/SUM(1/(position+1))</pre>

<strong>Среднее гармоническое взвешенное</strong> (по умолчанию) - предложенный Андреем Ткачёвым, вариант с дополнительной нормализацией в зависимости от общего числа голосов и общего числа тем.
<pre>($voteCount - SQRT( ($voteCount * $voteCount) - POW(COUNT(storyID),2) )) /
( $topicCount - SQRT( ($topicCount * $topicCount) - POW(среднее гармоническое,2)))</pre>

https://www.youtube.com/watch?v=9E0bsjHOrO0

Установка
======
Надо поставить git и <a href="http://getcomposer.org/download/">composer</a>. После checkout, запускаем `composer update` что-бы вытянуть все зависимости.

Потом изменяем SiteConfig.php и прописываем настройки - URL и доступ к mysql. В этоу базу надо таблички которые описаны в `vendor/Gratheon/Devclub/Docs/schema.sql`

Добавляем в `vendor/Gratheon/Devclub/assets/` папки куда будут генерироваться динамические файлы css_cache, js_cache и view_cache и ставим на них права записи
