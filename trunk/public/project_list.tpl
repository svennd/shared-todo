<div class="span4">
<div class="well well-small" >	
<ul class="nav nav-list">
<li id="head" class="nav-header head">Projects :</li>
<?php

if(!empty($projects)) 
{
	foreach ($projects as $project)
	{
		echo "<li " . (($project['id'] == $pid) ? 'class="active"' : '') . ">
				<a href='?project_id=" . $project['id'] . "'>
				<span style='position:inherit;right:40px;'>
				" . ( ($project['shared'] == 1) ? '<i class="icon-share"></i>' : '<i class="icon-"></i>' ) . "
				" . ( ($project['owner'] > 0) ? '<i class="icon-home"></i>' : '<i class="icon-"></i>' ) . "
				</span>
				<span style='position:inherit;left:30px;'>
				". $project['name'] ." 
					</span>

				</a>
			</li>";
	}
}

?>
</ul>
<br/>
<div class="nav-header head">Settings</div>
<a class="btn" href="#setting_mondal" data-toggle="modal"><i class="icon-user"></i> Users</a>
<br/>
<br/>
<div class="nav-header head">Add New</div>
<form action="?add_project" method="post" id="add_project" name="add_project">
	<div class="input-append">
	  <input class="input-medium" id="project_name" name="project_name" type="text">
	  <button class="btn" type="submit" name="submit">Add</button>
	</div>
</form>
</div>
todo : search
</div>