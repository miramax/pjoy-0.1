<?php
/* Switch off Cache, must have */
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");

/* Mime-Type, not necessarily */
header("Content-type: text/javascript;charset=utf-8");
?>
var RELANG = {};
RELANG['ru'] = {
	html: 'Код',
	video: 'Видео',
	image: 'Изображение',
	table: 'Таблица',
	link: 'Ссылка',
	link_insert: 'Вставить ссылку ...',
	unlink: 'Удалить ссылку',
	formatting: 'Форматирование',
	paragraph: 'Обычный текст',
	quote: 'Цитата',
	code: 'Код',
	header1: 'Заголовок 1',
	header2: 'Заголовок 2',
	header3: 'Заголовок 3',
	header4: 'Заголовок 4',
	bold:  'Полужирный',
	italic: 'Наклонный',
	fontcolor: 'Цвет текста',
	backcolor: 'Заливка текста',
	unorderedlist: 'Обычный список',
	orderedlist: 'Нумерованный список',
	outdent: 'Уменьшить отступ',
	indent: 'Увеличить отступ',
	cancel: 'Отменить',
	insert: 'Вставить',
	save: 'Сохранить',
	_delete: 'Удалить',
	insert_table: 'Вставить таблицу',
	insert_row_above: 'Добавить строку сверху',
	insert_row_below: 'Добавить строку снизу',
	insert_column_left: 'Добавить столбец слева',
	insert_column_right: 'Добавить столбец справа',
	delete_column: 'Удалить столбец',
	delete_row: 'Удалить строку',
	delete_table: 'Удалить таблицу',
	rows: 'Строки',
	columns: 'Столбцы',
	add_head: 'Добавить заголовок',
	delete_head: 'Удалить заголовок',
	title: 'Подсказка',
	image_position: 'Позиция изображения',
	none: 'Нет',
	left: 'Cлева',
	right: 'Cправа',
	image_web_link: 'Cсылка на изображение',
	text: 'Текст',
	mailto: 'Эл. почта',
	web: 'URL',
	video_html_code: 'Код видео ролика',
	file: 'Файл',
	upload: 'Загрузить',
	download: 'Скачать',
	choose: 'Выбрать',
	or_choose: 'Или выберите',
	drop_file_here: 'Перетащите файл сюда',
	align_left:	'По левому краю',
	align_center: 'По центру',
	align_right: 'По правому краю',
	align_justify: 'Выровнять текст по ширине',
	horizontalrule: 'Горизонтальная линейка',
	fullscreen: 'Во весь экран',
	deleted: 'Зачеркнутый',
	anchor: 'Якорь'
};