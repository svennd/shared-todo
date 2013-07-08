<div class="span12">
<div class="span4">
Folders / Projects & search
<ul>
<?php
	if(!empty($projects)) 
	{
		foreach ($projects as $project)
		{
			if($project['id'] == $pid)
			{
				echo "<li><b><a href='?project_id=" . $project['id'] . "'>". $project['name'] ."</a></b></li>";
			}
			else
			{
				echo "<li><a href='?project_id=" . $project['id'] . "'>". $project['name'] ."</a></li>";
			}
		}
	}
?>
</ul>
<form action="?add_project" method="post" id="add_project" name="add_project">
	<input type="text" name="project_name" value="" />
	<button type="submit" class="btn">add project</button>
</form>
</div>
<div class="span4">
Todo List
<?php if (is_numeric($pid)) : ?>
<input type="hidden" id="pid" name="pid" value="<?php echo $pid; ?>" />

<ul id="sortable" class="unstyled">
<?php
	if( $todos ) 
	{
		foreach ($todos as $todo) {
			if($todo['id'] == $tid)
			{
				echo "<li id='" . $todo['id'] . "'><i class='icon-resize-vertical'></i><b><a href='?todo_id=" . $todo['id'] . "'>". $todo['title'] ."</a></b></li>";
			}
			else
			{
				echo "<li id='" . $todo['id'] . "'><i class='icon-resize-vertical'></i><a href='?todo_id=" . $todo['id'] . "'>". $todo['title'] ."</a></li>";
			}
		}
	}
?>
</ul>
<form action="?add_todo" method="post" id="add_todo" name="add_todo">
	<input type="text" name="todo_title"  value="" />
	<button type="submit" class="btn">add todo</button>
</form>
<?php endif; ?>
</div>
<div class="span3">
Content & comment, share 
<?php if (is_numeric($tid)) : ?>
<form action="?change_topic&todo_id=<?php echo $topic['id']; ?>" method="post">
<?php
	echo "<h3>" . $topic['title'] . "</h3>";
	echo "<textarea name='content'>" . ((isset($topic['content'])) ? $topic['content'] : '' ) . "</textarea>";
?>
<button type="submit" class="btn">save</button>
</form>
<?php endif; ?>
</div>
</div>