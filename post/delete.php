<div id="deletepost" class="modal">
    <form class="modal-content animate" method="post" action="user/deletepost.php" enctype="multipart/form-data"><div class="container">
        <input type="hidden" name="return" value="<?php echo $returnto; ?>">
        <input type="hidden" name="media_id" value="<?php echo $id; ?>">
        <span onclick="document.getElementById('editpost').style.display='none'" class="close" title="Close">&times;</span>
        <h1>Delete Post</h1>
        <p>Are you sure you want to delete this post?</p>
        <p>This is permanent.</p>

        <button type="submit" name="DELETE">Confirm Delete</button>
    </div></form>
</div>

<script>
    modal_boxes.push(document.getElementById('deletepost'));
</script>