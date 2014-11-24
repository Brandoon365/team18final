<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CSCI370</title>
	{{HTML::style('css/fieldSession.css')}}
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1 id="first_header">CSCI 370 - Advanced Software Engineering</h1>
			<hr id="header_hr">
		</div>
		<div class="content">
			@yield('content')
		</div>
		<div class="footer">
			<span>&copy; 2014</span><br>
			<span>Brandon Bosso & Marcus Bermel</span>
		</div>
	</div>
</body>
</html>
