<?php
	/*
	 * This is the admin portion of the assignment. It requires validation for access. Here each row
	 * in the cms_pages database is displayed and can be edited (where the post can be updated or deleted)
	 * or set to the home page.
	 *
	 * The post time is displayed as a newly formatted datetime object
	 * with the timezone set to Winnipeg.
	 *
	 * This page requires authentication.
	 */
	
	date_default_timezone_set('UTC');
    
    require 'authenticate.php';

	require('connect.php');
    
    $sql_query = "SELECT * FROM cms_pages";
    $result = $db->query($sql_query);
	
	function date_conversion($input_date)
	{
		$date = new DateTime($input_date);
		$date->setTimeZone(new DateTimeZone('America/Winnipeg'));
		return date_format($date, "F j, Y, h:i A");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin Page</title>
		<link rel="stylesheet" type="text/css" href="blog.css">
	</head>
	<body>
		<section id="container">
			<header>
				<a href="index.php"><img src="images/blogtitle.png" alt="blogimage" /></a>
				<a href="index.php"><h1>Hero's Journal</h1></a>
                <a class="pagelinks" href="create.php">Make New Page</a>
			</header>
			<section class="post_section">
				<h2>All Pages</h2>
                <?php foreach($result as $page) : ?>
					<?php if($page['permalink'] == "") : ?>
						<a href="/Assignment6/"><h3><?= $page['title'] ?></h3></a>
                        <a class="edit" href="edit.php">Edit</a>
					<?php else : ?>
						<a href="<?= $page['permalink'] ?>"><h3><?= $page['title'] ?></h3></a>
                        <a class="edit" href="edit.php?p=<?= $page['permalink'] ?>">Edit</a>
                    <?php endif ?>
                    <a class="edit" href="homepage.php?p=<?= $page['permalink'] ?>">Make Homepage</a>
                    <h4>Created at: <?php echo date_conversion($page['created_at']) ?></h4>
                    <h4>Updated at: <?php echo date_conversion($page['updated_at']) ?></h4>
				<?php endforeach ?>
			</section>
		</section>
	</body>
</html>