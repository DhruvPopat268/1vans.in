{{ Form::model($name_of_work, ['route' => ['name-of-work.update', $name_of_work->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp

    {{-- end for ai module--}}
    <div class="row">
            <div class="col-sm-12 col-md-12">

            <div class="form-group">
                {{ Form::label('daily_report_main_category_id', __('Main Category'), ['class' => 'form-label']) }}<x-required></x-required>
               {{ Form::select('daily_report_main_category_id', ['' => 'Select Category'] + $maincategory->toArray(), $name_of_work->daily_report_main_category_id, ['class' => 'form-control', 'required']) }}
              </div>
              </div>
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>

<!--        <div class="col-sm-12 col-md-12">-->
<!--    <div class="form-group">-->
<!--        {{ Form::label('unit_category_id', __('Category'), ['class' => 'form-label']) }}-->
<!--    {{ Form::select('unit_category_id', ['' => 'Select Category'] + $unitCategories->toArray(), $name_of_work->unit_category_id, ['class' => 'form-control']) }}-->
<!--    </div>-->
<!--</div>-->

 <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('total_mesurement', __('Total Quantity Of Work'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('total_mesurement', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        
           {{-- Start Date --}}
<div class="col-sm-12 col-md-12">
    <div class="form-group">
        {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::date('start_date', null, ['class' => 'form-control', 'required']) }}
    </div>
</div>

{{-- End Date --}}
<div class="col-sm-12 col-md-12">
    <div class="form-group">
        {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::date('end_date', null, ['class' => 'form-control', 'required']) }}
    </div>
</div>

    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

