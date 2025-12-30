    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Media Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modalImageForm" action="#" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row d-flex">
                            <div class="col-6">
                                <img src="#" id="modalImageLink" width="100%" height="250px"
                                    alt="modalImage">
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="ModelImageName">Name*</label>
                                        <input type="text" class="form-control" name="name"
                                            id="ModelImageName">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="imageURL">URL</label>
                                        <input type="text" name="url" class="form-control" disabled
                                            id="imageURL" alt="mediaImage">

                                    </div>
                                    <div class="justify-content-end d-flex">
                                        <button class="btn btn-primary" id="CopyURLBtn">Copy URL to clipboard <em
                                                class="fa fa-copy"></em></button><br>
                                    </div>
                                    <div class="justify-content-center d-flex mt-3">
                                        <div id="copiedMsg" class="badge badge-primary d-none">You have successfully
                                            copied</div>

                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>

                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>