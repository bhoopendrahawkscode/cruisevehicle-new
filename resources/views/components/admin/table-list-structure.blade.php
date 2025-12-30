<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
          {{$table}}
            <!-- pagination start -->
            <div class="box-footer clearfix">

                @include('pagination.default', ['paginator' => $result])
            </div>
            <!-- pagination end -->
        </div>
    </div>
</div>