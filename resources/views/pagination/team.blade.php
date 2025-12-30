<?php
	$link_limit = 2; 
?>
@if ($paginator->lastPage() > 1)
 <ul class="pagination team_pagination" role="navigation">
    <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ ($paginator->currentPage() == 1) ? 'javascript:void(0)' : $paginator->url($paginator->onFirstPage()) }}"> << </a>
    </li>
    <li class="page-item single_arrows {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ ($paginator->currentPage() == 1) ? 'javascript:void(0)' : $paginator->url($paginator->currentPage()-1) }}"> < </a>
    </li>
    <li class="paging_count"><div><strong>{{ $paginator->currentPage() }}</strong> of {{$paginator->lastPage()}}</div></li>
    <li  class="page-item single_arrows {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'javascript:void(0)' : $paginator->url($paginator->currentPage()+1) }}" > > </a>
    </li>
    <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'javascript:void(0)' : $paginator->url($paginator->lastPage()) }}" > >> </a>
    </li>
</ul>
@endif
