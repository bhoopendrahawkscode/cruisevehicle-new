
 <script>
 	 $(document).ready(function(){
     	<?php if ($message = Session::get('success')) { ?>
     	   toastr.success('{{ $message }}', '', {timeOut: 5000})
      	<?php } ?>


      	<?php if ($message = Session::get('error')) { ?>
      	  toastr.error('{{ $message }}', '', {timeOut: 5000 })
      	<?php } ?>
      
	 	   <?php if ($message = Session::get('info')) { ?>
        	toastr.info('{{ $message }}', '', {timeOut: 5000})
      	<?php } ?>

 		   <?php if ($message = Session::get('warning')) { ?>
        	toastr.warning('{{ $message }}', '', {timeOut: 5000})
      	<?php } ?>
  	});
</script>