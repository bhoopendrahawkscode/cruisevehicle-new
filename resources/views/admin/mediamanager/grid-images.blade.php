
@foreach ($media as $item)
    <div class="col-md-3 mb-3">
        <div class="card media_images">
            <img src="{{ Storage::url(config('constants.MEDIA_FOLDER').'/'.$item->image)}}" class="mb-auto" alt="..." width="100%" height="200p1x">
            <div class="card-body mt-auto justify-content-center d-flex">
                {{-- <input type="checkbox" name="selected_images[]" value="{{ $item->id }}" > --}}
                <em class="text-danger fa fa-trash" role="button"></em>
            </div>
            <em class="fa fa-eye text-white" role="button"></em>
        </div>
    </div>
@endforeach