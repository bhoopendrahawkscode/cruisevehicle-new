<?php
use App\Services\GeneralService;
$link_limit = 6;
?>


    <div class="d-flex align-items-center">
        <div style="margin-right:15px;">
            {{ html()->modelForm(null, 'POST')->route("paginationLimitChange")
            ->attributes(['id'=>'paginationLimitChange','class'=>'form-inline','role'=>'form','autocomplete' => 'off','onSubmit'=>'return checkDate();'])->open() }}

             {{ html()->select('status',['5' => "Show 5 Per Page",'10' => "Show 10 Per Page",'20' => "Show 20 Per Page",'50' => "Show 50 Per Page",'100' => "Show 100 Per Page"],
             GeneralService::getSettings('pageLimit'))
             ->attributes(['class' => 'form-control form-control-dropdown','onChange'=>"document.getElementById('paginationLimitChange').submit();"]) }}

            {{ html()->closeModelForm() }}
        </div>

        <div>
            {{ trans("messages.showing") }} {{($paginator->currentpage()-1)*$paginator->perpage()+1}}
                {{ 'to' }} {{$paginator->currentpage()*$paginator->perpage()}}
                {{ 'of' }} {{$paginator->total()}} {{ trans("messages.entries") }}
        </div>
        @if ($paginator->lastPage() > 1)
            <ul class="pagination justify-content-end ms-auto my-0 me-0">
                <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                    <a class="page-link" href="{{ ($paginator->currentPage() == 1) ? 'javascript:void(0)' : $paginator->url($paginator->currentPage()-1) }}">{{ trans("messages.previous") }}</a>
                </li>

                @if($paginator->currentPage() > $link_limit + 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if($paginator->currentPage() > $link_limit + 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                @endif

                @for ($i = max(1, $paginator->currentPage() - $link_limit); $i <= min($paginator->lastPage(), $paginator->currentPage() + $link_limit); $i++)
                    <?php
                    $half_total_links = floor($link_limit / 2);
                    $from = $paginator->currentPage() - $half_total_links;
                    $to = $paginator->currentPage() + $half_total_links;
                    $shouldShowEllipsis = $paginator->lastPage() > $link_limit && ($i < $from || $i > $to);
                    ?>
                    @if ($shouldShowEllipsis && $i == 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    @if ($shouldShowEllipsis && $i == $paginator->lastPage() - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                        <a class="page-link" href='{{ $paginator->url("$i") }}'>{{ $i }}</a>
                    </li>
                    @endfor

                    @if($paginator->currentPage() < $paginator->lastPage() - $link_limit + 1)
                        @if($paginator->currentPage() < $paginator->lastPage() - $link_limit)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                            </li>
                            @endif

                            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                                <a class="page-link" href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'javascript:void(0)' : $paginator->url($paginator->currentPage()+1) }}">{{ trans("messages.next") }}</a>
                            </li>
            </ul>
            @endif
    </div>




