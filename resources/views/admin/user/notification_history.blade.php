@extends('admin.layouts.default_layout')
@section('content')
<style type="text/css">
.show_more_css{
    color: white !important;
}
.show_more_css {
    display: inline-block;
    margin-top: 5px;
    word-wrap: normal; /* Prevents text breaking inside the button */
}

.show-more .excerpt {
    white-space: nowrap; /* Prevents breaking inside the excerpt */
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    max-width: 100%; /* Adjust to fit within the available table space */
}

.show-more .more-content {
    white-space: normal; /* Allows the full content to wrap properly */
    word-wrap: break-word; /* Breaks long words if necessary */
}

.show-more-text, .show-less-text {
    cursor: pointer;
}

</style>
<?php use App\Services\GeneralService; ?>
<div class="header d-flex align-items-center">
    <h1 class="page-header">
    {{ trans('messages.notificationHistory') }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}"><em class="fa fa-dashboard"></em> {{ trans('messages.dashboard') }}</a></li>
        <li class="active">{{ trans('messages.notificationHistory') }}</li>
    </ol>
</div>


<div id="page-inner">
    <div class="panel panel-default">
		<div class="panel-body form_mobile">
             {{ html()->modelForm(null, 'GET')->route('admin.ListNotification')
             ->attributes(['class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}
            {{ html()->hidden('display') }}
			<div class="form_row">
				<div class="form_fields d-flex mb-0 gap-2">
					<div class="form-group mb-0">
                        {{ html()->text('title',((isset($searchVariable['title'])) ?
						$searchVariable['title'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans('Search by title')]) }}
					</div>
					{{-- <div class="form-group mb-0 w-20p">
                        {{ html()->select('status',['1' => trans('messages.active'), '0' => trans('messages.inActive')],
						((isset($searchVariable['status'])) ? $searchVariable['status'] : ''))
                        ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.status")) }}
					</div> --}}
					<div class="form-group mb-0 calendarIcon">
                        {{ html()->text('from',((isset($searchVariable['from'])) ?
                        $searchVariable['from'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.fromDate')]) }}
					</div>
					<div class="form-group mb-0 calendarIcon">
						{{ html()->text('to',((isset($searchVariable['to'])) ?
                        $searchVariable['to'] : ''))
                        ->attributes(['class' => 'form-control datepicker','onkeydown'=>'return false;',
                        'placeholder' => trans('messages.toDate')]) }}
					</div>
				</div>
				 <div class="form-action ">
                    <button type="submit" class="btn theme_btn bg_theme btn-sm btnIcon" title="{{ trans('messages.search') }}"><em class="fa-solid fa-magnifying-glass"></em> </button>
                    <a href="{{ Route('admin.ListNotification') }}" class="btn btn-sm border_btn btnIcon" title="{{ trans('messages.reset') }}"><em class='fa fa-refresh '></em> </a>
               
                    <div class="form-action_status">

                    </div>
                </div>
			</div>
            {{ html()->closeModelForm() }}
		</div>
	</div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="@if (!$result->isEmpty()) table table-striped table-hover @endif">
                    <caption>{{ trans('messages.notificationList') }}</caption>
                    @if (!$result->isEmpty())
                    <thead>
                        <tr>
                            <th scope="col" class="w-5p">
                                {{ trans('messages.sNo') }}
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.ListNotification',
                                    trans('messages.title'),
                                    [
                                        'sortBy' => 'title',
                                        'order' => $sortBy == 'title' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'title') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.ListNotification',
                                    trans('messages.message'),
                                    [
                                        'sortBy' => 'description',
                                        'order' => $sortBy == 'description' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'description') @endphp
                            </th>
                            <th scope="col">
                                {{ link_to_route(
                                    'admin.ListNotification',
                                    trans('messages.sendTo'),
                                    [
                                        'sortBy' => 'send_to',
                                        'order' => $sortBy == 'send_to' && $order == 'desc' ? 'asc' : 'desc',
                                    ],
                                    $query_string,
                                ) }}
                                @php getSortIcon($sortBy,$order,'send_to') @endphp
                            </th>
                            <th scope="col" class="w-15p">
                                {{
                                    link_to_route(
                                        'admin.ListNotification',
                                        "Created Date",
                                        [
                                            'sortBy' => 'created_at',
                                            'order' => $sortBy == 'created_at' && $order == 'desc' ? 'asc' : 'desc'
                                        ],
                                        $query_string
                                    )
                                }}
                                    @php getSortIcon($sortBy,$order,'created_at') @endphp
                            </th>
                        </tr>
                    </thead>
                    @endif
                        <tbody>
                        @php
                        $i = 1;
                        if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
                        $page = $_REQUEST['page'];
                        $limit = GeneralService::getSettings('pageLimit');
                        $i = ($page - 1) * $limit + 1;
                        }
                        @endphp
                        @if (!$result->isEmpty())
                        @foreach ($result as $key => $record)
                        <tr>
                            <td>{{ $i }}
                                @php
                                $i++;
                                @endphp
                            </td>
                            <td>
                                <?php  echo   $record->title;
                                ?>
                            </td>
                            <td>
                                <span class="show-more" title="{{ trans('messages.view') }}">
                                    <span class="excerpt">{{ excerpt($record->description, 50) }}</span>
                                    <span class="more-content" style="display: none;">{{ $record->description }}</span>
                                    <span class="show-more-text show_more_css btn btn-primary" style="display: {{ strlen($record->description) > 50 ? 'inline-block' : 'none' }}">Show more</span>
                                    <span class="show-less-text show_more_css btn btn-primary" style="display: none;">Show less</span>
                                </span>
                            </td>
                            
                            <td>
                                {{ $record->send_to }}
                            </td>
                            <td>
                                {{ date(Config::get('constants.ADMIN_LISTING_DATE_FORMAT'), strtotime($record->created_at)) }}
                            </td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center noRecord"><strong>{{ "No record found!" }}</strong></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <!-- pagination start -->
                <div class="box-footer clearfix">

                    @include('pagination.default', ['paginator' => $result])
                </div>
                <!-- pagination end -->
            </div>
        </div>
    </div>
</div>
<script>
    
    $(document).ready(function () {
        $('.show-more').click(function(e){
            e.preventDefault();
            var $moreContent = $(this).find('.more-content');
            var $excerpt = $(this).find('.excerpt');
            var $showMoreText = $(this).find('.show-more-text');
            var $showLessText = $(this).find('.show-less-text');
            $excerpt.toggle();
            $moreContent.toggle();
            $showMoreText.toggle();
            $showLessText.toggle();
        });
    });


</script>
@stop
