@extends('layouts.master')

@section('content')

	{!! csrf_field() !!}
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/6444/lorem.js"></script>
	<script>
	$(function() {
		
		var fieldsWithCSRF = function(fields){
			var csrf = $('input[type="hidden"][name="_token"]').val();
			fields['_token'] = csrf;
			return fields;
		}

		var versesSelected = [];
		var bible = {
			books: ["Genesis", "Exodus", "Leviticus", "Numbers", "Deuteronomy", "Joshua", "Judges", "Ruth", "1 Samuel", "2 Samuel", "1 Kings", "2 Kings", "1 Chronicles", "2 Chronicles", "Ezra", "Nehemiah", "Esther", "Job", "Psalms", "Proverbs", "Ecclesiastes", "Song of Songs", "Isaiah", "Jeremiah", "Lamentations", "Ezekiel", "Daniel", "Hosea", "Joel", "Amos", "Obadiah", "Jonah", "Micah", "Nahum", "Habakkuk", "Zephaniah", "Haggai", "Zechariah", "Malachi", "Matthew", "Mark", "Luke", "John", "Acts", "Romans", "1 Corinthians", "2 Corinthians", "Galatians", "Ephesians", "Philippians", "Colossians", "1 Thessalonians", "2 Thessalonians", "1 Timothy", "2 Timothy", "Titus", "Philemon", "Hebrews", "James", "1 Peter", "2 Peter", "1 John", "2 John", "3 John", "Jude", "Revelation"],
			chapters: ["50", "40", "27", "36", "34", "24", "21", "4", "31", "24", "22", "25", "29", "36", "10", "13", "10", "42", "150", "31", "12", "8", "66", "52", "5", "48", "12", "14", "3", "9", "1", "4", "7", "3", "3", "3", "2", "14", "4", "28", "16", "24", "21", "28", "16", "16", "13", "6", "6", "4", "4", "5", "3", "6", "4", "3", "1", "13", "5", "5", "3", "5", "1", "1", "1", "22"]
		}; 
		
		var bookNames = [];

		var defaultSearch = { book: '1', chapter: '1', verses: '1' };

		$('.container').data('book',defaultSearch.book);
		$('.container').data('chapter',defaultSearch.chapter);
		$('.container').data('verses',defaultSearch.verses);

		$.getJSON('/api/books', function(data){
		  $.each(data.books, function(index, val){
		  	bookNames.push(this.id);
		  });
		});

		var categories = ['Who is God?','What has God done?', 'Who are we?', 'What do we do?'];
		var item = '<li class="list__item"><i class="fa fa-times list__item--remove"></i></li>';

		var curCategory = 0;
		var menuOpen = false;

		var openMenu = function(){
			menuOpen = true;
			$('.nav-top__menu, .nav-top__hamburger').addClass('is-active');
		}
		var closeMenu = function(){
			menuOpen = false;
			$('.nav-top__menu, .nav-top__hamburger').removeClass('is-active');
		}

		var switchCategories = function(e){
			
			var index = $('.nav-top__menu--item a').index(this);
			if(curCategory != index)
			{
				$('.list.is-visible').removeClass('is-visible');
				$('.list').eq(index).addClass('is-visible');
				
				$('.nav-top__label').text( $(this).text() );		
				$(".nav-top__menu--item.is-active").removeClass('is-active');
				$(this).parent().addClass('is-active');
				curCategory = index;
				closeMenu();
			}	
			
			e.preventDefault();
		}

		var addItem = function(e){

			var $category = $('.list.is-visible');
			var $item = $(item);
			var $input = $('.add-item__input');

			var book = $('.container').data('book');
			var chapter = $('.container').data('chapter');
			var verses = $('.container').data('verses');

			$.post('/user/item/add', fieldsWithCSRF({ category_id: (curCategory + 1), book: book, chapter: chapter, verses: verses, content: $input.val() }), function(data){

				if(data.success)
				{
					$item.text( $input.val() );
					$input.val('');
					$category.append($item);
				}
				else
				{
					alert('There was an error. Please try again.');
				}

			});
			
			e.preventDefault();
		}

		$.each(bible.books, function(index, value){
			
			var $opt = $('<div class="change-ref__dropdown--option"></div>');
			
			if(index == 0)
			{
				$('.change-ref__book .change-ref__dropdown--label').text(value);
				$('.change-ref__book input').val('1');
			}
			
			$opt.data('value',index);
			$opt.text(value);
			
			$('.change-ref__book .change-ref__dropdown--options').append($opt);
			
		});

		var updateChapters = function(){
			var numChapters = bible.chapters[$('.change-ref__book input').val()];
			numChapters = (numChapters) ? numChapters : bible.chapters[0];
			$('.container').data('book', $('.change-ref__book input').val());
			
			$('.change-ref__chapter .change-ref__dropdown--options').empty();
			$('.change-ref__chapter .change-ref__dropdown--label').text('1');
			for (var i = 1; i <= numChapters; i++) {
				$('.change-ref__chapter .change-ref__dropdown--options').append('<div class="change-ref__dropdown--option" data-value="' + i + '">' + i + '</div>');
			}
		}

		var panelOverlay = function(e){
			if($(e.target).hasClass('change-ref__verses') || $(e.target).hasClass('change-ref__verse'))
			{
				//
			}
			else
			{
				$('.change-ref__verses').removeClass('is-active');
				$(window).off('click', panelOverlay);
			}
		}

		var updateVerses = function(){
			var $verses = $('.change-ref__verses');
			$('.container').data('chapter', $('.change-ref__chapter input').val());
			
			$verses.addClass('is-active');
			$verses.empty();

			var book = bookNames[$('.container').data('book') - 1];
			var chapter = $('.container').data('chapter');

			$.getJSON('/api/verses/' + book + '/' + chapter, function(data){
				var numVerses = data.verses.length;

				for (var i = 1; i <= numVerses; i++) {
					$verses.append('<li class="change-ref__verse">' + i + '</li>');
				}
			});
			
			setTimeout(function(){
				$(window).on('click', panelOverlay);
			}, 250);
		}

		updateChapters();

		$('.change-ref__book').change(updateChapters);

		$('.nav-top__label').text(categories[0]);
		$.each(categories, function(index, val){
			
			$('.nav-top__menu').append('<li class="nav-top__menu--item"><a href="#">' + val + '</a></li>');
			
		});

		$.each(categories, function(index, val){
			
			var $list = $('<ul class="list"></ul>');
			
			$('.content-container').append($list);
			if(index == 0) $list.addClass('is-visible');
			$list.data('type', index);

			var category_id = index + 1;
			
			$.getJSON('user/' + category_id + '/' + defaultSearch.book + '/' + defaultSearch.chapter + '/' + defaultSearch.verses + '/items', function(data){
				
				$.each(data, function(index, val){
					var $item = $(item);
					$item.text( this.content );
					$list.append( $item );
				});

			});
			
		});

		$('.change-ref__dropdown--label').on('click', function(e){
			
			if(!$(this).parents('.change-ref__dropdown').hasClass('is-active')) $('.change-ref__dropdown.is-active').removeClass('is-active');
			$(this).parents('.change-ref__dropdown').toggleClass('is-active');
			e.preventDefault();
		});
		$('.change-ref__dropdown').on('click', '.change-ref__dropdown--option', function(e){
			
			$(this).parents('.change-ref__dropdown').find('input').val($(this).data('value'));
			$(this).parents('.change-ref__dropdown').removeClass('is-active');
			
			if($(this).parents('.change-ref__dropdown').data('type') == 'books')
			{
				updateChapters();
				$('.change-ref__book .change-ref__dropdown--label').text( $(this).text() );
			}
			else if($(this).parents('.change-ref__dropdown').data('type') == 'chapters')
			{
				updateVerses();
				$('.change-ref__chapter .change-ref__dropdown--label').text( $(this).text() );
			}
			
			e.preventDefault();
		});

		$('.change-ref__verses').on('click', '.change-ref__verse', function(e){
			
			$(this).toggleClass('is-active');
			versesSelected = [];
			$('.change-ref__verse.is-active').each(function(){
				versesSelected.push($(this).text());
				$('.container').data('verses', versesSelected.join(','));
			});

			var book = $('.container').data('book');
			var chapter = $('.container').data('chapter');
			// var book = $('.container').data('book');

			$.getJSON('user/' + (curCategory + 1) + '/' + book + '/' + chapter + '/' + defaultSearch.verses + '/items', function(data){

				var $list = $('.list');
				$list.empty();
				
				$.each(data, function(index, val){
					var $item = $(item);
					$item.text( this.content );
					$list.append( $item );
				});

			});
			
			e.preventDefault();
		});

		$('.nav-top__menu--item').eq(curCategory).addClass('is-active');
		$('.nav-top__menu').on('click', '.nav-top__menu--item a', switchCategories);

		$('.nav-top__hamburger').on('click touchstart', function(e){
			
			if(menuOpen)
			{
				closeMenu();
			}
			else
			{
				openMenu();
			}
			
			e.preventDefault();
		});

		$('.nav-bottom__item--add').on('click', function(e){
			
			$('.add-item__input').trigger('focus');
			$('.add-item').toggleClass('is-active');
			$('.change-ref.is-active').removeClass('is-active');
			
			e.preventDefault();
		});

		$('.add-item').on('submit', addItem);

		$('.add-item__cancel').on('click', function(e){
			$(this).parents('.add-item').removeClass('is-active');
			e.preventDefault();
		});

		$('.nav-bottom__item--change-ref').on('click', function(e){
			$('.change-ref').toggleClass('is-active');
			$('.add-item.is-active').removeClass('is-active');
			e.preventDefault();
		});

		$('.list').on('touchstart', '.list__item', function(){
			$('.list__item-touch').removeClass('list__item-touch');
			$(this).addClass('list__item-touch');
		});

		$('.list').on('click', '.list__item--remove', function(){

			var $item = $(this).parents('.list__item');
			$item.slideUp(550, function(){
				$item.remove();
			});
			
		});

	});
	</script>

	<nav class="nav-top">
		<h2 class="nav-top__label"></h2>
		<div class="nav-top__hamburger"></div>
		<ul class="nav-top__menu"></ul>
	</nav>
	
	<form class="add-item">
		
		<input type="text" class="add-item__input" />
		<div class="add-item__actions">
			<a href="#" class="add-item__button add-item__cancel"><i class="fa fa-times"></i></a>
			<a href="#" class="add-item__button add-item__add"><i class="fa fa-plus"></i></a>
		</div>
		
	</form>
	
	<section class="content-container">
	
		<!--content will be generated here-->
		
	</section>
	
	<form class="change-ref">
		<div class="change-ref__book change-ref__dropdown" data-type="books">
			<div class="change-ref__dropdown--label"></div>
			<div class="change-ref__dropdown--options"></div>
			<input type="hidden" />
		</div>
		<div class="change-ref__chapter change-ref__dropdown" data-type="chapters">
			<div class="change-ref__dropdown--label"></div>
			<div class="change-ref__dropdown--options"></div>
			<input type="hidden" />
		</div>
		<ol class="change-ref__verses">
		</ol>
	</form>

	<nav class="nav-bottom">
		<ul class="nav-bottom__list">
			<!--<li class="nav-bottom__item"><a href="#">Home</a></li>-->
			<li class="nav-bottom__item nav-bottom__item--change-ref"><a href="#">Change Reference</a></li>
			<li class="nav-bottom__item nav-bottom__item--add"><a href="#">Add</a></li>
			<!--<li class="nav-bottom__item"><a href="#">Settings</a></li>-->
		</ul>
	</nav>

@stop