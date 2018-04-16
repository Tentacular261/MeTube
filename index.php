<!DOCTYPE html>
<html>

<?php
include_once "database.php";

include_once "navbar.php";

$db = new DatabaseConnection();

$user = (empty($_SESSION['username'])) ? "" : $db->conn->real_escape_string($_SESSION['username']);
?>

    <head>
        <title>Team14 MeTube</title>
		<link rel="shortcut icon" href="assets/metubeIcon.png" type="image/x-icon">
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
						<form id="searchform" method="get" action="index.php" onsubmit="Invertchecks(this); return false;">   <!-- TODO: CHANGE TO GIVE RESULTS -->
							<input type="text" placeholder="Search" name="keywords">

							<!-- Advanced Search Collapsible Menu -->
							<div class="advSearchCollapse">
								<a class="advSearchCollapse" href="javascript:toggleAdvSearch()">Filter</a>
							</div>
								<div class="advSearchContent" id="advSearchContent">
									<input type="text" placeholder="Limit To User" name="user">
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
									<?php if (!empty($_SESSION['username'])) { ?>
									<select id="lists" name="lists" />
										<option value="" disabled selected>Filter by List</option>
										<?php
										$lists = $db->custom_sql("SELECT DISTINCT list FROM playlists WHERE user='$user'");
										while ($list = $lists->fetch_array()) {
											$lname = $list['list'];
											echo "<option value=\"$lname\">$lname</option>\n";
										}
									}
									?>
									<!-- TODO: SEARCH BY FILE TYPE -->
									File Type: </br>
									<input type="checkbox" name="image" checked>Images
									<input type="checkbox" name="video" checked>Video
									<input type="checkbox" name="audio" checked>Audio <br/>
									Privacy: </br> 
									<input type="checkbox" name="public" checked>Public               
									<input type="checkbox" name="private" checked>Private
									<input type="checkbox" name="friend" checked>Friends <br/></br>

									Uploaded: </br>
									<input type="date" name="startdate">
									<input type="date" name="enddate">

									<!-- TODO: NEW ACTION FOR RESET -->
									<input type="submit" name="reset" value="Reset" />
								</div>

							<!-- TODO: NEW ACTION FOR SEARCH-->
							<input type="submit" name="search" value="Search" />

						</form>
					<script>
						function Invertchecks(obj) { // oh my god it finally worked!
							for(var i=0; i<obj.length; i++) {
								obj.elements[i].checked=!obj.elements[i].checked;
							}
							obj.submit();
						}
					</script>
                </div>
            </div>

            <!-- Media Container -->
            <div class="media-col">
                <?php
				$post_count = 25;

				$page_number = 1;
				if (!empty($_GET['pg']) && ctype_digit($_GET['pg']))
					$page_number = ((int)$_GET['pg']);
				$query_offset = $post_count * ($page_number-1);
				
				// This first query set are the queries that returns every post this user is capable of seeing
				$MAIN_QUERY = "";
				if (empty($_SESSION['username']))
					$MAIN_QUERY = "SELECT id,file,type,title,uploaded_by FROM media WHERE privacy='public'";
				else {
					$user = $db->conn->real_escape_string($_SESSION['username']);
					$MAIN_QUERY = "SELECT id,file,type,title,uploaded_by FROM media LEFT JOIN (SELECT * FROM friends WHERE friend='$user') AS fre ON media.uploaded_by=fre.user WHERE (privacy='public' OR (privacy IN ('private','friend') AND uploaded_by='$user') OR (privacy='friend' AND friend='$user'))";
				}

				if (!empty($_GET['keywords'])) { // select posts that have any of the keywords
					// this needs to ba a lambda function to avoid warnings
					$keywords_arr = array_map(function ($temp) use ($db) { return $db->conn->real_escape_string($temp); },explode(" ",$_GET['keywords']));
					$keywords_str = "";
					foreach ($keywords_arr as $word) if (strlen($word) < 30) // build the list of keywords we are looking for
						$keywords_str .= "'$word',";
					$keywords_str = rtrim($keywords_str,",");

					// TODO: Add the search functionality
					$MAIN_QUERY .= " AND EXISTS (SELECT * FROM keywords WHERE (keyword IN ($keywords_str)) AND keywords.media_id=media.id)";
				}

				if (!empty($_GET['user'])) { // select posts by this user
					$un = $db->conn->real_escape_string($_GET['user']);
					$MAIN_QUERY .= " AND uploaded_by='$un'";
				}

				if (!empty($_GET['category'])) { // select posts in this category
					$un = $db->conn->real_escape_string($_GET['category']);
					$MAIN_QUERY .= " AND category='$un'";
				}

				if (!empty($_GET['lists'])) { // select posts in this category
					$list = $db->conn->real_escape_string($_GET['lists']);
					$MAIN_QUERY .= " AND EXISTS (SELECT * FROM playlists WHERE user='$user' AND list='$list' AND playlists.media_id=media.id)";
				}

				if (!empty($_GET['image'])) {
					$MAIN_QUERY .= " AND type<>'image'";
				}

				if (!empty($_GET['video'])) {
					$MAIN_QUERY .= " AND type<>'video'";
				}

				if (!empty($_GET['audio'])) {
					$MAIN_QUERY .= " AND type<>'audio'";
				}

				if (!empty($_GET['public'])) {
					$MAIN_QUERY .= " AND privacy<>'public'";
				}

				if (!empty($_GET['friend'])) {
					$MAIN_QUERY .= " AND privacy<>'friend'";
				}

				if (!empty($_GET['private'])) {
					$MAIN_QUERY .= " AND privacy<>'private'";
				}

				if (!empty($_GET['startdate'])) {
					$tms = strtotime($_GET['startdate']);
					$MAIN_QUERY .= " AND date>='$tms'";
				}

				if (!empty($_GET['enddate'])) {
					$tms = strtotime($_GET['enddate']);
					$MAIN_QUERY .= " AND date<='$tms'";
				}
				
				// Add the result limit
				$MAIN_QUERY .= " ORDER BY date DESC LIMIT $post_count OFFSET $query_offset;";

				// allow the return of the total number of results without reciving all of them
				$MAIN_QUERY = substr_replace($MAIN_QUERY," SQL_CALC_FOUND_ROWS",6,0);
				$GET_TOTAL = "SELECT FOUND_ROWS();";

				
				$result = $db->custom_sql($MAIN_QUERY);
				//echo "<br>".$MAIN_QUERY."<br>".$db->conn->error."<br>";
				$rowcount = $db->custom_sql($GET_TOTAL)->fetch_array()[0];
				
				echo "Displaying results ".($post_count*$page_number-($post_count-1))."-".min($post_count*$page_number,$rowcount)." of ".$rowcount."<br>";
				//echo "<pre>".var_dump($_GET)."</pre><br>";

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
