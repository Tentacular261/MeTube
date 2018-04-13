<!DOCTYPE html>
<html>

<?php
// these two lines are needed at the start of each page
session_save_path("session");
session_start();

include_once "database.php";

include_once "navbar.php";
?>

    <head>
        <title>Team14 MeTube</title>
		<link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="css/browsing.css">
    </head>

    <body>

        <!-- Header Content -->
        <div class="header">
            <img src="assets/metube.png" alt="metubeIcon">
        </div>

		  <!-- Index Page Content -->
        <div class="flex-container">
            <!-- Search Bar -->
            <div class="search-col">
                <div class="main-search">
						<form action="/action_page.php">   <!-- TODO: CHANGE TO GIVE RESULTS -->
							<input type="text" placeholder="Search" name="search" required>

							<!-- Advanced Search Collapsible Menu -->
							<div class="advSearchCollapse">
								<a class="advSearchCollapse" href="javascript:toggleAdvSearch()">Filter</a>
							</div>
								<div class="advSearchContent" id="advSearchContent">
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
									File Type: </br>
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

									Uploaded: </br>
									<input type="date" name="date">
									<input type="date" name="date">

									<!-- TODO: NEW ACTION FOR RESET -->
									<input type="submit" name="reset" value="Reset" />
								</div>

							<!-- TODO: NEW ACTION FOR SEARCH-->
							<input type="submit" name="search" value="Search" />

                  </form>
                </div>
            </div>

            <!-- Media Container -->
            <div class="media-col">
                <?php
				$db = new DatabaseConnection();

				$post_count = 25;

				$page_number = 1;
				if (!empty($_GET['pg']) && ctype_digit($_GET['pg']))
					$page_number = ((int)$_GET['pg']);
				$query_offset = $post_count * ($page_number-1);
				
				$MAIN_QUERY = "";
				if (empty($_SESSION['username']))
					$MAIN_QUERY = "SELECT SQL_CALC_FOUND_ROWS id,file,type,title,uploaded_by FROM media WHERE privacy='public'";
				else {
					$user = $db->conn->real_escape_string($_SESSION['username']);
					$MAIN_QUERY = "SELECT SQL_CALC_FOUND_ROWS id,file,type,title,uploaded_by FROM media LEFT JOIN (SELECT * FROM friends WHERE friend='$user') AS fre ON media.uploaded_by=fre.user WHERE privacy='public' OR (privacy IN ('private','friend') AND uploaded_by='$user') OR (privacy='friend' AND friend='$user')";
				}

				$MID_QUERY = ""; // TODO: Add the search functionality
				
				$POST_QUERY = " ORDER BY date DESC LIMIT $post_count OFFSET $query_offset;";

				$GET_TOTAL = "SELECT FOUND_ROWS();";
				// TODO: Allow paging of the results
				
				$result = $db->custom_sql($MAIN_QUERY.$MID_QUERY.$POST_QUERY);
				$rowcount = $db->custom_sql($GET_TOTAL)->fetch_array()[0];

				echo "Displaying results ".($post_count*$page_number-($post_count-1))."-".min($post_count*$page_number,$rowcount)." of ".$rowcount."<br>";

                while ($row = $result->fetch_array()) {
					$id = $row['id'];
					$title = $row['title'];
					$thumb = ($row['type'] === "image")
								? $row['file']
								: (($row['type'] === "video")
									? preg_replace('/.[^.]*$/', '', $row['file']).".png"
									: "../../assets/AUDIOTHING.png");
					
                    echo "\n<a href=\"post.php?id="
                        .$id
                        ."\"><img class=\"item\" src=\"media/thumb/"
                        .$thumb
                        ."\" alt=\""
                        .$title
                        ."\"></a>";
				}
				
				$_GET['pg'] = max($page_number-1,1);
				echo "<p><a href=\"index.php?".http_build_query($_GET)."\">&#x25C0;</a>";

				echo " PG $page_number ";

				$_GET['pg'] = min($page_number+1,ceil($rowcount/$post_count));
				echo "<a href=\"index.php?".http_build_query($_GET)."\">&#x25B6;</a></p>";
				?>
            </div>
        </div>

        <!-- Footer Content -->
        <div class="footer">
            <h6><b>CPSC 4620-001 Spring 2018</b><br><i>Micah Johnson, Zackary Sullivan, Sadie Sweetman</i></h6>
        </div>

		  <script>
			/* Script to collapse advanced search menu */  
			function toggleAdvSearch() {
				var content = document.getElementById("advSearchContent");
				if (content.style.display === "block") {
					content.style.display = "none"; 
				} 
				else {
					content.style.display = "block";
				}
			}
			</script>  
			
    </body>
</html>
