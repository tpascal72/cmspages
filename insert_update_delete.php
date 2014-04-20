<?php
    /* The following script determines which post value has been set (insert, update or delete)
     * Using slightly different code for each outcome after connecting to the database.
     *
     * All three use SQL queries to accomplish their goals
     *
     * update: Using layered conditioning, values are checked to ensure they have proper data
     * before being used to update the cms_pages table. If any errors are found an appropriate
     * message is display.
     *
     * delete: The blog id is first verified to be numeric (else an error is displayed)
     * then the corresponding post is deleted from the database
     *
     * insert: Checks for values in title and permalink and displays an error if there is
     * none. If there is content in both a new page is inserted into the database
     *
     */

    require('connect.php');
    
    if(isset($_POST['update']))
    {
        $page_id = $_POST['update'];
        $page_title = $_POST['title'];
        $page_content = $_POST['content'];
        $page_permalink = $_POST['permalink'];
        $original_permalink = $_POST['original_permalink'];
        
        if(!is_numeric($page_id) || !(strlen($page_title) > 0 && strlen($page_title) < 101)
            || (strlen($page_permalink) < 1 && $original_permalink != "") || preg_match("/\\s/", $page_permalink))
        {
            
            if(!is_numeric($page_id))
            {
                header('Location: .');
                exit(0);
            }
            
            if(!(strlen($page_title) > 0 && strlen($page_title) < 101))
            {
                echo "Title must be between 1 and 100 characters (inclusive)<br />";
            }
            if(strlen($page_permalink) < 1 || preg_match("/\\s/", $page_permalink))
            {
                echo "Permalink must have at least 1 character and cannot have spaces";
            }
            
            exit(0);
        }
        
        $page_title = $db->real_escape_string($page_title);
        $page_content = $db->real_escape_string($page_content);
        $page_permalink = $db->real_escape_string($page_permalink);
        
        //Checking to see if the new permalink already exists on the database
        //Without including the page that is going to be updated
        $sql_query = "SELECT * FROM cms_pages WHERE permalink = '$page_permalink' AND id!='$page_id' LIMIT 1";
        $result = $db->query($sql_query);
        
        if($result->num_rows > 0)
        {
            echo "Permalink '$page_permalink' already exists on the database";
            exit(0);
        }
        
        $query  = "UPDATE cms_pages SET title='$page_title', content='$page_content', permalink='$page_permalink' WHERE id='$page_id' LIMIT 1";

        $result = $db->query($query);

        if($result)
        {
            header('Location: .');
        }
        else
        {
            printf("Errormessage: %s\n", mysqli_error($db));
            echo "Could not update post";
        }
    }
    elseif(isset($_POST['delete']))
    {
        $page_id = $_POST['delete'];
        
        if(!is_numeric($page_id))
        {
            header('Location: .');
            exit(0);
        }
        
        $query  = "DELETE FROM cms_pages WHERE id='$page_id'";
        
        $result = $db->query($query);
        
        if($result)
        {
            header('Location: .');
        }
        else
        {
            echo "There was an error deleting the post";
        }
    }
    elseif(isset($_POST['insert']))
    {
        $page_title = $_POST['title'];
        $page_content = $_POST['content'];
        $page_permalink = $_POST['permalink'];
        
        
        if(!(strlen($page_title) > 0 && strlen($page_title) < 101)
           || strlen($page_permalink) < 1 || preg_match("/\\s/", $page_permalink))
        {   
            if(!(strlen($page_title) > 0 && strlen($page_title) < 101))
            {
                echo "Title must be between 1 and 100 characters (inclusive)<br />";
            }
            if(strlen($page_permalink) < 1 || preg_match("/\\s/", $page_permalink))
            {
                echo "Permalink must have at least 1 character";
            }
            
            exit(0);
        }
        
        $page_title = $db->real_escape_string($page_title);
        $page_content = $db->real_escape_string($page_content);
        $page_permalink = $db->real_escape_string($page_permalink);
        
        //Checking to see if the new permalink already exists on the database
        $sql_query = "SELECT * FROM cms_pages WHERE permalink = '$page_permalink' LIMIT 1";
        $result = $db->query($sql_query);
        
        if($result->num_rows > 0)
        {
            echo "Permalink '$page_permalink' already exists on the database";
            exit(0);
        }
        
        $query  = "INSERT INTO cms_pages (title, content, created_at, updated_at, permalink) VALUES ('{$page_title}', '{$page_content}', null, null, '{$page_permalink}')";

        $result = $db->query($query);

        if($result)
        {
            header('Location: .');
        }
        else
        {
            echo "Could not insert post";
        }
    }
?>