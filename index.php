<?php
	/*
	 * The index page uses $_GET['p'] to determine which page is to be
	 * displayed. All pages are linked to in the header area using a
	 * for-loop on all rows resulting from a query on the cms_pages database.
	 *
	 * Permalinks are written in a very user friendly manner allowing for spaces.
	 * .htaccess is written so routing is set to the proper page.
	 *
	 * The post time is displayed as a newly formatted datetime object
	 * with the timezone set to Winnipeg.
	 */
	
	date_default_timezone_set('UTC');

	require('connect.php');
    
    $sql_query = "SELECT * FROM cms_pages ORDER BY created_at DESC";
    $result = $db->query($sql_query);
	
	//Holds the value of the page row if the p is set or not.
	//Is the home page if p is set with a 0 length string
	$this_page;
	
	if(isset($_GET['p']))
	{
		$perma_name = $_GET['p'];
		foreach($result as $page)
		{
			if($page['permalink'] == $perma_name)
			{
				$this_page = $page;
			}
		}
	}
	else
	{
		foreach($result as $page)
		{
			if($page['permalink'] == "")
			{
				$this_page = $page;
			}
		}
	}

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
		<title><?= $this_page['title'] ?></title>
		<link rel="stylesheet" type="text/css" href="blog.css">
	</head>
	<body>
		<section id="container">
			<header>
				<a href="/Assignment6/"><img src="images/blogtitle.png" alt="blogimage" /></a>
				<a href="/Assignment6/"><h1>Hero's Journal</h1></a>
				<?php foreach($result as $page) : ?>
					<?php if($page['permalink'] == "") : ?>
						<a class="pagelinks" href="/Assignment6/"><?= $page['title'] ?></a>
					<?php else : ?>
						<a class="pagelinks" href="/Assignment6/<?= $page['permalink'] ?>"><?= $page['title'] ?></a>
					<?php endif ?>
				<?php endforeach ?>
			</header>
			<section class="post_section">
				<h2><?= $this_page['title'] ?></h2>
					<section class="post">
							<?= $this_page['content'] ?>
					</section>
					<h6>Created at: <?php echo date_conversion($this_page['created_at']); ?></h6>
					<h6>Updated at: <?php echo date_conversion($this_page['updated_at']); ?></h6>
			</section>
			<footer>
				<a class="pagelinks" href="admin.php">Admin</a>
			</footer>
		</section>
	</body>
</html>