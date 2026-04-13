{{ Form::open(['url' => 'to-do-list', 'method' => 'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Engineer Dropdown --}}
        <div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('engineer_id', __('Engineer'), ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::select('engineer_id', $engineers, null, ['class' => 'form-control select', 'placeholder' => __('Select Engineer'), 'required']) }}
    </div>
</div>

        {{-- Task Category Name --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Task Category Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Task Category Name'), 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
