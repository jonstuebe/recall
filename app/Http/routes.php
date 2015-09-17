<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    
    if(!\Auth::check())
    {
    	return view('auth/login');
    }
    else
    {
    	return view('app');
    }

});

Route::get('auth/login', function(){
	return redirect('');
});

Route::get('auth/facebook', 'Auth\AuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Auth\AuthController@facebookCallback');
Route::get('auth/twitter', 'Auth\AuthController@redirectToTwitter');
Route::get('auth/twitter/callback', 'Auth\AuthController@twitterCallback');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::group([ 'prefix' => 'user', 'middleware' => 'auth' ], function(){

	Route::get('{category_id}/{book}/{chapter}/{verses}/items', function($category_id, $book, $chapter, $verses){
		
		$user = \Auth::user();
		
		$items = \App\Item::where('user_id', $user->id)
		->where('category_id', $category_id)
		->where('book',$book)
		->where('chapter',$chapter)
		// ->where('verses', $verses)
		->get();

		return response()->json($items);
	});

	Route::post('item/add', function(){

		$category_id = Input::get('category_id');
		$book = Input::get('book');
		$chapter = Input::get('chapter');
		$verses = Input::get('verses');
		$content = Input::get('content');

		$user = \Auth::user();
		$item = \App\Item::create([
			'user_id' => $user->id,
			'category_id' => $category_id,
			'book' => $book,
			'chapter' => $chapter,
			'verses' => $verses,
			'content' => $content,
		]);

		if($item)
		{
			return response()->json([ 'success' => true ]);
		}
		else
		{
			return response()->json([ 'success' => false ]);
		}

	});

});


Route::group([ 'prefix' => 'api', 'middleware' => 'auth' ], function(){

	Route::get('books', function(){
		$books = \App\Bible::books();
		return response()->json($books->response);
	});

	Route::get('chapters/{book}', function($book){
		$chapters = \App\Bible::chapters($book);
		return response()->json($chapters->response);
	});

	Route::get('verses/{book}/{chapter}', function($book, $chapter){
		$verses = \App\Bible::verses($book, $chapter);
		return response()->json($verses->response);
	});

});

Route::get('test', function(){

	

});