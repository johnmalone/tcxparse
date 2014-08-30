@extends('layout')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="col-md-4 col-md-offset-4">
{{ Form::open(array('url' => 'login',  'role' => 'form', 'required', 'autofocus' ) )}}
	<h2 class='form-signin-heading'>Please sign in</h2>

	<!-- if there are login errors, show them here -->
	<p>
		{{ $errors->first('email') }}
		{{ $errors->first('password') }}
	</p>
	
	<div class="form-group">
		{{ Form::label('email', 'Email Address') }}
		{{ Form::email('email', Input::old('email'), array('class'=>'form-control','placeholder' => 'Email address', 'required')) }}
	</div>

	<div class="form-group">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password',  array('class'=>'form-control','placeholder' => 'Password', 'required')) }}
	</div>

	<p>
		{{ Form::submit('Submit!', array('class' => 'btn btn-lg btn-primary btn-block') ) }}
	</p>
	
{{ Form::close() }}
</div>
</div>
</div>
@stop
