{{ Form::open(['route' => 'material-reports.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
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
                <label>{{ __('Images') }}</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <hr>
            <h5>{{ __('Materials') }}</h5>
            <div id="material-wrapper">
                <div class="material-item row mb-2">
                    <div class="col-md-4">
                        <label>{{ __('Category') }}</label>
                        <select name="materials[0][category_id]" class="form-control category-select" data-index="0" required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>{{ __('Sub Category') }}</label>
                        <select name="materials[0][sub_category_id]" class="form-control subcategory-select" id="subcategory-select-0">
                            <option value="">{{ __('Select Sub Category') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
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

    // Add new material input block
    $('#add-material').on('click', function () {
        const html = `
        <div class="material-item row mb-2">
            <div class="col-md-4">
                <select name="materials[${materialIndex}][category_id]" class="form-control category-select" data-index="${materialIndex}" required>
                    <option value="">{{ __('Select Category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="materials[${materialIndex}][sub_category_id]" class="form-control subcategory-select" id="subcategory-select-${materialIndex}" required>
                    <option value="">{{ __('Select Sub Category') }}</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="materials[${materialIndex}][stock]" class="form-control" placeholder="Stock" required>
            </div>
        </div>`;
        $('#material-wrapper').append(html);
        materialIndex++;
    });

    // Fetch subcategories on category change
    $(document).on('change', '.category-select', function () {
        const categoryId = $(this).val();
        const index = $(this).data('index');
        const subcategorySelect = $(`#subcategory-select-${index}`);

        if (categoryId) {
            $.ajax({
                url: "{{ route('get.subcategories') }}",
                method: 'GET',
                data: { category_id: categoryId },
                success: function (response) {
                    subcategorySelect.empty().append('<option value="">{{ __("Select Sub Category") }}</option>');
                    $.each(response.sub_categories, function (id, name) {
                        subcategorySelect.append(`<option value="${id}">${name}</option>`);
                    });

                }
            });
        }
    });
    </script>

