@php use App\Constants\Constant; @endphp
<div class="row">
    <div class="col-12 col-sm-6 mb-4 position-relative">
        <label class="d-block">{{ trans('messages.audio') }}
            <span class="red_lab"> </span>
        </label>
    <div class="videoWrap">
        <div id="video-container">
        </div>
        <?php if (isset($data['recordId'])) { ?>
            <script> const edit = true; </script>
             <a href="javascript:void(0);" onclick="changeAudio();" class="AudioEdit">{{trans("messages.edit")}}</a>
        <?php }else{ ?>
             <script> const edit = false; </script>
        <?php } ?>
    </div>
        {{ html()->hidden('duration')->attributes(['id'=>'duration']) }}
        {{ html()->hidden('audio')->attributes(['id'=>'audio']) }}
    </div>
</div>
<div class=" translation-tabs">
    <ul class="nav nav-tabs border-0" role="tabList">
        @php $i = 0; @endphp
        @foreach ($languages as $laguageRowTop)
        <li class="nav-item">
            <a class="nav-link language_tab {{ $laguageRowTop->locale }}
                    @php if($i == 0){echo " active"; } @endphp" id="tabId{{ $laguageRowTop->id }}" data-bs-toggle="pill" href="#custom-tabs-{{ $laguageRowTop->id }}" role="tab" aria-selected="true">{{ $laguageRowTop->name }}
            </a>
        </li>
        @php $i++; @endphp
        @endforeach
    </ul>
    <div class="tab-content" id="tab-parent">
        @php $j = 0; @endphp
        @foreach ($languages as $languageRow)
        <div language="{{ $languageRow->id }}" class="tab-pane   fade @php if($j == 0){echo " active show"; } @endphp" id="custom-tabs-{{ $languageRow->id }}" role="tabContent">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('name[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.name') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.name') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('name[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_name formValidate',
                            'placeholder' => trans("messages.name")]) }}
                        @if ($errors->has('name.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('name.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="clearValidation form-group
                        {{ $errors->has('artist[' . $languageRow->id . ']') ? 'has-error': '' }}">
                        {{ html()->hidden('id[' . $languageRow->id . ']') }}
                        <label for=" {{ trans('messages.artist') }} {{ '(' . $languageRow->name . ')' }}">
                            {{ trans('messages.artist') }} <span class="d-none">{{ '(' . $languageRow->name . ')' }}</span>
                            <span class="red_lab"> *</span>
                        </label>
                        {{ html()->text('artist[' . $languageRow->id . ']')
                            ->attributes(['class' => 'form-control languages_artist formValidate',
                            'placeholder' => trans("messages.artist")]) }}
                        @if ($errors->has('artist.' . $languageRow->id))
                        <span class="invalid-feedback " role="alert">
                            <strong>{{ $errors->first('artist.' . $languageRow->id) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        @php $j++; @endphp
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 upload_img mb-5">
        <label>{{ trans('messages.image') }}
            @if(!isset($data['recordId']))
            <span class="red_lab">*</span>
            @endif
        </label>
        <input name="image" type="file" id="imageInput" accept="image/*">
        <span id="file-size-error" class="text-danger"></span>
        @if ($errors->has('image'))
        <span class="invalid-feedback error" role="alert">
            <strong>{{ $errors->first('image') }}</strong>
        </span>
        @endif
        <span class="imageHint" style="display: block">{{ trans("messages.$title.ImageHint") }}</span>
        <?php if (isset($data['recordId'])) { ?>
            <label class="exist_image">{{ trans('messages.existingImage') }}
            </label>
            <div class="old_img">
                <img alt="Image" class="border border-1" src="{{ $data['thumbImage'] }}" width="100">
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-2  col-md-6">
        <label class='image-preview'>{{ trans('messages.imagePreview') }}</label>
        <img id="image-preview" class="image-preview" alt="{{ trans('messages.imagePreview') }}" class="img-fluid rounded-circle" width="150px">
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6 mb-4">
        <label>{{ trans('messages.category') }}
            <span class="red_lab">*</span>
        </label>
        {{ html()->select('category',Constant::VIDEO_CATEGORY)
        ->attributes(['class' => 'form-control form-control-dropdown'])->placeholder(trans("messages.category")) }}
    </div>
</div>
@if (env('ENABLE_CLIENT_VALIDATION'))
<script>
    $(function() {
        window.formReference = $("#<?php echo $formId; ?>").validate({
            ignore: [],
            rules: {
                duration: {
                    required: true,
                },
                audio: {
                    required: true,
                },
                category: {
                    required: true,
                },
            },
            errorElement: "span",
            highlight: function(element) {
                $(element).parents('.form-group').addClass('error');
            },
            unhighlight: function(element) {
                let lang = $(element).parent().parent().parent().parent().attr('language');
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
                removeTabError2(lang);
            },
        });
        $('.languages_name').each(function(e) {
            $(this).rules('add', {
                required: true,
                minlength: 20,
                maxlength: 100,
                notNumber: true,
                messages: {
                    minlength: "{{ trans('messages.min20') }}",
                    maxlength: "{{ trans('messages.max100') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });
        $('.languages_artist').each(function(e) {
            $(this).rules('add', {
                required: true,
                notNumber: true,
                minlength: 2,
                maxlength: 30,
                messages: {
                    minlength: "{{ trans('messages.minGeneral') }}",
                    maxlength: "{{ trans('messages.maxGeneral') }}",
                    notNumber: "{{ trans('messages.notNumberMessage') }}"
                }
            })
        });

        $('#btnSubmit').on('click', function(e) {
            $("#<?php echo $formId; ?>").valid();
            removeTabError();
        });
        checkImage(false);
        $("#imageInput").change(function(e) {
            checkImage(true);
        });
    });
</script>
@endif
<?php if (isset($data['recordId'])) { ?>
    <script>
        $(function() {
            $("#imageInput").rules('add', {
                extension: "{{ Config::get('constants.validImageExtensions') }}",
                filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
            });
        });
    </script>
<?php } else { ?>
    <script>
        $(function() {
            $("#imageInput").rules('add', {
                required: true,
                extension: "{{ Config::get('constants.validImageExtensions') }}",
                filesize: "{{ Config::get('constants.maxImageSizeJs') }}",
            });

        });
    </script>
<?php } ?>
<?php
if(!empty($tokenDetails['url'])){ ?>
    <script>
        $(document).ready(function() {
            $('#uploadWrap').hide();
            $('#formWrap').show();
            createAudioElement('<?php echo $tokenDetails['url']; ?>');

        });
    </script>
<?php }
?>
<script>

    const baseUrl = '{{$npmServer}}';
    const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB chunk size
    const fileInput = document.querySelector('#fileInput');
    const uploadBtn = document.querySelector('#uploadBtn');
    const progressBar = document.querySelector('.progress-bar');
    let file, fileName, totalChunks, uploadId;

    // Listen for file input change event
    fileInput.addEventListener('change', async () => {

        $("#fileError").html();

        $("#uploaderForm").validate({
            ignore: [],
            rules: {
                fileInput: {
                    required: true,
                    extensionAudio: "mp3",
                    fileSizeAudio: "10",
                }
            },
            errorPlacement: function(error, element) {
                error.appendTo("#fileError"); // Place error message in the specified div
            }
        });
        const errorStatus  = $("#uploaderForm").validate().element("#fileInput");
        if(errorStatus == false) { return false };
        $.blockUI({ message: '' });
        file = fileInput.files[0];
        fileName = Date.now().toString() + "_" + file.name;
        totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        console.log("file ", file, "totalChunks ", totalChunks);
        console.log("fileName ", fileName);
        try {

            const startTime = new Date();
            // Initiate multipart upload
            const requestBody = { fileName };
            console.log("requestBody ", requestBody);
            const res = await fetch(`${baseUrl}/initiateUpload`, {
            method: 'POST',
            body: JSON.stringify(requestBody),
            headers: {
                'Content-Type': 'application/json',
            },
            });
            const { results } = await res.json();
            uploadId  =  results?.uploadId;
            console.log("uploadId ", uploadId);
            // Send file chunks
            const uploadPromises = [];
            let uploadedChunks = 0;
            let start = 0, end;
            for (let i = 0; i < totalChunks; i++) {
            end = start + CHUNK_SIZE;
            const chunk = file.slice(start, end);
            const formData = new FormData();
            formData.append('index', i);
            formData.append('totalChunks', totalChunks);
            formData.append('fileName', fileName);
            formData.append('file', chunk);
            var unblock = false;
            const uploadPromise = fetch(`${baseUrl}/partUpload?uploadId=${uploadId}`, {
                method: "POST",
                body: formData,
            }).then(() => {
                uploadedChunks++;
                const progress = Math.floor((uploadedChunks / totalChunks) * 100);
                if(unblock == false){
                    $.unblockUI();
                    unblock = true;
                    console.log("Single");
                }

                updateProgressBar(progress);
            });
            uploadPromises.push(uploadPromise);
            start = end;
            }

            await Promise.all(uploadPromises);

            // Complete multipart upload
            const completeRes = await fetch(`${baseUrl}/completeUpload?fileName=${fileName}&uploadId=${uploadId}`, { method: 'POST' });
            const { results:results2,success } = await completeRes.json();
            data    = results2?.data;
           createAudioElement(data);

            const routeUrl = "{{ route('admin.updateTokenAudio') }}";
            $.ajax({
                url: routeUrl,
                method: 'POST',
                data: {
                    "token": '{{$_GET['token']}}',
                    "url": data,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if(edit){
                        alert("Audio updated successfully. If you want to update any other audio information Click on Submit Button.")
                    }
                }
            });


            console.log("file link: ", data);
            if (!success) {
                throw new Error('Error completing upload');
            }
            // End the timer and calculate the time elapsed
            const endTime = new Date();
            const timeElapsed = (endTime - startTime) / 1000;
            console.log('Time elapsed:', timeElapsed, 'seconds');
            $('#uploadWrap').hide();
            $('#formWrap').show();
            //alert('File uploaded successfully');
            resetProgressBar();
        } catch (err) {
            console.log(err);
            alert('Error uploading file');
        }



    });
    function changeAudio(){
        $(".uploadWrap").show();
        $(".uploadWrapToHide").show();
        $("#formWrap").hide();
    }
    function createAudioElement(audioUrl){
        document.getElementById('audio').value = audioUrl;
        audioUrl    =  S3_URL+audioUrl;
        const audioElement = document.createElement('audio');
        audioElement.controls = true;
        audioElement.autoplay = true;
        const sourceMP4 = document.createElement('source');
        audioElement.width = 500;
        sourceMP4.src = audioUrl;
        audioElement.appendChild(sourceMP4);
        audioElement.onerror = function() {
            console.error('Error occurred while loading the video:', audioElement.error);
        };
        audioElement.onloadedmetadata = function() {
            const duration = audioElement.duration;
            document.getElementById('duration').value = duration;
        };
        $("#video-container").html("");
        document.getElementById('video-container').appendChild(audioElement);

    }
    function updateProgressBar(progress) {
        $(".progressBar").show();
        $(".uploadWrapToHide").hide();
        progressBar.style.width = progress + '%';
        progressBar.textContent = progress + '%';
        console.log("progress ", progress);
    }
    function resetProgressBar() {
        $(".progressBar").hide();
        progressBar.style.width = '0%';
        progressBar.textContent = '';
        fileInput.value = '';
    }

</script>
