@extends('layout')

@section('content')


<h1> TCX Parser </h1>

@if (null !== Session::get('result') and Session::get('result') == TRUE)
<p class='bg-success'>
	You're file was uploaded successfully!
</p>
@endif


<div class="pbarContainer"><div class="progressbar"></div></div>

You're Logged In! 

Fancy dashboard goes here

@stop
