
 <script>
 	 $(document).ready(function(){
     	<?php if ($message = Session::get('success')) { ?>
     	   toastr.success('<?php echo e($message); ?>', '', {timeOut: 5000})
      	<?php } ?>


      	<?php if ($message = Session::get('error')) { ?>
      	  toastr.error('<?php echo e($message); ?>', '', {timeOut: 5000 })
      	<?php } ?>
      
	 	   <?php if ($message = Session::get('info')) { ?>
        	toastr.info('<?php echo e($message); ?>', '', {timeOut: 5000})
      	<?php } ?>

 		   <?php if ($message = Session::get('warning')) { ?>
        	toastr.warning('<?php echo e($message); ?>', '', {timeOut: 5000})
      	<?php } ?>
  	});
</script><?php /**PATH /var/www/html/resources/views/flash-message.blade.php ENDPATH**/ ?>