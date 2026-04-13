{{ Form::open(['route' => 'material.incoming.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

            {{--  <div class="form-group">
                <label>{{ __('Project') }}</label>
                <input type="number" name="project_id" class="form-control" required>
            </div>  --}}

            <div class="form-group">
                <label>{{ __('Challan Number') }}</label>
                <input type="text" name="challan_number" class="form-control" required>
            </div>

            <div class="form-group">
                <label>{{ __('Location') }}</label>
                <input type="text" name="location" class="form-control" required>
            </div>

             <div class="form-group">
                <label>{{ __('Vendor Name') }}</label>
                <input type="text" name="vendor_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>{{ __('Bill Number') }}</label>
                <input type="text" name="bill_number" class="form-control">
            </div>

            <div class="form-group">
                <label>{{ __('Vehicle Number') }}</label>
                <input type="text" name="vehicle_number" class="form-control" required>
            </div>

            <div class="form-group">
                <label>{{ __('Description') }}</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label>{{ __('Remark') }}</label>
                <input type="text" name="remark" class="form-control" required>
            </div>

            <div class="form-group">
                <label>{{ __('GST Number') }}</label>
                <input type="text" name="gst_number" class="form-control">
            </div>

            <div class="form-group">
                <label>{{ __('Batch Number') }}</label>
                <input type="text" name="batch_number" class="form-control">
            </div>

            <div class="form-group">
                <label>{{ __('E-Way Bill Number') }}</label>
                <input type="text" name="eway_bill_no" class="form-control">
            </div>

            <div class="form-group">
                <label>{{ __('E-Way Bill File') }}</label>
                <input type="file" name="eway_bill_file" class="form-control" accept=".jpeg,.png,.jpg,.pdf,.doc,.docx">
            </div>

            <div class="form-group">
                <label>{{ __('Royalty Slip Number') }}</label>
                <input type="text" name="royalty_slip_no" class="form-control">
            </div>

            <div class="form-group">
                <label>{{ __('Royalty Slip File') }}</label>
                <input type="file" name="royalty_slip_file" class="form-control" accept=".jpeg,.png,.jpg,.pdf,.doc,.docx">
            </div>

            <div class="form-group">
                <label>{{ __('Issue Status') }}</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="issue_status" id="issue_status_yes" value="Yes">
                    <label class="form-check-label" for="issue_status_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="issue_status" id="issue_status_no" value="No" checked>
                    <label class="form-check-label" for="issue_status_no">No</label>
                </div>
            </div>

            <div class="form-group" id="comment-box" style="display: none;">
                <label>{{ __('Comment') }}</label>
                <input type="text" name="comment" class="form-control">
            </div>


            <div class="form-group">
                <label>{{ __('Images') }}</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <hr>
            <h5>{{ __('Materials') }} (Category: <strong>{{ $category->name }}</strong>)</h5>
            <div id="material-wrapper">
                <div class="material-item row mb-2">
                    <input type="hidden" name="materials[0][category_id]" value="{{ $category->id }}">

                    <div class="col-md-6">
                        <label>{{ __('Sub Category') }}</label>
                        <select name="materials[0][sub_category_id]" class="form-control" required>
                            <option value="">{{ __('Select Sub Category') }}</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>{{ __('Stock') }}</label>
                        <input type="number" name="materials[0][stock]" class="form-control" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light mt-2" id="add-material">{{ __('Add More Material') }}</button>

        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    let materialIndex = 1;
    const subcategories = @json($subcategories);
    const categoryId = {{ $category->id }};
const maxMaterials = subcategories.length;

    $('#add-material').on('click', function () {
    if (materialIndex >= maxMaterials) {
        alert("You have added all available subcategories.");
        return;
    }

        let subcategoryOptions = `<option value="">{{ __('Select Sub Category') }}</option>`;
        subcategories.forEach(sub => {
            subcategoryOptions += `<option value="${sub.id}">${sub.name}</option>`;
        });

        const html = `
        <div class="material-item row mb-2">
            <input type="hidden" name="materials[${materialIndex}][category_id]" value="${categoryId}">
            <div class="col-md-6">
                <select name="materials[${materialIndex}][sub_category_id]" class="form-control" required>
                    ${subcategoryOptions}
                </select>
            </div>
            <div class="col-md-6">
                <input type="number" name="materials[${materialIndex}][stock]" class="form-control" placeholder="Stock" required>
            </div>
        </div>`;
        $('#material-wrapper').append(html);
        materialIndex++;
    });
    $('input[name="issue_status"]').on('change', function () {
        if ($(this).val() === 'Yes') {
            $('#comment-box').show();
        } else {
            $('#comment-box').hide();
        }
    });


    </script>
