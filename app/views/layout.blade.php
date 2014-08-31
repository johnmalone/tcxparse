<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TCXParse</title>

    <!-- Bootstrap core CSS -->
    <link href="<?PHP echo asset('css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?PHP echo asset('css/navbar.css')?>" rel="stylesheet">
    <link href="<?PHP echo asset('css/progressBar.css')?>" rel="stylesheet">

  </head>

  <body>


      <!-- S0tatic navbar -->


<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">TCX Parse</a>
    </div>
    <div class="navbar-collapse collapse" id="searchbar">

      <ul class="nav navbar-nav navbar-left">
        <li><a href="/">Home</a></li>
        <li><a href="upload">Upload</a></li>
      </ul>
	@if(Auth::check())

      		<ul class="nav navbar-nav navbar-right">
        		<li><a href="{{ URL::to('logout') }}">Logout</a>
      		</ul>

	@else
 		{{ Form::open(array('url' => 'login',  'class'=>'navbar-form', 'role' => 'form', 'required', 'autofocus' ) )}}
        	 <div class="form-group navbar-right" >
                  <div class="input-group">
 	   	   <div class="form-group">
              		<input name="email" type="email" placeholder="Email" class="form-control">
              		<input name="password" type="password" placeholder="Password" class="form-control">
            	   </div>
            	   <button type="submit" class="btn btn-success">Sign in</button>
          	  </div>
        	 </div>
      		{{Form::close()}}
      @endif

    </div><!--/.nav-collapse -->
    </div><!--/.nav-collapse -->
    </div><!--/.nav-collapse -->

      <!-- Main component for a primary marketing message or call to action -->
      <div class="container">
      <p>&nbsp;</p> <!-- figure out later why i need this. without it text begins right under formelements and so first line of content is obscured by background-->
		@yield('content')
      </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?PHP echo asset('js/jquery-1.11.1.min.js');?>"></script>
    <script src="<?PHP echo asset('js/bootstrap.min.js');?>"></script>
<?PHP
	if (Auth::check())
		echo '<script src="' . asset('js/progressBar.js') . '"></script>';
?>
	
  </body>
</html>

