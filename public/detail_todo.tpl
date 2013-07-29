<div class="span4">
<?php if (is_numeric($tid)) : ?>
<div class="well well-small" >	
<form action="?change_topic&todo_id=<?php echo $topic['id']; ?>" method="post">
<?php
	echo "<h3 id='title' class='edit'>" . $topic['title'] . "</h3>";
	echo "<p id='todo_id_" . $topic['id'] . "' class='todo_content'>" . ((isset($topic['content']) && $topic['content'] != '') ? $topic['content'] : "Click to edit" ) . "</p>";
?>
<br/>
<button type="submit" class="btn" style="display:none;">save</button>
</form>
</div>
<?php endif; ?>
</div>