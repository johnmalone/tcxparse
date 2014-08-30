@extends('layout')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="col-md-6 col-md-offset-3">

@if (isset($result) and !  $result)
<p>
	There was a problem parsing your file!
</p>
@endif

{{ Form::open(array('url'=>'upload','files'=>true)) }}
        <h2 class='form-signin-heading'>Upload your TCX files below</h2>

<div class="form-group">
{{ Form::label('file','File',array('id'=>'','class'=>'')) }}
{{ Form::file('file','',array('id'=>'','class'=>'')) }}
</div>
<!-- submit buttons -->
{{ Form::submit('Upload', array('class' => 'btn btn-lg btn-primary btn-block')) }}
		     
{{ Form::close() }}

</div>
</div>
</div>

@stop
