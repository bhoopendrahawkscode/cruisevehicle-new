@extends('admin.layouts.default_layout')
@section('content')
    <?php
    if (!function_exists('appendDate')) {
        function appendDate()
        {
            $string = '';
            if (isset($_GET['from']) && $_GET['from'] != '') {
                $string = '&from=' . $_GET['from'];
            }
            if (isset($_GET['to']) && $_GET['to'] != '') {
                $string .= '&to=' . $_GET['to'];
            }
            return $string;
        }
    }
    ?>
    <div class="header d-flex align-items-center">
        <h1 class="page-header">
            Dashboard
        </h1>
        <ol class="breadcrumb ms-auto mb-0">
            <li><a href="javascript:void(0);"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        </ol>
    </div>
    <section id="page-inner" class="admin-dashboard">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body form_fields">
                        {{ html()->modelForm(null, 'GET')->route('admin.dashboard')->attributes([
                                'class' => 'd-flex gap-2 form-inline',
                                'role' => 'form',
                                'autocomplete' => 'off',
                                'onSubmit' => 'return checkDate();',
                            ])->open() }}
                        {{ html()->hidden('display') }}
                        <div class="form-group mb-0 calendarIcon">
                            {{ html()->text('from', isset($searchData['from']) ? $searchData['from'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.fromDate'),
                                ]) }}
                        </div>
                        <div class="form-group mb-0 calendarIcon">
                            {{ html()->text('to', isset($searchData['to']) ? $searchData['to'] : '')->attributes([
                                    'class' => 'form-control datepicker',
                                    'onkeydown' => 'return false;',
                                    'placeholder' => trans('messages.toDate'),
                                ]) }}
                        </div>
                        <div class="form-group mb-0 ">
                        <select class="form-control formValidate" name="model_id" id="model_id">
                            <option value="" <?= isset($_GET['model_id']) && $_GET['model_id'] == '' ? 'selected' : '' ?>>All other Model</option>
                            <option value="same_model" <?= isset($_GET['model_id']) && $_GET['model_id'] == 'same_model' ? 'selected' : '' ?>>Same Model</option>
                        </select>

                        </div>
                         
                            <div class="form-group mb-0 ">
                              
                            {{ html()->select('vehicle_id', ['' => 'Please select vehicle'] + $vehicle_data)->attributes(['class' => 'form-control formValidate']) }}

            
                            </div>
                        
                        <button type="submit" class="btn theme_btn bg_theme btn-sm"
                            title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em></button>
                        <a href="{{ Route('admin.dashboard') }}" title="{{ trans('messages.reset') }}"
                            class="btn btn-sm border_btn"><em class='fa fa-refresh '></em> </a>
                        {{ html()->closeModelForm() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            {{-- Total Number of Users --}}
            
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        @can('USER_LIST')
                            <a href="{{ route('admin.listUsers') }}?<?php echo appendDate(); ?>">
                        @endcan
                                <div class="number">
                                    <h3>
                                        <h3>{{ isset($totalUser) ? $totalUser : '' }}</h3>
                                        <small>{{ trans('messages.totalUsers') }}</small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-user fa-5x blue"></em>
                                </div>
                        @can('USER_LIST')
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            
                <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                    <div class="board">
                        <div class="panel panel-primary">
                            @can('VEHICLE_LIST')
                            <a href="{{ route('admin.vehicle.list') }}?<?php echo appendDate(); ?>">
                                @endcan
                                <div class="number">
                                    <h3>
                                        <h3>{{ isset($totalRegisteredVehicles) ? $totalRegisteredVehicles : '' }}</h3>
                                        <small>{{ trans('messages.registered_vehicle') }}</small>
                                    </h3>
                                </div>
                                <div class="icon">
                                    <em class="fa fa-car fa-5x fa-5x green"></em>
                                </div>
                                @can('VEHICLE_LIST')
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
           
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <canvas id="fuelGraph"></canvas>
    
<script>
    // Function to get query parameter from URL
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    const vehicleId = getQueryParam('vehicle_id');
    if (vehicleId) {
        document.getElementById('vehicle_id').value = vehicleId;
    }

   var ctx = document.getElementById('fuelGraph').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line', // or 'bar', 'pie', etc.
    data: {
        labels: {!! json_encode($graphData['months'] ?? []) !!}, // X-axis months
        datasets: [
            {
                label: 'Selected Vehicle Mileage',
                data: {!! json_encode($graphData['selectedVehicleMileage'] ?? []) !!}, // Y-axis mileage for selected vehicle
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.6,
               
            },
            {
                label: 'Other Vehicles Average Mileage',
                data: {!! json_encode($graphData['otherVehiclesAverageMileage'] ?? []) !!}, // Y-axis average mileage for other vehicles
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.6,
               
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

        {{-- @can('FUEL_EFFICIANCY_LIST')
        <div class="row gx-3">
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        <a href="javascript:void();">
                            <div class="number">
                                <h3>
                                    <h3>0</h3>
                                    <small>{{ trans('messages.analytics_fuel_cost') }}</small>
                                </h3>
                            </div>
                            <div class="icon">
                                <em class="fa fa-gas-pump fa-5x fa-5x yellow"></em>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            @can('FUEL_ANALYTICS_LIST')
            <div class="col-xl-4 col-sm-12 col-xs-12 col-md-6">
                <div class="board">
                    <div class="panel panel-primary">
                        <a href="javascript:void();">
                            <div class="number">
                                <h3>
                                    <h3>0</h3>
                                    <small>{{ trans('messages.analytics_fuel_efficiency') }}</small>
                                </h3>
                            </div>
                            <div class="icon">
                                <em class="fa fa-gas-pump fa-5x fa-5x red"></em>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endcan --}}


        </div>


    </section>
  
@stop