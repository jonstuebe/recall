<?php 

namespace App;

class Bible {

	private static function request($path)
	{
		$token = 'JnosQMR5Zmdf0qPCEeaaR90zehwMBD6mf0JybEXa';
		$url = 'https://bibles.org/v2/' . $path . '.js';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$token:X");

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	public static function versions()
	{
		$path = 'versions';
		$response = static::request($path);
		return json_decode($response);
	} 

	public static function books()
	{
		$version = 'eng-ESV';
		$path = 'versions/' . $version . '/books';
		$response = static::request($path);
		return json_decode($response);
	}

	public static function chapters($book)
	{
		$version = 'eng-ESV';
		$path = 'books/' . $version . ':' . $book . '/chapters';
		$response = static::request($path);
		return json_decode($response);
	}

	public static function verses($book, $chapter)
	{
		$path = 'chapters/' . $book . '.' . $chapter . '/verses';
		$response = static::request($path);
		return json_decode($response);
	}

}