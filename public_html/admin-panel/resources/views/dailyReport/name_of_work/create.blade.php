{{ Form::open(['url' => 'name-of-work', 'method' => 'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        
         <div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('daily_report_main_category_id', __('Main Category'), ['class'=>'form-label']) }}<x-required></x-required>
        {{ Form::select('daily_report_main_category_id', $maincategory, null, ['class' => 'form-control', 'placeholder' => __('Select Main Category'), 'required']) }}
    </div>
</div>

        {{-- Category Name --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required']) }}
            </div>
        </div>

        {{-- Unit Category --}}
<!--<div class="col-sm-6 col-md-12">-->
<!--    <div class="form-group">-->
<!--        {{ Form::label('unit_category_id', __('Category'), ['class'=>'form-label']) }}-->
<!--        {{ Form::select('unit_category_id', $unitCategories, null, ['class' => 'form-control', 'placeholder' => __('Select Category')]) }}-->
<!--    </div>-->
<!--</div>-->

{{-- Attribute --}}
<div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('mesurement_attribute_id', __('Types Of Measurements'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::select('mesurement_attribute_id', $attributes->toArray(), null, ['class' => 'form-control', 'placeholder' => __('Select Types Of Measurements'), 'id' => 'mesurement_attribute_id','required']) }}
    </div>
</div>

{{-- Sub Attribute --}}
<div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('mesurement_sub_attribute_id', __('Mesurement Unit'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::select('mesurement_sub_attribute_id', [], null, ['class' => 'form-control', 'placeholder' => __('Select Unit'), 'id' => 'mesurement_sub_attribute_id','required']) }}
    </div>
</div>
<div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('total_mesurement', __('Total Quantity Of Work'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('total_mesurement', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Quantity Of Work'), 'required']) }}
            </div>
        </div>
        
          {{-- Start Date --}}
<div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('start_date', __('Start Date'), ['class'=>'form-label']) }}<x-required></x-required>
        {{ Form::date('start_date', null, ['class' => 'form-control', 'required']) }}
    </div>
</div>

{{-- End Date --}}
<div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('end_date', __('End Date'), ['class'=>'form-label']) }}<x-required></x-required>
        {{ Form::date('end_date', null, ['class' => 'form-control', 'required']) }}
    </div>
</div>



    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>

{{ Form::close() }}
<script>
        $('select[name="mesurement_attribute_id"]').change(function() {
            var attributeId = $(this).val();  // Get selected company ID

            if(attributeId) {
                // Fetch the filtered groups via AJAX
                $.ajax({
                    url: "{{ url('get-sub-attributes') }}/" + attributeId,
                    type: 'GET',
                    success: function(data) {
                        var group_idSelect = $('select[name="mesurement_sub_attribute_id"]');
                        group_idSelect.empty();  // Clear the current options

                        // Add a default "Select Group" option
                        group_idSelect.append('<option value="">{{ __("Select Sub Attribute") }}</option>');

                        // Add new options to the dropdown
                        $.each(data, function(key, value) {
                            group_idSelect.append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            } else {
                // If no company is selected, reset the group_id dropdown
                $('select[name="mesurement_sub_attribute_id"]').html('<option value="">{{ __("Select a Attribute first") }}</option>');
            }
        });

    </script>

