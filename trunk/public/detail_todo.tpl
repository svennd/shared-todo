
<div class="span4">
<div class="well well-small" >	
<?php if (is_numeric($tid)) : ?>
<form action="?change_topic&todo_id=<?php echo $topic['id']; ?>" method="post">
<?php
	echo "<h3 id='title' class='edit'>" . $topic['title'] . "</h3>";
	echo "<textarea name='content'>" . ((isset($topic['content'])) ? $topic['content'] : '' ) . "</textarea>";
?>
<br/>
<button type="submit" class="btn">save</button>
</form>
</div>
<?php endif; ?>
</div>