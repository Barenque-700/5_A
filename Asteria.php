<html> 
	<head>
		 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
		<title> Asteria </title>
	</head>
	<body>
	<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid"> <a class="navbar-brand me-auto" href="#"> <img src="logo.png" width="255" height="150" alt="Logo Asteria">
        </a>
        <span id="current-date" class="navbar-brand mb-0 h1">
    </span>
    </div>
</nav>
  </nav>
		<h1> Mega social network pazzurdo per astronomia</h1>
		<div class="p-3 text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-3">
  			Example element with utilities
		</div>
		<br>
		<div class="p-3 text-success-emphasis bg-info-subtle border border-warning-subtle rounded-3"> 
				Header 
		</div>

		<script>
    	const oggi = new Date();
    	const opzioni = { year: 'numeric', month: 'long', day: 'numeric' };
    	const dataFormattata = oggi.toLocaleDateString('it-IT', opzioni);
    	document.getElementById('current-date').textContent = dataFormattata;
	</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
	</body>
</html>