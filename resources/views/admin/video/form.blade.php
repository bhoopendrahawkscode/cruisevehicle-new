@php use App\Constants\Constant; @endphp
<div class="row">
    <div class="col-12 col-sm-6 mb-4 position-relative">
        <label class="d-block">{{ trans('messages.video') }}
            <span class="red_lab"> </span>
        </label>
    <div class="videoWrap">
        <div id="video-container">
        </div>
        <?php if (isset($data['recordId'])) { ?>
            <script> const edit = true; </script>
             <a href="javascript:void(0);" onclick="changeVideo();" class="videoEdit">{{trans("messages.edit")}}</a>
        <?php }else{ ?>
             <script> const edit = false; </script>
        <?php } ?>
    </div>
        {{ html()->hidden('duration')->attributes(['id'=>'duration']) }}
        {{ html()->hidden('video')->attributes(['id'=>'video']) }}
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
    <div class="col-12 col-sm-6 mb-4">
        <label>{{ trans('messages.category') }}
            <span class="red_lab"> *</span>
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
                video: {
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
    });
    $('#btnSubmit').on('click', function(e) {
        $("#<?php echo $formId; ?>").valid();
        removeTabError();
    });
</script>
@endif

<?php
if(!empty($tokenDetails['url'])){ ?>
    <script>
        $(document).ready(function() {
            $('#uploadWrap').hide();
            $('#formWrap').show();
            createVideoElement('<?php echo $tokenDetails['url']; ?>');

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
                    extensionVideo: "mp4",
                    fileSizeVideo: "20",
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
           createVideoElement(data);

            const routeUrl = "{{ route('admin.updateTokenVideo') }}";
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
                        alert("Video updated successfully. If you want to update any other video information Click on Submit Button.")
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
    function changeVideo(){
        $(".uploadWrap").show();
        $(".uploadWrapToHide").show();
        $("#formWrap").hide();
    }
    function createVideoElement(videoUrl){
        document.getElementById('video').value = videoUrl;
        videoUrl    =  S3_URL+videoUrl;
        const videoElement = document.createElement('video');
        videoElement.controls = true;
        videoElement.autoplay = true;
        const sourceMP4 = document.createElement('source');
        videoElement.width = 500;
        sourceMP4.src = videoUrl;
        videoElement.appendChild(sourceMP4);
        videoElement.onerror = function() {
            console.error('Error occurred while loading the video:', videoElement.error);
        };
        videoElement.onloadedmetadata = function() {
            const duration = videoElement.duration;
            document.getElementById('duration').value = duration;
        };
        $("#video-container").html("");
        document.getElementById('video-container').appendChild(videoElement);

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
