<nav class="navbar-default navbar-side" role="navigation" id="sidebar">
    <div class="sidebar_header d-flex align-items-center">
        <h6 class="mb-0"><?php echo e(__('messages.navigations')); ?></h6>
        <div id="sideNav" class="ms-auto" href="">
            <em class="fa fa-bars icon"></em>
        </div>
    </div>
    <div class="sidebar-collapse">

        <ul class="nav d-block" id="main-menu">
            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('DASHBOARD_LIST'))) : ?>
            <li>
                <a class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active-menu' => request()->is('admin/dashboard*')]); ?>" href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em>
                    <span class="menu_name"><?php echo e(__('messages.dashboard')); ?></span></a>
            </li>
            <?php endif; ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="user_manage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-users"></em><span class="menu_name">
                        <?php echo e(__('messages.UserManagement')); ?></span><span class="fa arrow"></span>
                </a>
                <ul class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'dropdown-menu',
                    'show' =>
                        request()->is('admin/user-list*') ||
                        request()->is('admin/role*') ||
                        request()->is('admin/sub-admin-list*'),
                ]); ?>" id="user_management_ul" aria-labelledby="user_manage">
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('ROLE_LIST'))) : ?>
                        <li>
                            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'active-menu' => request()->is('admin/role*'),
                            ]); ?>" href="<?php echo e(route('admin.role.list')); ?>"><em
                                    class="fa fa-users-gear px-2"></em><span class="menu_name">
                                    <?php echo e(trans('messages.role')); ?></span></a>
                        </li>
                    <?php endif; ?>


                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SUB_ADMIN_LIST'))) : ?>
                        <li>
                            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'active-menu' => request()->is('admin/sub-admin-list*'),
                            ]); ?>" href="<?php echo e(Route('admin.listSubadmin')); ?>"><em
                                    class="fa fa-users px-2"></em> <span
                                    class="menu_name"><?php echo e(__('messages.subAdmin')); ?></span></a>
                        </li>
                    <?php endif; ?>


                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('USER_LIST'))) : ?>
                        <li>
                            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'active-menu' => request()->is('admin/user-list*'),
                            ]); ?>" href="<?php echo e(Route('admin.listUsers')); ?>"><em
                                    class="fa fa-user px-2"></em> <span
                                    class="menu_name"><?php echo e(__('messages.user')); ?></span></a>
                        </li>
                    <?php endif; ?>


                </ul>
            </li>

            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('VEHICLE_LIST'))) : ?>

            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="vehicle_management" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                               <em class="fa fa-car"></em><span class="menu_name"><?php echo e(__('messages.vehicle_management')); ?></span><span class="fa arrow"></span>
                           </a>
                           <ul class="dropdown-menu <?php echo e(request()->routeIs([
                               'admin.vehicle.list',
                               'admin.vehicle.view'
                           ]) ? 'show' : ''); ?>" id="vehicle_management_ul" aria-labelledby="vehicle_management">


                                   <li>
                                       <a class="dropdown-item <?php echo e(request()->routeIs(['admin.vehicle.list','admin.vehicle.view']) ? 'active-menu' : ''); ?>"
                                           href="<?php echo e(route('admin.vehicle.list')); ?>">
                                           <em class="fas fa-car-side px-2"></em><span class="menu_name"><?php echo e(trans('messages.vehicle')); ?></span>
                                       </a>
                                   </li>

                           </ul>
                       </li>

                       <?php endif; ?>

            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('BRAND_LIST'))) : ?>
            <li>
                <a class="dropdown-item <?php echo e(request()->routeIs(['admin.brand.list','admin.brand.add','admin.brand.edit']) ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.brand.list')); ?>">
                    <em class="fa fa-gg px-2"></em><span class="menu_name"><?php echo e(trans('messages.brand')); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('MODEL_LIST'))) : ?>
            <li>
                <a class="dropdown-item <?php echo e(request()->routeIs(['admin.model.list','admin.model.add','admin.model.edit']) ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.model.list')); ?>">
                    <em class="fa fa-audio-description px-2"></em><span class="menu_name"><?php echo e(trans('messages.model')); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FUEL_USED_LIST'))) : ?>
            <li>
                <a class="dropdown-item <?php echo e(request()->routeIs(['admin.fueltype.list','admin.fueltype.add','admin.fueltype.edit']) ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.fueltype.list')); ?>">
                    <em class="fa fa-gas-pump px-2"></em><span class="menu_name"><?php echo e(trans('messages.fueltype')); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FUEL_USED_LIST'))) : ?>
            <li>
                <a class="dropdown-item <?php echo e(request()->routeIs(['admin.fuelrefill.list','admin.fuelrefill.add','admin.fuelrefill.edit']) ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.fuelrefill.list')); ?>">
                    <em class="fa fa-gas-pump px-2"></em><span class="menu_name"><?php echo e(trans('Fuel Refill List')); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('TRANSMISSION_TYPE_LIST'))) : ?>
            <li>
                <a class="dropdown-item <?php echo e(request()->routeIs(['admin.transmissiontype.list','admin.transmissiontype.add','admin.transmissiontype.edit']) ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.transmissiontype.list')); ?>">
                    <em class="fas fa-snowplow px-2"></em><span class="menu_name"><?php echo e(trans('messages.transmission_type')); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('ENGINE_CAPACITY_LIST'))) : ?>
            <li>
                <a class="dropdown-item <?php echo e(request()->routeIs(['admin.enginecapacity.list','admin.enginecapacity.add','admin.enginecapacity.edit']) ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.enginecapacity.list')); ?>">
                    <em class="fas fa-sleigh px-2"></em><span class="menu_name"><?php echo e(trans('messages.engine_capacity')); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('INSURANCE_LIST'))) : ?>

        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="insurance_management" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <em class="fa fa-car"></em><span class="menu_name"><?php echo e(__('messages.insurance_management')); ?></span><span class="fa arrow"></span>
                        </a>
                        <ul class="dropdown-menu <?php echo e(request()->routeIs([
                            'admin.insuranceCompany.list'
                        ]) ? 'show' : ''); ?>" id="insurance_management_ul" aria-labelledby="insurance_management">

                                <li>
                                    <a class="dropdown-item checkOne<?php echo e(request()->routeIs('admin.insuranceCompany.list') ? 'active-menu' : ''); ?>"
                                        href="<?php echo e(route('admin.insuranceCompany.list')); ?>">
                                        <em class="fa fa-gg px-2"></em><span class="menu_name"><?php echo e(trans('messages.insurance_company')); ?></span>
                                    </a>
                                </li>


                        </ul>
                    </li>

                    <?php endif; ?>

        
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('INSURANCE_COVER_TYPE_LIST'))) : ?>
                        <li>
                            <a class="dropdown-item checkOne <?php echo e(request()->routeIs('admin.insuranceCoverType.list') ? 'active-menu' : ''); ?>"
                                href="<?php echo e(route('admin.insuranceCoverType.list')); ?>">
                                <em class="fas fa-snowplow px-2"></em><span class="menu_name"><?php echo e(trans('messages.insurance_cover_type')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('INSURANCE_COVER_PERIOD_LIST'))) : ?>
                        <li>
                            <a class="dropdown-item checkOne <?php echo e(request()->routeIs('admin.insuranceCoverPeriod.list') ? 'active-menu' : ''); ?>"
                                href="<?php echo e(route('admin.insuranceCoverPeriod.list')); ?>">
                                <em class="fas fa-sleigh px-2"></em><span class="menu_name"><?php echo e(trans('messages.insurance_cover_period')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('INSURANCE_RENEWAL_LIST'))) : ?>
                        <li>
                            <a class="dropdown-item checkOne <?php echo e(request()->routeIs('admin.insuranceRenewal.list') ? 'active-menu' : ''); ?>"
                                href="<?php echo e(route('admin.insuranceRenewal.list')); ?>">
                                <em class="fas fa-sleigh px-2"></em><span class="menu_name"><?php echo e(trans('messages.insurance_renewal')); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>

            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SERVICE_PROVIDER_LIST'))) : ?>
            <li>
                <a class="dropdown-item checkOne <?php echo e(request()->route()->named('admin.serviceprovider.list') ? 'active-menu' : ''); ?>"
                    href="<?php echo e(route('admin.serviceprovider.list')); ?>">
                    <em class="fa fa-ship"></em><span
                        class="menu_name"><?php echo e(__('messages.servicePr')); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('BANNER_MANGER_LIST'))) : ?>
        <li><a class="dropdown-item <?php echo e(request()->route()->named('admin.banner.list') ? 'active-menu' : ''); ?>"
                href="<?php echo e(Route('admin.banner.index')); ?>"><em class="fa fa-image px-2"></em> <span
                    class="menu_name">
                    <?php echo e(__('messages.bannerManagement')); ?></span></a></li>
        <?php endif; ?>


        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('REPORT_LIST'))) : ?>
        <li>
            <a class="dropdown-item checkOne <?php echo e(request()->route()->named('admin.report') ? 'active-menu' : ''); ?>"
                href="<?php echo e(route('admin.report')); ?>">
                <em class="fa fa-ship"></em><span
                    class="menu_name"><?php echo e(__('messages.ReportManager')); ?></span>
            </a>
        </li>
    <?php endif; ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="cms_management" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-file"></em><span class="menu_name">
                        <?php echo e(__('messages.CMSManager')); ?></span><span class="fa arrow"></span>
                </a>
                <ul class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'dropdown-menu',
                    'show' =>
                        request()->is('admin/cms-list*') ||
                        request()->is('admin/email_template-list*') ||
                        request()->is('admin/faq-list*'),
                ]); ?>" id="cms_management_ul" aria-labelledby="cms_management">

                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('EMAIL_TEMPLATE_MANGER_LIST'))) : ?>
                    <li>

                        <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'dropdown-item',
                            'active-menu' => request()->is('admin/email_template-list*'),
                        ]); ?>" href="<?php echo e(route('admin.email_template.index')); ?>">
                            <em class="fa fa-folder px-2"></em> <span
                                class="menu_name"><?php echo e(__('messages.EmailTemplateManagement')); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('CMS_MANGER_LIST'))) : ?>
                        <li>
                            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'dropdown-item',
                                'active-menu' => request()->is('admin/cms-list*'),
                            ]); ?>" href="<?php echo e(route('admin.cms.index')); ?>">
                                <em class="fa fa-folder px-2"></em> <span
                                    class="menu_name"><?php echo e(__('messages.page_management')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('FAQ_LIST'))) : ?>
                        <li>
                            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'dropdown-item',
                                'active-menu' => request()->is('admin/faq-list*'),
                            ]); ?>" href="<?php echo e(route('admin.faq.index')); ?>">
                                <em class="fa fa-folder px-2"></em> <span
                                    class="menu_name"><?php echo e(__('messages.FaqManager')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>

            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('CONTACT_LIST'))) : ?>
                <li>
                    <a class="dropdown-item <?php echo e(request()->route()->named('admin.contactList') ? 'active-menu' : ''); ?>"
                        href="<?php echo e(route('admin.contactList')); ?>">
                        <em class="fa fa-user px-2"></em><span
                            class="menu_name"><?php echo e(__('messages.help_support')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="jawascript:void(0);" id="dropdown_manage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-dropbox"></em> <span
                        class="menu_name"><?php echo e(__('messages.DropdownManagement')); ?></span>
                    <span class="fa arrow"></span>
                </a>
                <?php
                    use App\Services\CommonService;
                    CommonService::createDropDownTableIfNotExists();
                    $dropdownItems = CommonService::dropDownItems();
                ?>

                <ul class="dropdown-menu" id="dropdown_ul" aria-labelledby="dropdown_manage">
                    <?php $__currentLoopData = $dropdownItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dropdownItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission(strtoupper($dropdownItem->name) . '_LIST'))) : ?>
                            <li>
                                <a class="dropdown-item <?php echo e(request()->is("admin/dropdown/index/{$dropdownItem->name}*") ? 'active-menu' : ''); ?>"
                                    href="<?php echo e(url('admin/dropdown/index/' . $dropdownItem->name)); ?>">
                                    <em class="<?php echo e($dropdownItem->icon); ?>"></em> <span class="menu_name"> &nbsp;
                                        <?php echo e(ucfirst(__('dropdowns.' . $dropdownItem->name))); ?> List</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="notification_manage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-bell"></em><span class="menu_name">
                        <?php echo e(__('messages.NotificationsManagement')); ?></span><span class="fa arrow"></span>
                </a>
                <ul class="dropdown-menu <?php echo e(request()->is('admin/push-notification*') || request()->is('admin/list-notification*') ? 'show' : ''); ?>"
                    id="notification_ul" aria-labelledby="notification_manage">
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('NOTIFICATION_SEND'))) : ?>
                        <li>
                            <a class="dropdown-item <?php echo e(request()->is('admin/push-notification*') ? 'active-menu' : ''); ?>"
                                href="<?php echo e(Route('admin.PushNotification')); ?>"><em class="fa fa-send-o px-2"></em><span
                                    class="menu_name"><?php echo e(trans('messages.sendNotification')); ?></span></a>
                        </li>
                    <?php endif; ?>
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('NOTIFICATION_LIST'))) : ?>
                        <li>
                            <a href="<?php echo e(Route('admin.ListNotification')); ?>"
                                class="dropdown-item <?php echo e(request()->is('admin/list-notification*') ? 'active-menu' : ''); ?>">
                                <em class="fa fa-list px-2"></em> <span
                                    class="menu_name"><?php echo e(trans('messages.notificationHistory')); ?> </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('SETTING_MANAGEMENT_LIST'))) : ?>
                <li>
                    <a class="<?php echo e(request()->route()->named('Settings.index') ? 'active-menu' : ''); ?>"
                        href="<?php echo e(route('Settings.index')); ?>">
                        <em class="fa fa-gear"></em><span
                            class="menu_name"><?php echo e(trans('messages.SettingsManagement')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        </li>

        </ul>
    </div>
</nav>
<script type="text/javascript">
$(document).ready(function() {
    var vehicle_management_li_count = $("#vehicle_management_ul li").length;
  var insurance_management_count = $("#insurance_management_ul li").length;

    $("#vehicle_management").css("display", "none");
    $("#insurance_management").css("display", "none");

    if (vehicle_management_li_count > 0) {
    $("#vehicle_management").css("display", "block");
  }
  if (insurance_management_count > 0) {
    $("#insurance_management").css("display", "block");
  }

});

</script>
<script src="<?php echo e(asset('assets/js/sidebar.js')); ?>"></script>
<?php /**PATH /var/www/html/resources/views/admin/include/sidebar.blade.php ENDPATH**/ ?>