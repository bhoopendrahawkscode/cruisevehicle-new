@extends('admin.layouts.default_layout')
<link integrity="" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
@section('content')
<?php use App\Services\GeneralService; use \App\Constants\Constant; ?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{ trans('messages.insurance_renewal') }} {{ trans('messages.list') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }} </a></li>
        <li class="active">{{ trans('messages.insurance_renewal') }} {{ trans("messages.list") }}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            {{ html()->modelForm(null, 'GET')->route('admin.insuranceRenewal.list')
            ->attributes(['class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'onSubmit' => 'return checkDate();'])->open() }}
            {{ html()->hidden('display') }}
            <div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
                    <div class="form-group mb-0">
                        {{ html()->text('name', isset($searchVariable['name']) ? $searchVariable['name'] : '')
                        ->attributes(['class' => 'form-control', 'placeholder' => trans('messages.fullNameSearchPlaceholder')]) }}
                    </div>
                    <div class="form-group mb-0 w-20p">
                            {{ html()->select('insurance_status',['0' => 'Pending', '1' => 'Premium amount quoted', '2' => 'Rejected', '3' => 'Confirmed'],
                            ((isset($searchVariable['insurance_status'])) ? $searchVariable['insurance_status'] : ''))
                            ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status")) }}
                        </div>
                    <div class="form-group mb-0 calendarIcon">
                        {{ html()->text('from', isset($searchVariable['from']) ? $searchVariable['from'] : '')
                        ->attributes(['class' => 'form-control datepicker', 'onkeydown' => 'return false;', 'placeholder' => trans('messages.fromDate')]) }}
                    </div>
                    <div class="form-group mb-0 calendarIcon">
                        {{ html()->text('to', isset($searchVariable['to']) ? $searchVariable['to'] : '')
                        ->attributes(['class' => 'form-control datepicker', 'onkeydown' => 'return false;', 'placeholder' => trans('messages.toDate')]) }}
                    </div>
                </div>
                <div class="form-action ">
                    <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon" title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="{{ Route('admin.insuranceRenewal.list') }}" class="btn btn-sm border_btn btnIcon" title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
                        @can('INSURANCE_RENEWAL_ADD')
                        {{-- <a href="{{ Route('admin.insuranceRenewal.add') }}" class="btn theme_btn bg_theme btn-sm py-2 btnIcon" style="margin:0;" title="{{ trans('Add New Insurance Renewal') }}">
                             <em class='fa fa-add '></em></a> --}}
                        @endcan
                </div>
            </div>
            {{ html()->closeModelForm() }}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>{{ trans('messages.insuranceRenewal') }} {{ trans('messages.list') }}</caption>
                    @if (!$result->isEmpty())
                    <thead>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.sNo'),
                                ['sortBy' => 'insurance_renewal_id', 'order' => $sortBy == 'insurance_renewal_id' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'insurance_renewal_id') @endphp
                        </th>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.fullName'),
                                ['sortBy' => 'full_name', 'order' => $sortBy == 'full_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'full_name') @endphp
                        </th>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.nic'),
                                ['sortBy' => 'nic', 'order' => $sortBy == 'nic' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'nic') @endphp
                        </th>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.carModel'),
                                ['sortBy' => 'car_model_name', 'order' => $sortBy == 'car_model_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'car_model_name') @endphp
                        </th>
                        <!-- <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.carModel'),
                                ['sortBy' => 'car_model', 'order' => $sortBy == 'car_model' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'car_model') @endphp
                        </th> -->
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.yearOfManufacturer'),
                                ['sortBy' => 'year_of_manufacturer', 'order' => $sortBy == 'year_of_manufacturer' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'year_of_manufacturer') @endphp
                        </th>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.vehicleRegistrationMark'),
                                ['sortBy' => 'vehicle_registration_mark', 'order' => $sortBy == 'vehicle_registration_mark' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'vehicle_registration_mark') @endphp
                        </th>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.value'),
                                ['sortBy' => 'value', 'order' => $sortBy == 'value' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'value') @endphp
                        </th>
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.sumToBeInsured'),
                                ['sortBy' => 'sum_to_be_insured', 'order' => $sortBy == 'sum_to_be_insured' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'sum_to_be_insured') @endphp
                        </th>
                       
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.cover_type'),
                                ['sortBy' => 'cover_type_name', 'order' => $sortBy == 'cover_type_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'cover_type_name') @endphp
                        </th>
                        
                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.InsurancePeriod'),
                                ['sortBy' => 'period_insurance_cover_name', 'order' => $sortBy == 'period_insurance_cover_name' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'period_insurance_cover_name') @endphp
                        </th>

                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('Premium Payable'),
                                ['sortBy' => 'premium_payable', 'order' => $sortBy == 'premium_payable' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'premium_payable') @endphp
                        </th>

                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('Comment'),
                                ['sortBy' => 'comment', 'order' => $sortBy == 'comment' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'comment') @endphp
                        </th>

                        <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.createdOn'),
                                [
                                    'sortBy' => 'created_at',
                                    'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc',
                                ],
                                $query_string,
                            ) }}

                            @php getSortIcon($sortBy,$order,'created_at') @endphp
                        </th>
                        <th scope="col">
                            {{ trans('messages.insuranceRenewal.RequestStatus') }}

                        </th>
                        <th scope="col">
                            {{ trans('messages.insuranceRenewal.Status') }}

                        </th>
                        <th scope="col">
                                        {{ trans('messages.action') }}
                       </th>

                        {{-- <th scope="col">
                            {{ link_to_route(
                                'admin.insuranceRenewal.list',
                                trans('messages.insuranceRenewal.Status'),
                                ['sortBy' => 'status', 'order' => $sortBy == 'status' && $order == 'desc' ? 'asc' : 'desc'],
                                $query_string
                            ) }}
                            @php getSortIcon($sortBy, $order, 'insurance_renewal.status') @endphp
                        </th> --}}
                    </thead>
                    <tbody>
                       <?php
                        //print_r($result);die;
                       ?>
                        @foreach ($result as $key => $value)
                        <tr>
                            <td>{{ $value->insurance_renewal_id }}</td>
                            <td>{{ $value->insuranceRenewal->full_name }}</td>
                            <td>{{ $value->insuranceRenewal->nic }}</td>
                            <td>{{ $value->insuranceRenewal->carModel->name ?? 'N/A' }}</td>
                            <td>{{ $value->insuranceRenewal->year_of_manufacturer }}</td>
                            <td>{{ $value->insuranceRenewal->vehicle_registration_mark }}</td>
                            <td>{{ $value->insuranceRenewal->value?'INR. '.$value->insuranceRenewal->value:'N/A' }}</td>
                            <td>{{ $value->insuranceRenewal->sum_to_be_insured?'INR. '.$value->insuranceRenewal->sum_to_be_insured:'N/A' }}</td>
                            <td>{{ $value->insuranceRenewal->coverType->name ?? 'N/A' }}</td>
                            <td>{{ $value->insuranceRenewal->periodInsuranceCover->name ?? 'N/A'}}</td>
                            <td>{{ $value->premium_payable ? 'INR. ' . $value->premium_payable : 'N/A' }}</td>
                            <td>{{ $value->comment ? $value->comment : 'N/A' }}</td>
                            <td> {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($value->created_at)) }}</td>
                            <td><?php
                                if($value->company_insurance_status == 0){ ?>
                                    <a class="btn btn-primary status_change" data-comment="{{ $value->comment }}" data-premium_payable="{{ $value->premium_payable }}" data-id="{{ $value->company_insurance_renewal_id }}" href="javascript::void(0);">
                                        Pending
                                    </a>
                                <?php
                                }elseif($value->company_insurance_status == 1){ ?>
                                    <a class="btn btn-primary status_change" data-comment="{{ $value->comment }}" data-premium_payable="{{ $value->premium_payable }}" data-id="{{ $value->company_insurance_renewal_id }}" href="javascript::void(0);">
                                        Premium amount quoted
                                    </a>
                                <?php
                                }elseif($value->company_insurance_status == 2){
                                    echo "Rejected";
                                }elseif($value->company_insurance_status == 3){ ?>
                                    <a class="btn btn-primary release_certificate" data-vehicle_id="{{ $value->vehicle_id }}"  data-id="{{ $value->company_insurance_renewal_id }}" href="javascript::void(0);">
                                        Release Certificate
                                    </a>
                                <?php
                                }else{
                                    echo "Confirmed";
                                }
                                ?>
                            </td>
                            <td>{{ ($value->company_insurance_status == 3) ? 'Active' : 'Inactive' }}</td>
                            <td>
                        <a title="{{ trans('messages.view') }}"
                            href="{{ URL::to('admin/insurance-management/insurance-renewal/view/' . $value->insurance_renewal_id) }}"
                            class="btn btn-warning mb-2">
                            <em class="fa fa-eye"></em>
                        </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <tr>
                        <td colspan="16" class="text-center">{{ trans('messages.noRecordFound') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="box-footer clearfix">

                @include('pagination.default', ['paginator' => $result])
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {

    $('.release_certificate').on('click', function() {
    var id = $(this).attr('data-id');
    var vehicle_id = $(this).attr('data-vehicle_id'); // Get the data-id attribute

    if (!id) {
        alert('Error: No ID provided.');
        return;
    }

    // Show a Swal prompt to upload a certificate file
    Swal.fire({
        title: "Upload Certificate",
        text: "Please upload the certificate file.",
        input: "file", // Input type for file upload
        inputAttributes: {
            accept: ".pdf" // Accept only PDF and image formats
        },
        showCancelButton: true,
        confirmButtonText: "Upload",
        cancelButtonText: "Cancel",
        preConfirm: (file) => {
            if (!file) {
                Swal.showValidationMessage(`You need to upload a certificate!`);
                return false;
            }
            return file; 
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var file = result.value; 
            var formData = new FormData();
            formData.append('id', id);
            formData.append('vehicle_id', vehicle_id);
            formData.append('certificate', file);

            // Send the certificate file to the server via AJAX
            $.ajax({
                url: "https://cruise-mu.com/public/admin/insurance-management/insurance-renewal/submit_certificate",
                method: "POST",
                data: formData,
                contentType: false, 
                processData: false, 
                success: function(response) {
                  //  console.log(response);
                    Swal.fire({
                        title: "Success!",
                        text: "Certificate uploaded successfully.",
                        icon: "success"
                    }).then(() => {
                     window.location.reload(); // Reload the page after success
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred while uploading the certificate.",
                        icon: "error"
                    });
                }
            });
        }
    });
});



    $('.status_change').on('click', function() {
        var id = $(this).attr('data-id'); // Get the data-id attribute
        var premium_payable = $(this).attr('data-premium_payable');
        var comment = $(this).attr('data-comment');
        
        if (!id) {
            alert('Error: No ID provided.');
            return;
        }

        // Show a Swal with two options: Accept or Decline
        Swal.fire({
            title: "Submit the premium payable",
            text: "Do you want to accept or decline?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Accept",
            cancelButtonText: "Decline",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If premium_payable is available, show it as a default value
                Swal.fire({
                    title: "Submit the premium payable",
                    input: "number",
                    inputLabel: "Enter the premium payable for the status change",
                    inputValue: premium_payable ? premium_payable : '', // Pre-fill with premium_payable if available
                    inputPlaceholder: "Enter price",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Submit",
                    preConfirm: (price) => {
                        if (!price) {
                            Swal.showValidationMessage(`Premium payable is required!`);
                        } else if (isNaN(price) || price <= 0) {
                            Swal.showValidationMessage(`Please enter a valid premium payable greater than zero.`);
                        } else {
                            return price; // Return valid price
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var price = result.value; // The price value entered by the user

                        // Prompt for comments, pre-fill with existing comment if available
                        Swal.fire({
                            title: "Add a comment",
                            input: "textarea",
                            inputLabel: "Enter any additional comments",
                            inputValue: comment ? comment : '', // Pre-fill with comment if available
                            inputPlaceholder: "Type your comment here...",
                            showCancelButton: true,
                            confirmButtonText: "Submit"
                        }).then((commentResult) => {
                            if (commentResult.isConfirmed) {
                                var comment = commentResult.value; // The comment entered by the user

                                // Send the price, ID, and comment to your server via AJAX
                                $.ajax({
                                    url: "https://cruise-mu.com/public/admin/insurance-management/insurance-renewal/submit-status-change",
                                    method: "POST",
                                    data: {
                                        id: id,
                                        price: price,
                                        comment: comment, // Send the comment
                                        status: 'accepted' // Send status as 'accepted'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: "Success!",
                                            text: "Status, price, and comment updated successfully.",
                                            icon: "success"
                                        }).then(() => {
                                            window.location.reload(); // Reload the page after success
                                        });
                                    },
                                    error: function(error) {
                                        console.log(error);
                                        Swal.fire({
                                            title: "Error",
                                            text: "An error occurred while updating status.",
                                            icon: "error"
                                        });
                                    }
                                });
                            }
                        });
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // If the user clicks Decline, proceed without asking for the price
                Swal.fire({
                    title: "Are you sure you want to decline?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Decline",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Prompt for comments, pre-fill with existing comment if available
                        Swal.fire({
                            title: "Add a comment",
                            input: "textarea",
                            inputLabel: "Enter any additional comments",
                            inputValue: comment ? comment : '', // Pre-fill with comment if available
                            inputPlaceholder: "Type your comment here...",
                            showCancelButton: true,
                            confirmButtonText: "Submit"
                        }).then((commentResult) => {
                            if (commentResult.isConfirmed) {
                                var comment = commentResult.value; // The comment entered by the user

                                // Send the decline status and comment to the server via AJAX
                                $.ajax({
                                    url: "https://cruise-mu.com/public/admin/insurance-management/insurance-renewal/submit-status-change",
                                    method: "POST",
                                    data: {
                                        id: id,
                                        comment: comment, // Send the comment
                                        status: 'declined' // Send status as 'declined'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: "Declined",
                                            text: "The status has been updated to Declined.",
                                            icon: "success"
                                        }).then(() => {
                                            window.location.reload(); // Reload the page after success
                                        });
                                    },
                                    error: function(error) {
                                        Swal.fire({
                                            title: "Error",
                                            text: "An error occurred while updating status.",
                                            icon: "error"
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    });
});


</script>
@endsection
