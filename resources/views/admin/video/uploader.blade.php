
<div class="uploadWrap" id="uploadWrap" onclick="setErrorEmpty()">
    <form id="uploaderForm">
        <div class="uploadCircle uploadWrapToHide"> <em class="fa-solid fa-upload"></em><input accept="video/*" name="fileInput" id="fileInput" type="file" /></div>
        <div class="uploadText uploadWrapToHide">
            <strong>{{trans("messages.dragDropFileVideo")}}</strong>
        </div>
        <a href="#" class="btn uploadWrapToHide" onclick="document.getElementById('fileInput').click();">{{trans("messages.selectFile")}}</a>

        <div class="progress mt-5 progressBar" style="display:none;">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <p id="fileError"></p>
        <p class="progressBar" style="display:none;color:#f00;">
            {{trans("messages.videoIsUploading")}}
        </p>
    </form>
</div>
<script>
   function setErrorEmpty(){
        $('#fileError').html("");
    }
</script>
