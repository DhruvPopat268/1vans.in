{{ Form::model($material_incoming, ['route' => ['material.incoming.update', $material_incoming], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan = \App\Models\Utility::getChatGPTSettings();
    @endphp
    {{-- end for ai module--}}

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <label>{{ __('Issue Status') }}</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="issue_status" id="issue_status_yes" value="Yes"
                        {{ old('issue_status', $material_incoming->issue_status) == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label" for="issue_status_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="issue_status" id="issue_status_no" value="No"
                        {{ old('issue_status', $material_incoming->issue_status) == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label" for="issue_status_no">No</label>
                </div>
            </div>

            <div class="form-group mt-3" id="comment_box" style="display: none;">
                <label>{{ __('Comment') }}</label>
                <input type="text" name="comment" class="form-control" value="{{ old('comment', $material_incoming->comment) }}">
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    function toggleCommentBox() {
        const isYes = document.getElementById('issue_status_yes').checked;
        const commentBox = document.getElementById('comment_box');
        commentBox.style.display = isYes ? 'block' : 'none';
    }

    document.getElementById('issue_status_yes').addEventListener('change', toggleCommentBox);
    document.getElementById('issue_status_no').addEventListener('change', toggleCommentBox);

    // Initial state on page load
    window.addEventListener('DOMContentLoaded', toggleCommentBox);
</script>
