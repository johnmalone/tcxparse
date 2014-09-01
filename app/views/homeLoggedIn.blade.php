@extends('layout')

@section('content')


<div class="container-fluid">

<div class="row">

<div class="col-sm-3 col-md-2 sidebar">
	<div id="uploadProgressbar">
		Processing uploaded file 
		<div class="progress">
			<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
    				<span class="sr-only"></span>
    				<span id="uploadProgressbarContents"></span>
			</div>
			<script>doUploadProgress = true;</script>
		</div>
	</div>

	</div>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header"> TCX Parser </h1>
		<div class="row placeholders">

You're Logged In! 

Fancy dashboard goes here

		</div>
	</div>
</div>
</div>

@stop
