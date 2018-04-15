<div id="editpost" class="modal">
    <form class="modal-content animate" method="post" action="user/modifypost.php" enctype="multipart/form-data"><div class="container">
        <input type="hidden" name="return" value="<?php echo $returnto; ?>">
        <input type="hidden" name="media_id" value="<?php echo $id; ?>">
        <span onclick="document.getElementById('editpost').style.display='none'" class="close" title="Close">&times;</span>
        <h1>Edit Post</h1>

        <input type="text" name="title" placeholder="Media Title" value="<?php echo $title; ?>" required/>
        
        <textarea name="description" placeholder="Description" rows="20" style="width: 100%"/><?php echo $description; ?></textarea>

        <?php
        $words = $db->custom_sql("SELECT keyword FROM keywords WHERE media_id='$id'");
        $wordarry = array();
        while ($word = $words->fetch_array())
            array_push($wordarry,$word['keyword']);
        $keys = implode(" ",$wordarry);
        ?>
        <input type="text" name="keywords" placeholder="Keywords" value="<?php echo $keys; ?>"/>
        
        <!-- TODO: NEW CATEGORY -->
        <select id="category" name="category" required/>
            <option value="" disabled>Category</option>
            <option value="entertainment" <?php if ($category == "entertainment") echo "selected"; ?>>Entertainment</option>
            <option value="food" <?php if ($category == "food") echo "selected"; ?>>Food</option>
            <option value="funny" <?php if ($category == "funny") echo "selected"; ?>>Funny</option>
            <option value="gaming" <?php if ($category == "gaming") echo "selected"; ?>>Gaming</option>
            <option value="news" <?php if ($category == "news") echo "selected"; ?>>News & Politics</option>
            <option value="people" <?php if ($category == "people") echo "selected"; ?>>People</option>
            <option value="pets" <?php if ($category == "pets") echo "selected"; ?>>Pets & Animals</option>
            <option value="science" <?php if ($category == "science") echo "selected"; ?>>Science & Tech</option>
            <option value="sports" <?php if ($category == "sports") echo "selected"; ?>>Sports</option>
            <option value="travel" <?php if ($category == "travel") echo "selected"; ?>>Travel & Outdoors</option>
        </select><br>

        <input type="radio" name="privacy" value="public" <?php if ($privacy == "public") echo "checked" ?>>Public               
        <input type="radio" name="privacy" value="friend" <?php if ($privacy == "friend") echo "checked" ?>>Friends 
        <input type="radio" name="privacy" value="private" <?php if ($privacy == "private") echo "checked" ?>>Private <br/>

        <button type="submit" name="modify">Update</button>
    </div></form>
</div>

<script>
    modal_boxes.push(document.getElementById('editpost'));
</script>