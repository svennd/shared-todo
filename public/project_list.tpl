<br/>
<div class="span3 offset1">
<div class="well well-small" >	
<ul class="nav nav-list">
<li id="head" class="nav-header head">Projects :</li>
<?php
	if(!empty($projects)) 
	{
		foreach ($projects as $project)
		{
			echo "<li " . (($project['id'] == $pid) ? 'class="active"' : '') . "><a href='?project_id=" . $project['id'] . "'>". $project['name'] ."</a></b></li>";
		}
	}
?>
</ul>
<br/>
<form action="?add_project" method="post" id="add_project" name="add_project">
	<div class="input-append">
	  <input class="input-medium" id="project_name" name="project_name" type="text">
	  <button class="btn" type="submit" name="submit">Add</button>
	</div>
</form>
</div>
todo : search
</div>