<!DOCTYPE html>

<?php
// these two lines are needed at the start of each page
session_save_path("session");
session_start();

include_once "database.php";

include_once "navbar.php";
?>

<html>
    <head>
        <title>|Team14 MeTube|</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
		  <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/browsing.css">
    </head>
    <body>

        <!-- Header Content -->
        <div class="header">
            <h1>MeTube</h1>
            <p><i>You're going to be offended.</i></p>
        </div>

        <div class="flex-container">
            <!-- Search Bar -->
            <div class="search-col">
                <div class="main-search">
						<form action="/action_page.php">   <!-- TODO: CHANGE TO GIVE RESULTS -->
							<input type="text" placeholder="Search" name="search">
							<select id="category" name="category" />
								<option value="" disabled selected>Filter by Category</option>
								<option value="entertainment">Entertainment</option>
								<option value="food">Food</option>
								<option value="funny">Funny</option>
								<option value="gaming">Gaming</option>
								<option value="news">News & Politics</option>
								<option value="people">People</option>
								<option value="pets">Pets & Animals</option>
								<option value="science">Science & Tech</option>
								<option value="sports">Sports</option>
								<option value="travel">Travel & Outdoors</option> </select>
							<select id="lists" name="lists" />
								<option value="" disabled selected>Filter by List</option>
								<option value="favorites">Favorites </option>
								<option value="haha">Haha (Example) </option>
								<option value="rip">RIP (Example) </option> </select>

							<!-- TODO: SEARCH BY FILE TYPE -->
							File Type</br>
							<input type="checkbox" name="file" value="image" checked>Images
							<input type="checkbox" name="file" value="video" checked>Video
							<input type="checkbox" name="file" value="audio" checked>Audio <br/>
							Rating: </br>
							<input type="checkbox" name="rating" value="1">1
							<input type="checkbox" name="rating" value="2">2
							<input type="checkbox" name="rating" value="3">3 
							<input type="checkbox" name="rating" value="4">4 
							<input type="checkbox" name="rating" value="5">5 </br>
							Privacy: </br>
							<input type="checkbox" name="privacy" value="public" checked>Public               
							<input type="checkbox" name="privacy" value="private" checked>Private
							<input type="checkbox" name="privacy" value="contacts" checked>Friends <br/></br>

							Uploaded Between: </br>
							<input type="date" name="date">And
							<input type="date" name="date">

							<!-- TODO: NEW ACTION FOR SEARCH-->
							<input type="submit" name="search" value="Search" />

							<!-- TODO: NEW ACTION FOR RESET -->
							<input type="submit" name="reset" value="Reset" />
                  </form>
                </div>

                <!-- TODO: Additional Search Stuff -->

            </div>

            <!-- Media Container -->
            <div class="media-col">
                <?php
                $db = new DatabaseConnection();

                // TODO: Allow paging of the results
                $result = $db->custom_sql("SELECT id,file,title,privacy,uploaded_by FROM media ORDER BY date DESC");

                for ($i=0;$i<25;$i++) {
                    do {
                        $rows = $result->fetch_array();
                    } while ($rows != NULL && $rows['privacy'] != "public" && (empty($_SESSION['username']) || $rows['uploaded_by'] != $_SESSION['username']));
                    // TODO: handle seeing friends posts that can be seen once that is added
                    if ($rows==NULL) break;
                    $size = getimagesize("media/".$rows['file']);
                    $imgsizedef = ($size[0] > $size[1]) // specify only the size of the largest dimension of the image
                                    ? "width=\"64\""
                                    : "height=\"64\"";
                    echo "\n<a href=\"post.php?id="
                        .$rows['id']
                        ."\"><img class=\"item\" src=\"media/"
                        .$rows['file']
                        ."\" alt=\""
                        .$rows['title']
                        ."\" "
                        .$imgsizedef
                        ."></a>";
                }
                ?>
                <!-- TODO: Add the paging controls -->
                <p>&#x25C0; PG &#x25B6;</p>
            </div>
        </div>

        <!-- Footer Content -->
        <div class="footer">
            <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan, Sadie Sweetman</i></h6>
        </div>
        
    </body>
</html>
