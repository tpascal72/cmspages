<?php
	/*
	 * This page requires the user to authenticate before they are granted access
	 *
	 * An individual page is found assuming the GET parameter for permalink
	 * is set and is greater than 0 in string length. Otherwise, the data acquired from the homepage
	 * In both cases data is written to form controls (textbox and textarea).
	 *
	 * The form makes use of the post method calling on the insert_update_delete
	 * script where the two buttons, update or delete, determine the outcome.
	 */

    require 'authenticate.php';
	
	//Since the home page is defined as "", no need to error check values for p.
	$permalink = "";

    if(isset($_GET['p']) && strlen($_GET['p']) > 0)
    {
        $permalink = $_GET['p'];
    }


	require('connect.php');
    
    $sql_query = "SELECT * FROM cms_pages WHERE permalink = '$permalink'";
    $result = $db->query($sql_query);
    
    if($result->num_rows > 0)
    {
        $row = $result->fetch_row();
        
		$page_id = $row[0];
        $page_title = $row[1];
        $page_content = $row[2];
		$page_permalink = $row[5];
    }
    else
    {
        echo "No post matching the permalink found";
        exit(0);
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit <?= $page_title ?></title>
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
					<input id="title" type="text" name="title" value="<?= $page_title ?>" />
					<br />
					<label for="content">Content</label>
					<br />
					<textarea id="content" name="content" rows="5" cols="50"><?= $page_content ?></textarea>
					<br />
					<?php if($page_permalink != "") : ?>
						<label for="permalink">Permalink</label>
						<br />
						<input id="permalink" type="text" name="permalink" value="<?= $page_permalink ?>" />
					<?php else : ?>
						<input class="hide" id="permalink" type="text" name="permalink" value="<?= $page_permalink ?>" readonly/>
					<?php endif ?>
					<input class="hide" id="original_permalink" type="text" name="original_permalink" value="<?= $page_permalink ?>" readonly/>
					<br /><br />
					<button type="submit" name="update" value="<?= $page_id ?>" >Edit page</button>
                    <button type="submit" name="delete" value="<?= $page_id ?>" >Delete page</button>
				</form>
			</section>
		</section>
	</body>
</html>