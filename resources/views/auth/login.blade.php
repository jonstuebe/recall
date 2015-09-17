@extends('layouts.master')

@section('content')

	<div class="app-hero">
		<h1 class="app-hero__headline">recall</h1>
		
		<div class="social-logins">
			<a href="/auth/facebook" class="social-logins__facebook social-logins__button">Sign in with Facebook</a>
			<a href="/auth/twitter" class="social-logins__twitter social-logins__button">Sign in with Twitter</a>
		</div>
	</div>

@stop