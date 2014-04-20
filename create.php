<?php
	/*
	 * HTML5 page that requires user authentication to access.
	 *
	 * Uses a form with inputs for title, content and permalink to write a new page
	 * to the database, using the post method on the insert_update_delete
	 * script.
	 * 
	 */
    require 'authenticate.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Write a new page</title>
		<link rel="stylesheet" type="text/css" href="blog.css">
		<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
		<script>
	       tinymce.init({selector:'textarea'});
		</script>
	</head>
	<body>
		<section id="container">
			<header>
				<a href="/Assignment6/"><img src="images/blogtitle.png" alt="blogimage" /></a>
				<a href="/Assignment6/"><h1>Hero's Journal</h1></a>
			</header>
			<section class="post_section">
				<form action="insert_update_delete.php" method="post">
					<label for="title">Title</label>
					<br />
					<input id="title" type="text" name="title" />
					<br />
					<label for="content">Content</label>
					<br />
					<textarea id="content" name="content" rows="5" cols="50"></textarea>
					<br />
					<label for="permalink">Permalink</label>
					<br />
					<input id="permalink" type="text" name="permalink" />
					<br /><br />
					<button type="submit" name="insert" >Make new page</button>
				</form>
			</section>
		</section>
	</body>
</html>