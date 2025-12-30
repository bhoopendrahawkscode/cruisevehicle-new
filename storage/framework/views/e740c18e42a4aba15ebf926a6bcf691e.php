
<link integrity="" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
<?php $__env->startSection('content'); ?>
<?php

use App\Services\GeneralService;
use \App\Constants\Constant; ?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        <?php echo e(trans('messages.insurance_renewal')); ?> <?php echo e(trans('messages.list')); ?>

    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="<?php echo e(Route('admin.dashboard')); ?>"><em class="fa fa-dashboard"></em> <?php echo e(trans('messages.dashboard')); ?> </a></li>
        <li class="active"><?php echo e(trans('messages.insurance_renewal')); ?> <?php echo e(trans("messages.list")); ?></li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            <?php echo e(html()->modelForm(null, 'GET')->route('admin.insuranceRenewal.list')
            ->attributes(['class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'onSubmit' => 'return checkDate();'])->open()); ?>

            <?php echo e(html()->hidden('display')); ?>

            <div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
                    <div class="form-group mb-0">
                        <?php echo e(html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')
                        ->attributes(['class' => 'form-control', 'placeholder' => trans('Search By Full Name, reference number')])); ?>

                    </div>

                    <div class="form-group mb-0 calendarIcon">
                        <?php echo e(html()->text('from', isset($searchVariable['from']) ? $searchVariable['from'] : '')
                        ->attributes(['class' => 'form-control datepicker', 'onkeydown' => 'return false;', 'placeholder' => trans('messages.fromDate')])); ?>

                    </div>
                    <div class="form-group mb-0 calendarIcon">
                        <?php echo e(html()->text('to', isset($searchVariable['to']) ? $searchVariable['to'] : '')
                        ->attributes(['class' => 'form-control datepicker', 'onkeydown' => 'return false;', 'placeholder' => trans('messages.toDate')])); ?>

                    </div>
                </div>
                <div class="form-action ">
                    <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon" title="<?php echo e(trans('messages.search')); ?>"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="<?php echo e(Route('admin.insuranceRenewal.list')); ?>" class="btn btn-sm border_btn btnIcon" title="<?php echo e(trans('messages.reset')); ?>"><em class='fa fa-refresh '></em> </a>
                    <?php if (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->hasPermission('INSURANCE_RENEWAL_ADD'))) : ?>
                    
                    <?php endif; ?>
                </div>
            </div>
            <?php echo e(html()->closeModelForm()); ?>

        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="<?php if(!$result->isEmpty()): ?> table table-striped table-hover <?php endif; ?>">
                    <caption><?php echo e(trans('messages.insuranceRenewal')); ?> <?php echo e(trans('messages.list')); ?></caption>
                    <?php if(!$result->isEmpty()): ?>
                    <thead>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.sNo'),
                                ['sortBy' => 'id', 'order' => $sortBy == 'id' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'id') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.fullName'),
                                ['sortBy' => 'full_name', 'order' => $sortBy == 'full_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'full_name') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.nic'),
                                ['sortBy' => 'nic', 'order' => $sortBy == 'nic' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'nic') ?>
                        </th>
                        <!-- <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.carModel'),
                                ['sortBy' => 'car_model', 'order' => $sortBy == 'car_model' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'car_model') ?>
                        </th> -->
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.carModel'),
                                ['sortBy' => 'car_model_name', 'order' => $sortBy == 'car_model_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'car_model_name') ?>
                        </th>

                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.yearOfManufacturer'),
                                ['sortBy' => 'year_of_manufacturer', 'order' => $sortBy == 'year_of_manufacturer' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'year_of_manufacturer') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.vehicleRegistrationMark'),
                                ['sortBy' => 'vehicle_registration_mark', 'order' => $sortBy == 'vehicle_registration_mark' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'vehicle_registration_mark') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.value'),
                                ['sortBy' => 'value', 'order' => $sortBy == 'value' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'value') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.sumToBeInsured'),
                                ['sortBy' => 'sum_to_be_insured', 'order' => $sortBy == 'sum_to_be_insured' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'sum_to_be_insured') ?>
                        </th>
                        <!-- <th scope="col">
                            <?php echo e(trans('messages.insuranceRenewal.cover_type')); ?>

                        </th>
                        <th scope="col">
                            <?php echo e(trans('messages.insuranceRenewal.InsurancePeriod')); ?>


                        </th> -->
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.cover_type'),
                                ['sortBy' => 'cover_type_name', 'order' => $sortBy == 'cover_type_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'cover_type_name') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.InsurancePeriod'),
                                ['sortBy' => 'period_insurance_cover_name', 'order' => $sortBy == 'period_insurance_cover_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'period_insurance_cover_name') ?>
                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                                'admin.insuranceRenewal.list',
                                                trans("messages.createdOn"),
                                                [
                                                    'sortBy' => 'created_at',
                                                    'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc'
                                                ],
                                                $query_string
                                            )); ?>


                            <?php getSortIcon($sortBy,$order,'created_at') ?>
                        </th>


                        <th scope="col">
                            <?php echo e(trans('messages.insuranceRenewal.RequestStatus')); ?>


                        </th>
                        <th scope="col">
                            <?php echo e(link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.Status'),
                                ['sortBy' => 'status', 'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            )); ?>

                            <?php getSortIcon($sortBy, $order, 'status') ?>


                        </th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($value->id); ?></td>
                            <td><?php echo e($value->full_name); ?></td>
                            <td><?php echo e($value->nic); ?></td>
                            <td><?php echo e($value->carModel->name ?? ''); ?></td>
                            <td><?php echo e($value->year_of_manufacturer); ?></td>
                            <td><?php echo e($value->vehicle_registration_mark); ?></td>
                            <td><?php echo e($value->value?'INR. '.$value->value:'N/A'); ?></td>
                            <td><?php echo e($value->sum_to_be_insured?'INR. '. $value->sum_to_be_insured:'N/A'); ?></td>
                            <td><?php echo e($value->coverType->name ?? ''); ?></td>
                            <td><?php echo e($value->periodInsuranceCover->name ?? ''); ?></td>
                            <td>
                                <?php echo e(date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($value->created_at))); ?>

                            </td>
                            <td>
                                <button class="btn btn-primary view-companies"
                                    data-companies='<?php echo json_encode($value->CompanyInsuranceRenewal->map(function($CompanyInsuranceRenewal) {
                                    return [
                                        "company" => $CompanyInsuranceRenewal->company->full_name ?? "No Company", "status" => $CompanyInsuranceRenewal->status, "premium_payable" => $CompanyInsuranceRenewal->premium_payable
                                    ];
                                })) ?>'>
                                    View Companies
                                </button>
                            </td>
                            <td><?php echo e(($value->status == 1) ? 'Active' : 'Inactive'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <?php else: ?>
                    <tr>
                        <td colspan="16" class="text-center"><?php echo e(trans('messages.noRecordFound')); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="box-footer clearfix">

                <?php echo $__env->make('pagination.default', ['paginator' => $result], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	// Function to dynamically set the width based on placeholder text length
function adjustWidthBasedOnPlaceholder() {
    // Get the placeholder value
    var placeholderText = document.getElementById("name").placeholder;
    
    // Create a temporary element to measure text width
    var tempSpan = document.createElement("span");
    tempSpan.style.visibility = "hidden";
    tempSpan.style.whiteSpace = "nowrap";
    tempSpan.style.fontSize = window.getComputedStyle(document.getElementById("name")).fontSize;
    tempSpan.style.fontFamily = window.getComputedStyle(document.getElementById("name")).fontFamily;
    tempSpan.innerText = placeholderText;

    // Append the element to the body to measure
    document.body.appendChild(tempSpan);
    var textWidth = tempSpan.offsetWidth;

    // Remove the temporary element
    document.body.removeChild(tempSpan);

    // Set the width of the form-group dynamically
    document.querySelector(".form-group").style.width = (textWidth +40) + "px"; // Add some padding for better spacing
}

// Call the function on page load
window.onload = adjustWidthBasedOnPlaceholder;

	</script>
<script>
    $(document).ready(function() {
        $('.view-companies').on('click', function() {
            var companies = $(this).data('companies');
            var modalContent = `
        <div style="font-family: Arial, sans-serif; color: #333;">
            <table style="width: 100%; border-collapse: collapse; margin: 0 auto;">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #495057;">
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Company</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Status</th>
                        <th style="padding: 10px; border: 1px solid #dee2e6;">Premium Payable</th>
                    </tr>
                </thead>
                <tbody>`;

            companies.forEach(function(companyRenewal) {
                var companyName = companyRenewal.company;
                var premiumPayable = companyRenewal.premium_payable ? 'INR. '+companyRenewal.premium_payable : 'N/A';
                var status = '';
                switch (companyRenewal.status) {
                    case 0:
                        status = 'Awaiting Reply';
                        break;
                    case 1:
                        status = 'Replied';
                        break;
                    case 2:
                        status = 'Rejected';
                        break;
                    case 3:
                        status = 'Release Certificate';
                        break;
                        case 4:
                        status = 'Confirmed';
                        break;
                    default:
                        status = 'Unknown';
                        break;
                }
                modalContent += `
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${companyName}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${status}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${premiumPayable}</td>
            </tr>`;
            });

            modalContent += `
                </tbody>
            </table>
        </div>`;

            Swal.fire({
                title: 'Company Status and Premium Payable',
                html: modalContent,
                width: '50%',
                padding: '20px',
                background: '#fff',
                confirmButtonText: 'Close',
                confirmButtonColor: '#3085d6',
                customClass: {
                    container: 'my-swal-container',
                    title: 'my-swal-title',
                    content: 'my-swal-content',
                    confirmButton: 'my-swal-confirm-button'
                }
            });
        });
    });
</script>

<style>
    /* Custom Styles for SweetAlert2 */
    .my-swal-container {
        border-radius: 10px;
    }

    .my-swal-title {
        font-size: 1.5rem;
        color: #333;
    }

    .my-swal-content {
        font-size: 1rem;
        color: #333;
    }

    .my-swal-confirm-button {
        background-color: #3085d6;
        border-color: #3085d6;
        color: #fff;
        font-weight: bold;
    }
</style>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.default_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/insuranceRenewal/list.blade.php ENDPATH**/ ?>