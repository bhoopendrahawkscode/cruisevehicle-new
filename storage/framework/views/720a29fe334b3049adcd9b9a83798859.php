<!DOCTYPE html>
<html lang="en">
    <head>
    <?php
        use App\Constants\Constant;
        use App\Services\GeneralService;
        use App\Services\ImageService;
        ?>
          <style>
            :root {
                --primaryColor: <?php echo GeneralService::getSettings('primaryColor'); ?>;
                --secondaryColor: <?php echo GeneralService::getSettings('secondaryColor'); ?>;
            }
        </style>
	    <meta charset="utf-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo e(GeneralService::getSettings('websiteTitle')); ?></title>
        <meta content="2GTHR Admin Panel <?php if($version != ''){ echo $version; } ?>" name="description" />

        <link href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" rel=icon sizes=32x32 type=image/png>
        <link href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" rel=icon sizes=16x16 type=image/png>
        <link href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" rel=apple-touch-icon>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>">

		<script src="https://kit.fontawesome.com/2b392c3eb3.js" integrity="" crossorigin="anonymous"></script>


		<script src="<?php echo e(asset('/assets/js/jquery-3.7.1.min.js')); ?>"></script>
		<script src="<?php echo e(asset('/assets/js/jquery-migrate.min.js')); ?>"></script>

	    <script src="<?php echo e(asset('/assets/js/bootstrap.bundle.min.js')); ?>"></script>

		<script src="<?php echo e(asset ('/assets/js/jquery.validate.min.js')); ?>?id=<?php echo random_int(0,9);  ?>"></script>
		<script src="<?php echo e(asset ('/assets/js/jquery.validate_custom.min.js')); ?>?id=<?php echo random_int(0,9);  ?>"></script>


		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
		<link href="<?php echo e(asset('/assets/css/bootstrap.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(asset('/assets/css/custom-styles.css')); ?>?id=<?php echo random_int(0,9);  ?>" rel="stylesheet" />

	    <link href="<?php echo e(asset('/assets/css/toastr.min.css')); ?>" rel="stylesheet">
	    <script src="<?php echo e(asset ('/assets/js/toastr.min.js')); ?>"></script>

		<!-- Font Awesome -->
		<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>


        <script>
            window.addEventListener('pageshow', function(event) {
              // Check if the persisted property is set to true
              if (event.persisted) {
                document.getElementById("pageBody").style.display = "none";
                document.getElementById("overlay").style.display = "block";
                // Reload the page when the user navigates back or forward
                location.reload(true);
              }
            });
          </script>
	</head>

    <body class="hold-transition login-page" >
        <div id="overlay">
            <div class="cv-spinner">
              <span class="spinner"></span>
            </div>
        </div>
        <div class="login-box" id="pageBody">
            <div class="login-logo">
				<img src="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('logo'))); ?>" alt="Logo" width="200" />
            </div>
            <!-- /.login-logo -->

            <div class="login-box-body ">
				<?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<div class="panel p-md-5 p-4 login_page">
	                <?php echo $__env->yieldContent('content'); ?>
	            </div>
            </div>
            <!-- /.login-box-body -->
        </div>

		<script>
			$("document").ready(function(){
				setTimeout(function(){
					$(".message").fadeOut('fast');
				}, 3000 ); // 5 secs
			});

		</script>
    </body>
</html>
<?php /**PATH /var/www/html/resources/views/admin/layouts/admin-login.blade.php ENDPATH**/ ?>