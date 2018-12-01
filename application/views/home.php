<html>
<head>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" >

	<script src="<?php echo base_url(); ?>js/bootstrap.min.js" ></script>

	<script src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js" ></script>
	<script type="text/javascript">
		function task_add(){
			//alert($('#mytask').val());
			var mtask = $('#mytask').val();
			$.ajax({
				url: "<?php echo base_url('welcome/add_task'); ?>", 
				 type: "POST",
				  data: {task : mtask},
				  dataType: "json",
				success: function(result){
		        	//alert(result);
		        	$('#taskbox').append('<p id="p'+result.id+'""><span>'+ mtask + '</span><i class="text-primary" style="cursor: pointer"  onclick="edit_task('+result.id+')"> edit</i> <i class="text-danger" style="cursor: pointer" onclick="task_delete('+result.id+')">delete</i>'+'</p>');
		        	$('#mytask').val('');
		    }});
		}

		function task_delete(id){
			//alert($('#mytask').val());
			//var mtask = $('#mytask').val();
			$.ajax({
				url: "<?php echo base_url('welcome/delete_task'); ?>", 
				 type: "POST",
				  data: {task_id : id},
				  dataType: "json",
				success: function(result){
					if(result=='deleted'){
						$('#p'+id).remove('');
					}
		        	
		    }});
		}

		function update_task(id){
			$.ajax({
				url: "<?php echo base_url('welcome/update_task'); ?>", 
				 type: "POST",
				  data: {task_id : id, task_name : $("#mytask").val()},
				  dataType: "json",
				success: function(result){
					if(result=='updated'){
						$('#p'+id).children("span").text($("#mytask").val());
						$("#mybtn").val("Ok");
						$("#mybtn").attr("onclick","task_add()");
						$("#p"+id).children("span").css("font-weight","normal");
						$("#mytask").val("");
					}
		        	
		    }});
		}

		function edit_task(id){
			$("p:not(#p"+id+")").children("span").css("font-weight","normal");
			$("#p"+id).children("span").css("font-weight","bold");
			$("#mytask").val( $("#p"+id).children("span").text() );
			$("#mybtn").val("Save");
			$("#mybtn").attr("onclick","update_task("+id+")");
		}
	</script>
</head>
<body class="text-center" >
	<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
      <h5 class="my-0 mr-md-auto font-weight-normal">Furqan Hussain</h5>
      <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="#">Home</a>
      </nav>
      <a class="btn btn-outline-primary" href="<?php echo base_url('welcome/signout');?>">Sign out</a>
    </div>
	<h1><?php echo 'To Do List'; ?></h1>
	<div id="taskbox"> 
		<?php foreach($result as $data){ ?>
			<p id="p<?php  echo $data->id;?>">
				<span><?php  echo $data->task ;?></span>
			<i class="text-primary" style="cursor: pointer" onclick="edit_task(<?php  echo $data->id;?>)">edit</i>
			<i class="text-danger" style="cursor: pointer" onclick="task_delete(<?php  echo $data->id;?>)">delete</i>
			  </p>
		<?php } ?>
	</div>
	


	<input type="input" name="" id="mytask">
	<input value="ADD" name="" type="button" onclick="task_add()" id="mybtn"></form>
</body>
</html>

