<div class="row">
    <div class="col-12 p-3">
        <div>
            @if ($activity->is_multi_language)
                @include('admin.actvitylogs.multilang')
            @else
                @switch($activity->event)
                    @case('Deleted')
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <caption>{{ trans('messages.properties') }}
                                    </caption>
                                    <thead>
                                        <th scope="col">{{ trans('messages.attribute') }}</th>
                                        <th scope="col">{{ trans('messages.values') }}</th>
                                    </thead>

                                    <tbody>

                                        @foreach ($keys as $key)
                                          @if($old[$key])
                                                <tr>
                                                    <td><strong>{{ Str::title(Str::of($key)->replace('_', ' ')) }}</strong>
                                                    </td>
                                                    @if ($key == 'status')
                                                        <td>{{ $old[$key] }}
                                                        </td>
                                                    @else
                                                        <td>{{ $old[$key] }}</td>
                                                       
                                                    @endif
                                                </tr>
                                                @endif
                                          
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    @break

                    @case('Created')
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <caption>{{ trans('messages.properties') }}
                                    </caption>
                                    <thead>
                                        <th scope="col">{{ trans('messages.attribute') }}</th>
                                        <th scope="col">{{ trans('messages.values') }}</th>
                                    </thead>

                                    <tbody>

                                        @foreach ($keys as $key)
                                          @if($new[$key])
                                            <tr>
                                                <td><strong>{{ Str::title(Str::of($key)->replace('_', ' ')) }}</strong>
                                                </td>

                                                @if ($key == 'status')
                                                    <td>{{ $new[$key] }}
                                                    </td>
                                                @else
                                                    <td>{{ $new[$key] }}</td>
                                                   
                                                @endif
                                            </tr>
                                          @endif
                                               
                                          
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    @endswitch

                    @if (count($old) > 0 && count($new) > 0)
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <caption>{{ trans('messages.properties') }}
                                        </caption>
                                        <thead>
                                            <th scope="col">{{ trans('messages.attribute') }}</th>
                                            <th scope="col">
                                                {{ trans('messages.old') }}
                                            </th>
                                            <th scope="col">{{ trans('messages.new') }}</th>
                                        </thead>

                                        <tbody>

                                            @foreach ($keys as $key)
                                                @if ($new[$key] != '')
                                                    <tr>
                                                        <td><strong>{{ Str::title(Str::of($key)->replace('_', ' ')) }}</strong>
                                                        </td>
                                                        @if ($key == 'status')
                                                            <td>{{ $old[$key] }}
                                                            </td>
                                                            <td>{{ $new[$key] }}
                                                            </td>
                                                        @else
                                                            <td>{{ $old[$key] }}</td>
                                                            <td>{{ $new[$key] }}</td>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>



</div>