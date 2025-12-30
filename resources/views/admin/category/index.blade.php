@extends('admin.layouts.default_layout')
@section('content')
@php
use App\Services\CommonService;
@endphp

<div class="header d-flex align-items-center">
    <h1 class="page-header">
        {{$title}} {{ trans("messages.list") }}
    </h1>
    <ol class="breadcrumb ms-auto mb-0">
        <li><a href="{{ Route('admin.dashboard') }}">
                <em class="fa fa-dashboard"></em>
                {{ trans("messages.dashboard") }}</a></li>
        <li class="active">{{$title}}</li>
    </ol>
</div>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body form_mobile">
            <div class="form_row">
                <div class="form_fields d-flex mb-0 gap-2">
                    <div class="form-group mb-0">
                        {{ html()->text('name',((isset($searchVariable['name'])) ?
						$searchVariable['name'] : ''))
                        ->attributes(['class' => 'form-control',
                        'placeholder' => trans("messages.searchBy")." ".trans('messages.name')]) }}
                    </div>
                </div>
                <div class="form-action ">
                    <button type="submit" title="{{ trans('messages.search') }}" onclick="highlight(document.getElementById('name').value);" class="btn theme_btn bg_theme btn-sm">
                        <em class="fa-solid fa-magnifying-glass"></em>
                    </button>
                    <a href="javascript:void(0);" title="{{ trans("messages.reset") }}" onClick="highlightAndReload()" class="btn btn-sm border_btn">
                        <em class='fa fa-refresh '></em>
                    </a>

                    <div class="form-action_status">
                        @can($addPermission)
                        <a href="{{ Route($addRoute)}}" title="{{ trans("messages.addNew") }} {{$title}}" class="btn theme_btn bg_theme btn-sm py-2">
                            <em class='fa fa-add '></em>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="form_row">
            <span class="pt-1 pb-1 categoryHint">{{__("messages.categoryHint")}}</span>
        </div>
        <div class="panel-body htmlSearch">
            @if(!$result->isEmpty())
            @foreach($result as $key => $record)

            @php
            $active = ' text-success ';
            if($record->status ==0){
            $active = " text-danger ";
            }
            if(isset($treeMain[$record->id]['children']) &&
            !empty($treeMain[$record->id]['children'])){
            echo '<ul class="myUL">
                <li style="padding-left:0px;"><span style="padding-left:0px;" class="caret">'.$record['name'].'<a class="editCategory" href="'.URL('/').
                                       '/admin/category-list/edit/'.
                                       $record['id'].'"><em class=" '.$active.' fa-regular fa-pen-to-square ms-2"></em></a></span>';
                    echo CommonService::categoryMenu($treeMain[$record->id]['children']);
                    echo '</li>
            </ul>';
            }else{
            echo '<p class="mb-0 pt-1">'.$record->name.'<a class="editCategory" href="'.URL('/').
                                       '/admin/category-list/edit/'.
                                       $record['id'].'"><em class=" '.$active.' fa-regular fa-pen-to-square ms-2"></em></a></p>';
            }

            @endphp

            @endforeach
            @else
            {{ trans("messages.noRecordFound") }}
            @endif

            <div class="box-footer clearfix">
                @include('pagination.default', ['paginator' => $result])
            </div>

        </div>
        <div class="panel-body norecordfound" style="display: none">
            <tr>
                <td colspan="6" class="text-center noRecord"><strong>
                        {{ trans("messages.noRecordFound") }} </strong></td>
            </tr>
        </div>
    </div>
</div>
<style>
    ul.myUL {
        list-style-type: none;
    }

    .myUL {
        margin: 0;
        padding: 0;
    }

    ul.myUL li span,
    ul.myUL li {
        list-style: none;
        padding: 10px;
    }

    ul.myUL li a {
        color: #000
    }

    .caret {
        cursor: pointer;
        user-select: none;
    }

    .caret::before {
        content: "\25B6";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .caret-down::before {
        transform: rotate(90deg);
    }

    .nested {
        display: none;
    }

    .active {
        display: block;
    }

    .badge:empty {
        display: inline;
    }

    mark.highlight {
        color: black;
        padding: 5px;
        background: cyan;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js" integrity="sha512-5CYOlHXGh6QpOFA/TeTylKLWfB3ftPsde7AnmhuitiTX4K5SqCLBeKro6sPS8ilsz1Q4NRx3v8Ko2IBiszzdww==" crossorigin="anonymous">
</script>


<script>
    function highlightAndReload() {
        highlight('');

        location.reload();
    }

    var toggler = document.getElementsByClassName("caret");
    var i;
    for (i = 0; i < toggler.length; i++) {
        toggler[i].parentElement.querySelector(".nested").classList.toggle("active");
        toggler[i].classList.add("caret-down");

        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }

    function highlight(searchTerm) {

        if (searchTerm == '') {
            $('.htmlSearch').show();
            $('.norecordfound').hide();
        } else {
            var ob = new Mark(document.querySelector(".htmlSearch"));
            ob.unmark();
            ob.mark(
                searchTerm, {
                    className: 'highlight',
                    separateWordSearch: true
                }
            );
            const highlightedElement = document.querySelector('.highlight');
            if (highlightedElement) {
                $('.htmlSearch').show();
                $('.norecordfound').hide();
                $('html, body').animate({
                    scrollTop: ($('.highlight:visible:first').offset().top - 150)
                }, 1000);

            } else {
                $('.norecordfound').show();
                $('.htmlSearch').hide();
            }
        }



    }
</script>
@stop
