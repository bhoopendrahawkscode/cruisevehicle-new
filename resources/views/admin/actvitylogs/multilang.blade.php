<div class="mb-3 translation-tabs">
    <ul class="nav nav-tabs border-0" role="tabList">
        @foreach ($multi_lang_name as $lang)
            <li class="nav-item">
                <a class="nav-link language_tab {{ $lang == 'English' ? 'active' : null }}" id="tabId_{{ $lang }}"
                    data-bs-toggle="pill" href="#custom-tabs-{{ $lang }}" role="tab"
                    aria-selected="true">{{ $lang }}
                </a>
            </li>
        @endforeach

    </ul>

    <div class="tab-content" id="tab-parent">

        @foreach ($multi_lang_name as $lang)
            <div @class(['tab-pane','fade','active show'=>$lang=='English']) id="custom-tabs-{{ $lang }}" role="tabContent">
                @switch($activity->event)
                @case('Created')
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <caption>{{ trans('messages.properties') }}
                                </caption>
                                <thead>
                                    <th scope="col">{{ trans('messages.attribute') }}</th>
                                    <th scope="col">
                                        {{ trans('messages.values') }}
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($multi_lang_attributes_keys as $attr)  
                                    @if ($multi_lang_properties[$lang]['new'][$attr])
                                        
                                    <tr>
                                        <td><strong>{{ Str::title(Str::of($attr)->replace('_', ' ')) }}</strong>
                                        </td>
                                        <td>{{$multi_lang_properties[$lang]['new'][$attr]}}</td>
                                     
                                    </tr>
                                    @endif 

                                    @endforeach                                                           
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                @break
                @case('Deleted')
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <caption>{{ trans('messages.properties') }}
                                </caption>
                                <thead>
                                    <th scope="col">{{ trans('messages.attribute') }}</th>
                                    <th scope="col">
                                        {{ trans('messages.values') }}

                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($multi_lang_attributes_keys as $attr)   
                                               
                                    <tr>
                                        <td><strong>{{ucfirst($attr)}}</strong>
                                        </td>
                                        <td>{{$multi_lang_properties[$lang]['old'][$attr]}}</td>
                                     
                                    </tr>
                                    @endforeach                                                           
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                @break
                @case('Updated')
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
                                  
                                    @foreach ($multi_lang_attributes_keys as $attr)   
                                    @if($multi_lang_properties[$lang]['old'][$attr] !==$multi_lang_properties[$lang]['new'][$attr])                 
                                    <tr>
                                        <td><strong>{{ucfirst($attr)}}</strong>
                                        </td>

                                        <td>{{$multi_lang_properties[$lang]['old'][$attr]}}</td>
                                        <td>{{$multi_lang_properties[$lang]['new'][$attr]}}</td>

                                    </tr>
                                    @endif
                                          
                                    @endforeach                                                           
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                @break
            
            @break
    
        @default
            
    @endswitch

               

            </div>
        @endforeach


    </div>
</div>
