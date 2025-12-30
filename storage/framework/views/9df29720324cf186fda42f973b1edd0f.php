<?php use App\Constants\Constant;
    use App\Services\GeneralService;
    use App\Services\ImageService;
    ?>
<nav class="navbar navbar-default top-navbar p-3" role="navigation">
    <div class="navbar-header d-flex">
        <a class="navbar-brand d-block p-0 m-0 " href="<?php echo e(url("/")); ?>"><img
            alt="logo"
            src="<?php echo e(ImageService::getImageUrl(Config::get('constants.LOGO_FOLDER') . GeneralService::getSettings('logo'))); ?>"
            alt="Logo"
            width="200"
            onerror="this.onerror=null; this.src='public/storage/uploads/logo/ac4291f6-8710-4218-9d5a-5fad95017a8f.png';"
        />></a>
    </div>
	<div class="head_right d-flex align-items-center">

    <div class="dropdown d-none">
        <button class="px-2 btn language fs-5 dropdown-toggle  border-0 bg-transparent py-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
             <em class="fa-solid fa-tent-arrow-left-right"></em>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
    </div>


		<?php  $totalUnreadNotifi  = get_notification_count(Auth::user()->id);  ?>
	
		

        <a href="<?php echo e(Route('edit-profile')); ?>" class="header_notofication me-sm-3">
            <img alt="logo" src="<?php echo e(ImageService::getImageUrl(Config::get('constants.USER_FOLDER') . Auth::user()->image)); ?> " alt="Logo" width="25" height="25" />
        </a>
	


        <div class="dropdown">
                <button class="dropdown-toggle border-0 bg-transparent d-flex text-start p-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                    <h6 class="mb-0"> <span class="head_right_user"><?php echo e(trans('messages.welcome')); ?> <?php echo auth()->user()->first_name; ?> <?php echo auth()->user()->last_name; ?></span>
                        Administrator
                    </h6>
                    <span class="fa arrow" aria-hidden="true"></span>
                </button>
                <ul class="dropdown-menu dropdown-user" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="<?php echo e(Route('edit-profile')); ?>"><em class="fa fa-user fa-fw"></em><?php echo e(trans('messages.myProfile')); ?></a></li>
                    <li><a class="dropdown-item" href="<?php echo e(Route('change-password')); ?>"><em class="fa fa-lock fa-fw"></em> <?php echo e(trans('messages.changePassword')); ?></a></li>
                    <li class="divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('admin.logout')); ?>" data-action="logout">
                            <em class="fa fa-sign-out fa-fw"></em> <?php echo e(trans('messages.logout')); ?>

                        </a>
                    </li>
                </ul>
            </div>
	</div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[data-action]').forEach(function(link) {
            link.addEventListener('click', function(event) {
                var action = this.getAttribute('data-action');
                var confirmMessage;

                switch (action) {
                    case 'logout':
                        confirmMessage = 'Are you sure you want to logout?';
                        break;
                    // Add more cases here if needed for different actions
                    default:
                        confirmMessage = 'Are you sure?';
                        break;
                }

                if (!confirm(confirmMessage)) {
                    event.preventDefault(); // Prevent navigation
                }
            });
        });
    });
</script>
<?php /**PATH /var/www/html/resources/views/admin/include/header.blade.php ENDPATH**/ ?>