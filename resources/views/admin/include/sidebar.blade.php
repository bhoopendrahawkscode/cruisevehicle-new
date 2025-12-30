<nav class="navbar-default navbar-side" role="navigation" id="sidebar">
    <div class="sidebar_header d-flex align-items-center">
        <h6 class="mb-0">{{ __('messages.navigations') }}</h6>
        <div id="sideNav" class="ms-auto" href="">
            <em class="fa fa-bars icon"></em>
        </div>
    </div>
    <div class="sidebar-collapse">

        <ul class="nav d-block" id="main-menu">
            @can('DASHBOARD_LIST')
            <li>
                <a @class(['active-menu' => request()->is('admin/dashboard*')]) href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em>
                    <span class="menu_name">{{ __('messages.dashboard') }}</span></a>
            </li>
            @endcan
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="user_manage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-users"></em><span class="menu_name">
                        {{ __('messages.UserManagement') }}</span><span class="fa arrow"></span>
                </a>
                <ul @class([
                    'dropdown-menu',
                    'show' =>
                        request()->is('admin/user-list*') ||
                        request()->is('admin/role*') ||
                        request()->is('admin/sub-admin-list*'),
                ]) id="user_management_ul" aria-labelledby="user_manage">
                    @can('ROLE_LIST')
                        <li>
                            <a @class([
                                'active-menu' => request()->is('admin/role*'),
                            ]) href="{{ route('admin.role.list') }}"><em
                                    class="fa fa-users-gear px-2"></em><span class="menu_name">
                                    {{ trans('messages.role') }}</span></a>
                        </li>
                    @endcan


                    @can('SUB_ADMIN_LIST')
                        <li>
                            <a @class([
                                'active-menu' => request()->is('admin/sub-admin-list*'),
                            ]) href="{{ Route('admin.listSubadmin') }}"><em
                                    class="fa fa-users px-2"></em> <span
                                    class="menu_name">{{ __('messages.subAdmin') }}</span></a>
                        </li>
                    @endcan


                    @can('USER_LIST')
                        <li>
                            <a @class([
                                'active-menu' => request()->is('admin/user-list*'),
                            ]) href="{{ Route('admin.listUsers') }}"><em
                                    class="fa fa-user px-2"></em> <span
                                    class="menu_name">{{ __('messages.user') }}</span></a>
                        </li>
                    @endcan


                </ul>
            </li>

            @can('VEHICLE_LIST')

            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="vehicle_management" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                               <em class="fa fa-car"></em><span class="menu_name">{{ __('messages.vehicle_management') }}</span><span class="fa arrow"></span>
                           </a>
                           <ul class="dropdown-menu {{ request()->routeIs([
                               'admin.vehicle.list',
                               'admin.vehicle.view'
                           ]) ? 'show' : '' }}" id="vehicle_management_ul" aria-labelledby="vehicle_management">


                                   <li>
                                       <a class="dropdown-item {{ request()->routeIs(['admin.vehicle.list','admin.vehicle.view']) ? 'active-menu' : '' }}"
                                           href="{{ route('admin.vehicle.list') }}">
                                           <em class="fas fa-car-side px-2"></em><span class="menu_name">{{ trans('messages.vehicle') }}</span>
                                       </a>
                                   </li>

                           </ul>
                       </li>

                       @endcan

            @can('BRAND_LIST')
            <li>
                <a class="dropdown-item {{ request()->routeIs(['admin.brand.list','admin.brand.add','admin.brand.edit']) ? 'active-menu' : '' }}"
                    href="{{ route('admin.brand.list') }}">
                    <em class="fa fa-gg px-2"></em><span class="menu_name">{{ trans('messages.brand') }}</span>
                </a>
            </li>
        @endcan
        @can('MODEL_LIST')
            <li>
                <a class="dropdown-item {{ request()->routeIs(['admin.model.list','admin.model.add','admin.model.edit']) ? 'active-menu' : '' }}"
                    href="{{ route('admin.model.list') }}">
                    <em class="fa fa-audio-description px-2"></em><span class="menu_name">{{ trans('messages.model') }}</span>
                </a>
            </li>
        @endcan
        @can('FUEL_USED_LIST')
            <li>
                <a class="dropdown-item {{ request()->routeIs(['admin.fueltype.list','admin.fueltype.add','admin.fueltype.edit']) ? 'active-menu' : '' }}"
                    href="{{ route('admin.fueltype.list') }}">
                    <em class="fa fa-gas-pump px-2"></em><span class="menu_name">{{ trans('messages.fueltype') }}</span>
                </a>
            </li>
        @endcan

        @can('FUEL_USED_LIST')
            <li>
                <a class="dropdown-item {{ request()->routeIs(['admin.fuelrefill.list','admin.fuelrefill.add','admin.fuelrefill.edit']) ? 'active-menu' : '' }}"
                    href="{{ route('admin.fuelrefill.list') }}">
                    <em class="fa fa-gas-pump px-2"></em><span class="menu_name">{{ trans('Fuel Refill List') }}</span>
                </a>
            </li>
        @endcan
        @can('TRANSMISSION_TYPE_LIST')
            <li>
                <a class="dropdown-item {{ request()->routeIs(['admin.transmissiontype.list','admin.transmissiontype.add','admin.transmissiontype.edit']) ? 'active-menu' : '' }}"
                    href="{{ route('admin.transmissiontype.list') }}">
                    <em class="fas fa-snowplow px-2"></em><span class="menu_name">{{ trans('messages.transmission_type') }}</span>
                </a>
            </li>
        @endcan
        @can('ENGINE_CAPACITY_LIST')
            <li>
                <a class="dropdown-item {{ request()->routeIs(['admin.enginecapacity.list','admin.enginecapacity.add','admin.enginecapacity.edit']) ? 'active-menu' : '' }}"
                    href="{{ route('admin.enginecapacity.list') }}">
                    <em class="fas fa-sleigh px-2"></em><span class="menu_name">{{ trans('messages.engine_capacity') }}</span>
                </a>
            </li>
        @endcan

        @can('INSURANCE_LIST')

        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="insurance_management" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <em class="fa fa-car"></em><span class="menu_name">{{ __('messages.insurance_management') }}</span><span class="fa arrow"></span>
                        </a>
                        <ul class="dropdown-menu {{ request()->routeIs([
                            'admin.insuranceCompany.list'
                        ]) ? 'show' : '' }}" id="insurance_management_ul" aria-labelledby="insurance_management">

                                <li>
                                    <a class="dropdown-item checkOne{{ request()->routeIs('admin.insuranceCompany.list') ? 'active-menu' : '' }}"
                                        href="{{ route('admin.insuranceCompany.list') }}">
                                        <em class="fa fa-gg px-2"></em><span class="menu_name">{{ trans('messages.insurance_company') }}</span>
                                    </a>
                                </li>


                        </ul>
                    </li>

                    @endcan

        {{-- @can('INSURANCE_QUOTE_LIST')
                        <li>
                            <a class="dropdown-item checkOne {{ request()->routeIs('admin.insuranceQuote.list') ? 'active-menu' : '' }}"
                                href="{{ route('admin.insuranceQuote.list') }}">
                                <em class="fa fa-audio-description px-2"></em><span class="menu_name">{{ trans('messages.insurance_quote') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('INSURANCE_QUOTE_CONFIRMATION_LIST')
                        <li>
                            <a class="dropdown-item checkOne {{ request()->routeIs('admin.insuranceQuoteConfirmation.list') ? 'active-menu' : '' }}"
                                href="{{ route('admin.insuranceQuoteConfirmation.list') }}">
                                <em class="fa fa-gas-pump px-2"></em><span class="menu_name">{{ trans('messages.insurance_quote_confirmation') }}</span>
                            </a>
                        </li>
                    @endcan --}}
                    @can('INSURANCE_COVER_TYPE_LIST')
                        <li>
                            <a class="dropdown-item checkOne {{ request()->routeIs('admin.insuranceCoverType.list') ? 'active-menu' : '' }}"
                                href="{{ route('admin.insuranceCoverType.list') }}">
                                <em class="fas fa-snowplow px-2"></em><span class="menu_name">{{ trans('messages.insurance_cover_type') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('INSURANCE_COVER_PERIOD_LIST')
                        <li>
                            <a class="dropdown-item checkOne {{ request()->routeIs('admin.insuranceCoverPeriod.list') ? 'active-menu' : '' }}"
                                href="{{ route('admin.insuranceCoverPeriod.list') }}">
                                <em class="fas fa-sleigh px-2"></em><span class="menu_name">{{ trans('messages.insurance_cover_period') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('INSURANCE_RENEWAL_LIST')
                        <li>
                            <a class="dropdown-item checkOne {{ request()->routeIs('admin.insuranceRenewal.list') ? 'active-menu' : '' }}"
                                href="{{ route('admin.insuranceRenewal.list') }}">
                                <em class="fas fa-sleigh px-2"></em><span class="menu_name">{{ trans('messages.insurance_renewal') }}</span>
                            </a>
                        </li>
                        @endcan

            @can('SERVICE_PROVIDER_LIST')
            <li>
                <a class="dropdown-item checkOne {{ request()->route()->named('admin.serviceprovider.list') ? 'active-menu' : '' }}"
                    href="{{ route('admin.serviceprovider.list') }}">
                    <em class="fa fa-ship"></em><span
                        class="menu_name">{{ __('messages.servicePr') }}</span>
                </a>
            </li>
        @endcan

        @can('BANNER_MANGER_LIST')
        <li><a class="dropdown-item {{ request()->route()->named('admin.banner.list') ? 'active-menu' : '' }}"
                href="{{ Route('admin.banner.index') }}"><em class="fa fa-image px-2"></em> <span
                    class="menu_name">
                    {{ __('messages.bannerManagement') }}</span></a></li>
        @endcan


        @can('REPORT_LIST')
        <li>
            <a class="dropdown-item checkOne {{ request()->route()->named('admin.report') ? 'active-menu' : '' }}"
                href="{{ route('admin.report') }}">
                <em class="fa fa-ship"></em><span
                    class="menu_name">{{ __('messages.ReportManager') }}</span>
            </a>
        </li>
    @endcan

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="cms_management" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-file"></em><span class="menu_name">
                        {{ __('messages.CMSManager') }}</span><span class="fa arrow"></span>
                </a>
                <ul @class([
                    'dropdown-menu',
                    'show' =>
                        request()->is('admin/cms-list*') ||
                        request()->is('admin/email_template-list*') ||
                        request()->is('admin/faq-list*'),
                ]) id="cms_management_ul" aria-labelledby="cms_management">

                    @can('EMAIL_TEMPLATE_MANGER_LIST')
                    <li>

                        <a @class([
                            'dropdown-item',
                            'active-menu' => request()->is('admin/email_template-list*'),
                        ]) href="{{ route('admin.email_template.index') }}">
                            <em class="fa fa-folder px-2"></em> <span
                                class="menu_name">{{ __('messages.EmailTemplateManagement') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('CMS_MANGER_LIST')
                        <li>
                            <a @class([
                                'dropdown-item',
                                'active-menu' => request()->is('admin/cms-list*'),
                            ]) href="{{ route('admin.cms.index') }}">
                                <em class="fa fa-folder px-2"></em> <span
                                    class="menu_name">{{ __('messages.page_management') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('FAQ_LIST')
                        <li>
                            <a @class([
                                'dropdown-item',
                                'active-menu' => request()->is('admin/faq-list*'),
                            ]) href="{{ route('admin.faq.index') }}">
                                <em class="fa fa-folder px-2"></em> <span
                                    class="menu_name">{{ __('messages.FaqManager') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

            @can('CONTACT_LIST')
                <li>
                    <a class="dropdown-item {{ request()->route()->named('admin.contactList') ? 'active-menu' : '' }}"
                        href="{{ route('admin.contactList') }}">
                        <em class="fa fa-user px-2"></em><span
                            class="menu_name">{{ __('messages.help_support') }}</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="jawascript:void(0);" id="dropdown_manage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-dropbox"></em> <span
                        class="menu_name">{{ __('messages.DropdownManagement') }}</span>
                    <span class="fa arrow"></span>
                </a>
                @php
                    use App\Services\CommonService;
                    CommonService::createDropDownTableIfNotExists();
                    $dropdownItems = CommonService::dropDownItems();
                @endphp

                <ul class="dropdown-menu" id="dropdown_ul" aria-labelledby="dropdown_manage">
                    @foreach ($dropdownItems as $dropdownItem)
                        @can(strtoupper($dropdownItem->name) . '_LIST')
                            <li>
                                <a class="dropdown-item {{ request()->is("admin/dropdown/index/{$dropdownItem->name}*") ? 'active-menu' : '' }}"
                                    href="{{ url('admin/dropdown/index/' . $dropdownItem->name) }}">
                                    <em class="{{ $dropdownItem->icon }}"></em> <span class="menu_name"> &nbsp;
                                        {{ ucfirst(__('dropdowns.' . $dropdownItem->name)) }} List</span>
                                </a>
                            </li>
                        @endcan
                    @endforeach
                </ul>

            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="javascript:void(0);" id="notification_manage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <em class="fa fa-bell"></em><span class="menu_name">
                        {{ __('messages.NotificationsManagement') }}</span><span class="fa arrow"></span>
                </a>
                <ul class="dropdown-menu {{ request()->is('admin/push-notification*') || request()->is('admin/list-notification*') ? 'show' : '' }}"
                    id="notification_ul" aria-labelledby="notification_manage">
                    @can('NOTIFICATION_SEND')
                        <li>
                            <a class="dropdown-item {{ request()->is('admin/push-notification*') ? 'active-menu' : '' }}"
                                href="{{ Route('admin.PushNotification') }}"><em class="fa fa-send-o px-2"></em><span
                                    class="menu_name">{{ trans('messages.sendNotification') }}</span></a>
                        </li>
                    @endcan
                    @can('NOTIFICATION_LIST')
                        <li>
                            <a href="{{ Route('admin.ListNotification') }}"
                                class="dropdown-item {{ request()->is('admin/list-notification*') ? 'active-menu' : '' }}">
                                <em class="fa fa-list px-2"></em> <span
                                    class="menu_name">{{ trans('messages.notificationHistory') }} </span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            @can('SETTING_MANAGEMENT_LIST')
                <li>
                    <a class="{{ request()->route()->named('Settings.index') ? 'active-menu' : '' }}"
                        href="{{ route('Settings.index') }}">
                        <em class="fa fa-gear"></em><span
                            class="menu_name">{{ trans('messages.SettingsManagement') }}</span>
                    </a>
                </li>
            @endcan
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
<script src="{{ asset('assets/js/sidebar.js') }}"></script>
