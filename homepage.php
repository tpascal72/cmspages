<?php
	/*
	 * Homepage.php requires the user define a new pagelink for the current homepage (identified by
	 * a permalink of "") before updating the selected page to the homepage.
	 *
	 * Various checks are made to ensure data integrity on database updates as the permalink must be
	 * unique to the database.
	 *
	 * This page requires authentication.
	 */

    require 'authenticate.php';
    require('connect.php');
    
    if(isset($_GET['p']))
    {
        if(strlen($_GET['p']) < 1)
        {
            header('Location: .');
            exit(0);
        }
        
        $permalink = $_GET['p'];
        
        $sql_query = "SELECT * FROM cms_pages WHERE permalink = '' LIMIT 1";
        $result = $db->query($sql_query);
        
        if($result->num_rows > 0)
        {
            $row = $result->fetch_row();
            
            $page_title = $row[1];
        }
        else
        {
            echo "No post matching the permalink found";
            exit(0);
        }
    }
    
    if(isset($_POST['homepage']) && strlen($_POST['homepage']) > 0)
    {
        $page_permalink = $_POST['homepage'];
        $new_permalink = "";
        
        if(isset($_POST['permalink']) && strlen($_POST['permalink']) > 0)
        {
            $new_permalink = $_POST['permalink'];
        }
        else
        {
            echo "Permalink must be entered.";
            exit(0);
        }
		
		if(preg_match("/\\s/", $new_permalink))
		{
			echo "Permalink is not allowed to have any spaces in it";
			exit(0);
		}
		
        //Checking to see if the new permalink already exists on the database
        $sql_query = "SELECT * FROM cms_pages WHERE permalink = '$new_permalink' LIMIT 1";
        $result = $db->query($sql_query);
        
        if($result->num_rows > 0)
        {
            echo "Permalink '$new_permalink' already exists on the database";
            exit(0);
        }
        
        $new_permalink = $db->real_escape_string($new_permalink);
        $page_permalink = $db->real_escape_string($page_permalink);
        //Changing the current homepage before changing the current homepage
        $query  = "UPDATE cms_pages SET permalink='$new_permalink' WHERE permalink='' LIMIT 1";

        $new_permalink_result = $db->query($query);
        //Updating new homepage permalink
        $query  = "UPDATE cms_pages SET permalink='' WHERE permalink='$page_permalink' LIMIT 1";

        $homepage_permalink_result = $db->query($query);

        if($new_permalink_result && $homepage_permalink_result)
        {
            header('Location: .');
            exit(0);
        }
        else
        {
            printf("Errormessage: %s\n", mysqli_error($db));
            echo "Could not update";
            exit(0);
        }
    }
    
    if(!isset($_POST['homepage']) && !isset($_GET['p']))
    {
        header('Location: .');
        exit(0);
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Change homepage</title>
		<link rel="stylesheet" type="text/css" href="blog.css">
	</head>
	<body>
		<section id="container">
			<header>
				<a href="/Assignment6/"><img src="images/blogtitle.png" alt="blogimage" /></a>
				<a href="/Assignment6/"><h1>Hero's Journal</h1></a>
			</header>
            <h2>Page '<?= $page_title ?>' needs a new permalink defined before homepage change can be made.</h2>
			<section class="post_section">
				<form action="homepage.php" method="post">
                    <label for="permalink">Enter new permalink:</label>
                    <br />
                    <input id="permalink" type="text" name="permalink" />
                    <br /><br />
                    <button type="submit" name="homepage" value="<?= $permalink ?>">Change homepage</button>
                </form>
			</section>
		</section>
	</body>
</html>