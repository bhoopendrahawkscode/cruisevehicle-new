<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="author" content="octal it solution"/>
        <?php echo $meta_info??''; ?>


		<?php
        use App\Constants\Constant;
        use App\Services\GeneralService;
        use App\Services\ImageService;
        if(isset($section) && !empty($section)){ ?>
            <title><?php echo e(GeneralService::getSettings('websiteTitle')); ?> - <?php echo $section; ?></title>
        <?php }else{ ?>
            <title><?php echo e(GeneralService::getSettings('websiteTitle')); ?></title>
        <?php } ?>

        <style>
            :root {
                --primaryColor: <?php echo GeneralService::getSettings('primaryColor'); ?>;
                --secondaryColor: <?php echo GeneralService::getSettings('secondaryColor'); ?>;
            }
        </style>
        <meta content="<?php echo e(GeneralService::getSettings('websiteTitle')); ?> <?php if($version != ''){ echo $version; } ?>" name="description" />

        <link href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" rel=icon sizes=32x32 type=image/png>
        <link href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" rel=icon sizes=16x16 type=image/png>
        <link href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>" rel=apple-touch-icon>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('favicon'))); ?>">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        





		<script src="<?php echo e(asset('/assets/js/jquery-3.7.1.min.js')); ?>"></script>
		<script src="<?php echo e(asset('/assets/js/jquery-migrate.min.js')); ?>"></script>


		<script src="<?php echo e(asset ('/assets/js/jquery.validate.min.js')); ?>?id=<?php echo random_int(0,9);  ?>"></script>
		<script src="<?php echo e(asset ('/assets/js/jquery.validate_custom.min.js')); ?>?id=<?php echo random_int(0,9);  ?>"></script>

		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">


        <!-- Bootstrap CSS -->


		<link href="<?php echo e(asset('/assets/css/bootstrap.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(asset('/assets/css/custom-styles.css')); ?>?id=<?php echo random_int(0,9);  ?>" rel="stylesheet" />


        <script>
            window.addEventListener('pageshow', function(event) {
              // Check if the persisted property is set to true
              if (event.persisted) {
                document.getElementById("wrapper").style.display = "none";
                document.getElementById("overlay").style.display = "block";
                // Reload the page when the user navigates back or forward
                location.reload(true);
              }
            });
            const S3_URL = "<?php echo Constant::S3_URL; ?>"
        </script>

	</head>
    <body>
        <script>
            // Function to dynamically set the width based on placeholder text length
// function adjustWidthBasedOnPlaceholder() {
//     // Get the placeholder value
//     var placeholderText = document.getElementById("name").placeholder;
    
//     // Create a temporary element to measure text width
//     var tempSpan = document.createElement("span");
//     tempSpan.style.visibility = "hidden";
//     tempSpan.style.whiteSpace = "nowrap";
//     tempSpan.style.fontSize = window.getComputedStyle(document.getElementById("name")).fontSize;
//     tempSpan.style.fontFamily = window.getComputedStyle(document.getElementById("name")).fontFamily;
//     tempSpan.innerText = placeholderText;

//     // Append the element to the body to measure
//     document.body.appendChild(tempSpan);
//     var textWidth = tempSpan.offsetWidth;

//     // Remove the temporary element
//     document.body.removeChild(tempSpan);
//     document.querySelector(".form-group").style.width = (textWidth + 50) + "px"; // Add some padding for better spacing
// }

// Call the function on page load
//window.onload = adjustWidthBasedOnPlaceholder;

            if (getCookie('dark') === 'true') {
                 $('body').addClass('dark');
            }
        </script>
        <div id="overlay">
            <div class="cv-spinner">
              <span class="spinner"></span>
            </div>
        </div>
    <div id="wrapper">
		<?php echo $__env->make('admin.include.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<?php echo $__env->make('admin.include.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		  <div id="page-wrapper">
			<?php echo $__env->yieldContent('content'); ?>
			<button class="btn btn-primary customize_btn d-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
				<em class="fa-solid fa-palette"></em>
			</button>
		  </div>
			<?php echo $__env->make('admin.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
				<div class="offcanvas-header">
					<h5 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo e(__('messages.themeSettings')); ?></h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><em class="fa-solid fa-xmark"></em></button>
				</div>
				<div class="offcanvas-body">
					<div class="form-group">
						<label for="">Primary Color</label>
						<div class="input-group input-group-sm color-primary">
							<span class="input-group-text">
								<input  class="border-0" type="color" id="primaryColorPicker" name="primaryColorPicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#ee0c85" />
							</span>
							<input  class="form-control color-picker-input" type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"  id="primaryColor" value="#ee0c85" />
						</div>
					</div>
					<div class="form-group" id="secondaryColorBlock">
						<label for="">Secondary Color</label>
						<div class="input-group input-group-sm color-primary">
							<span class="input-group-text">
								<input class="border-0" type="color" id="secondaryColorPicker" name="secondaryColorPicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#191c21" />
							</span>
							<input class="form-control color-picker-input" type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"  id="secondaryColor" value="#191c21" />
						</div>
					</div>
					<div class="form-group">
						<label for="">Dark Theme</label>
						<div>
							<span class="switch"><input type="checkbox" id="sidebarThemeSwitch"/><span class="slider">&nbsp;</span></span>
						</div>
					</div>

				</div>
			</div>
	</div>

    <script src="<?php echo e(asset('/assets/js/bootstrap.bundle.min.js')); ?>"></script>

    <script src="<?php echo e(asset ('/assets/js/bootstrap-datepicker.js')); ?>"></script>
	<link href="<?php echo e(asset('/assets/css/bootstrap-datepicker.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('/assets/css/toastr.min.css')); ?>" rel="stylesheet">
	<script src="<?php echo e(asset ('/assets/js/toastr.min.js')); ?>"></script>


    <script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

    <script integrity="" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>

    <script integrity="" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


	<script type="text/javascript">

        var r = document.querySelector(':root');

		$(function() {


			var elem = document.getElementsByClassName("active-menu");
			if(elem.length)
			elem[0].scrollIntoView();

			$('#from').datepicker({
				format: "yyyy-mm-dd",
				clearBtn: true
			}).on('changeDate', function(selected){
				$('#to').datepicker('setStartDate', selected.date);
			});
			$('#to').datepicker({
				format: "yyyy-mm-dd",
				clearBtn: true
			}).on('changeDate', function(selected){
				$('#from').datepicker('setEndDate', selected.date);
			});
			<?php if(isset($_GET['from']) && $_GET['from'] !=''){ ?>
				$('#to').datepicker('setStartDate', <?php echo "'".$_GET['from']."'"; ?>);
			<?php } ?>
			<?php if(isset($_GET['to']) && $_GET['to'] !=''){ ?>
				$('#from').datepicker('setEndDate', <?php echo "'".$_GET['to']."'"; ?>);
			<?php } ?>
			checkDate();
		});

		function checkDate(){
			 //Select the date fields by ID
			var fromDateField = $('#from');
			var toDateField = $('#to');

			// Attach an event listener to the "to" date field
			toDateField.on('change', function() {
				checkDate2();
			});
			checkDate2();
			function checkDate2(){
				var fromDateValue = new Date(fromDateField.val());
				var toDateValue = new Date(toDateField.val());

				if (fromDateValue > toDateValue) {
					alert('To date cannot be less than From date.');
					toDateField.val('');
					return false;
				}
				return true;
			}
		}

		$(document).ready(function () {
			$('#sideNav').on('click', function () {
				$('body').toggleClass('toggleSidebar');
			});

			$(".sidebar-collapse").hover(
				function(){
				    // Mouseenter: add the "hovered" class
				    $(this).addClass("hovered");
				},
				function(){
				    // Mouseleave: remove the "hovered" class
				    $(this).removeClass("hovered");
				}
			);


            $('#primaryColorPicker').on('input', function() {
                $('#primaryColor').val(this.value);
                saveTheme('primaryColor',this.value);
            });

            $('#secondaryColorPicker').on('input', function() {
                $('#secondaryColor').val(this.value);
                saveTheme('secondaryColor',this.value);
            });

            // dark Theme Light Theme start
                if (getCookie('dark') === 'true') {
                    $('body').addClass('dark');
                    $("#sidebarThemeSwitch").prop("checked", true);
                    $("#secondaryColorBlock").hide();
                }else{
                    $("#secondaryColorBlock").show();
                }
                $("#sidebarThemeSwitch").change(function(){
                    if(this.checked) {
                        $("body").addClass("dark");
                        setCookie('dark',true);
                        $("#secondaryColorBlock").hide();
                    } else {
                        $("body").removeClass("dark");
                        setCookie('dark',false);
                        $("#secondaryColorBlock").show();
                    }
                });


                var styles = window.getComputedStyle(r);
                $("#primaryColor").val(styles.getPropertyValue('--primaryColor'));
                $("#primaryColorPicker").val(styles.getPropertyValue('--primaryColor'));

                $("#secondaryColor").val(styles.getPropertyValue('--secondaryColor'));
                $("#secondaryColorPicker").val(styles.getPropertyValue('--secondaryColor'));

             // dark Theme Light Theme end

		});
        function saveTheme(type,value){
            if(type=='primaryColor'){
                r.style.setProperty('--primaryColor', value);
            }else if(type=='secondaryColor'){
                r.style.setProperty('--secondaryColor', value);
            }else{
                r.style.setProperty('--primaryColor', "<?php echo Config::get('constants.primaryColor'); ?>");
                r.style.setProperty('--secondaryColor', "<?php echo Config::get('constants.secondaryColor'); ?>");
            }
            var routeUrl = "<?php echo e(route('admin.updateCss')); ?>";
            $.ajax({
                url: routeUrl,
                method: 'POST',
                data: {
                    "type": type,
                    "value": value,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                }
            });
        }

        function block() {
            $.blockUI({ message: '<span class="please_wait">Please Wait...</span>' });
        }
        function unBlock() {
             $.unblockUI();
        }
    </script>
         <?php if(isset($statusChangeUrl)): ?>
            <script>
                $(document).ready(function () {
                    $('.status_any_item').on('change', function() {
                        status=$(this).attr('status');
                        id=$(this).attr('data-id');
                        var location= "<?php echo URL::to($statusChangeUrl); ?>"+'/'+id+'/'+$(this).attr('status');
                        Swal.fire({
                            title: "Are you sure you want to change the status?",
                            text: "",
                            icon: "warning",
                            showCancelButton: true,
                            // confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes"
                        }).then((result) => {
                            confirmed=false;
                            if(result.isConfirmed){
                                var confirmed = result.isConfirmed;
                            }
                            if (status) {
                                if (!confirmed) {
                                    if(status==1){
                                        $(this).prop('checked', false);
                                    }else{
                                    $(this).prop('checked', true);
                                    }
                                }else{
                                    block();
                                    window.location.href = location;
                                }
                            }
                        });
                    });
                });
            </script>
        <?php endif; ?>
    </body>
</html>
<?php /**PATH /var/www/html/resources/views/admin/layouts/default_layout.blade.php ENDPATH**/ ?>