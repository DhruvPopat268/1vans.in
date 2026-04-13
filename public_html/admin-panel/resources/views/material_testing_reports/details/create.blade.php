{{ Form::open(['url' => 'material-testing-reports-details/store', 'method' => 'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        {{-- Project Dropdown --}}
        {{ Form::hidden('material_testing_reports_id', $report->id) }}


        {{-- Drawing Name --}}
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('remark', __('Remark'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('remark', null, ['class' => 'form-control', 'placeholder' => __('Enter Remark'), 'required']) }}
            </div>
        </div>

        {{-- Image Upload --}}
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('file', __('Upload File'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::file('file', ['class' => 'form-control', 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
