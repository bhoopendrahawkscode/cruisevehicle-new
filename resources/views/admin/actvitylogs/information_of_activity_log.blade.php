<div class="row mb-3">
    <div class="col-12 mb-3 border rounded p-3">
        <div class="modal-header  justify-content-center">
            <h4>{{ trans('messages.info_of_activity_logs') }}</h4>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-9">
                    <div class="mb-3">
                    <strong>{{ $activity_by }} : </strong>
                    {{ $activity->causer->full_name }} ({{ $activity->causer->roles->first()->name }})
                    </div>
                    <div class="mb-3">
                    <strong >{{ $activity_on }}: </strong>
                    {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($activity->created_at)) }}
                    </div>
                    <strong>{{ trans('messages.section') }} : </strong> {{ $activity->section_name }}


                </div>

                <div class="col-3">
                    <strong>{{ trans('messages.event') }}: </strong>
                    @switch($activity->event)
                        @case('Created')
                            <div class="badge badge-primary">{{ $activity->event }}</div>
                        @break

                        @case('Deleted')
                            <div class="badge badge-danger">{{ $activity->event }}</div>
                        @break

                        @case('Updated')
                            <div class="badge badge-warning">{{ $activity->event }}</div>
                        @break

                        @default
                    @endswitch

                </div>
            </div>
            @include('admin.actvitylogs.details_of_activity_log')
        </div>
    </div>
</div>