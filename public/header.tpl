<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl-be">
<head>

	<!-- title allong with other values can be set as part of page_info in the initialisation of the page -->
	<title>
		<?php 
			if (isset($page_info->title)) 
			{
				echo $page_info->title;
			} 
			else
			{
				echo 'No Title';
			}  
		?>
	</title>
	
	<!-- bootstrap http://twitter.github.io/bootstrap/ -->
	<link href="<?php echo $page_info->path; ?>public/bootstrap/bootstrap.min.css" rel="stylesheet" />
	<script src="<?php echo $page_info->path; ?>public/bootstrap/jquery.min.js"></script>
	<script src="<?php echo $page_info->path; ?>public/bootstrap/jquery_ui.min.js"></script>
	<script src="<?php echo $page_info->path; ?>public/bootstrap/jeditable.min.js"></script>
	<script src="<?php echo $page_info->path; ?>public/bootstrap/bootstrap.min.js"></script>
	
	<!-- sortable -->
	<script>
	  $(function() {
		 $( "#sortable" ).sortable({
				items : '> li:not(.head)',
				update : function () {
					var neworder = new Array();
					var pid = $('input#pid').val();
					$('#sortable li').each(function() {    
						//get the id
						var id  = $(this).attr("id");
						//push the object into the array
						neworder.push(id);
					});
					$(".load_bar").show();
					$.post("save_list.php",{'neworder': neworder, 'pid': pid},function(data){
						$(".load_bar").hide();
					});

				}
			});
		/* not yet implemented */
		// $('.edit').editable('dummy.php', { cssclass : 'full-width'	});
		 
		 /* test test test */
		// $('.todo_content').editable('save_title.php', { rows:10, type: 'textarea',  submit : 'OK', cssclass : '' });

		
		$(".edit").click(function () {
			var text = $(this).text();
			$(this).replaceWith( "<input type='text' name='title' class='full-width' value='" + text + "' />" );
			$(".btn").show();
		});
		
		$(".todo_content").click(function () {
			var text = $(this).text();
			if (text == "Click to edit") { text = ''; }
			$(this).replaceWith( "<textarea name='content' class='full-width'>" + text + "</textarea>" );
			$(".btn").show();
		});
		
		$('#typeahead').typeahead({
        source: function (query, process) {
            return $.ajax({
                url: 'get_user_list.php',
                type: 'post',
                data: { query: query },
                dataType: 'json',
                success: function (jsonResult) {
                    return typeof jsonResult == 'undefined' ? false : process(jsonResult);
                }
            });
        }
    });
	
	  });


	</script>
	
	<style>
	input.full-width {
		box-sizing: border-box;
		width: 100%;
	}
	.full-width textarea {
		box-sizing: border-box;
		width: 100%;
	}
	.full-width {
		box-sizing: border-box;
		width: 100%;
	}
	body {
	  padding-top: 60px;
	}
	@media (max-width: 979px) {
	  body {
		padding-top: 0px;
	  }
	}
	</style>

	<!-- needed for dynamic change of scale for different type of screens -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container">
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
	  <a class="brand" href="index.php">Shared ToDo</a>
	  <div class="nav-collapse collapse">
		<ul class="nav">
		  <li class="active"><a href="index.php">Home</a></li>
		  <?php if ($logged_in) : ?>
		  <li><a href="ucp.php?byebye">Logout</a></li>
		  <?php endif; ?>
		</ul>
		  <?php if (!$logged_in) : ?>
		<form action="ucp.php?log&data" method="POST" class="navbar-form pull-right">
		  <input class="span2" name="username" type="text" placeholder="Username">
		  <input class="span2" name="password" type="password" placeholder="Password">
		  <button type="submit" class="btn">Sign in</button>
		</form>
			<?php else : ?>
			<div class="navbar-form pull-right navbar-text">
			<?php echo $user_name; ?>			
			</div>
			<?php endif; ?>
	  </div><!--/.nav-collapse -->
	</div>
  </div>
</div>
	
    <div class="container">
		<div class="row">
	