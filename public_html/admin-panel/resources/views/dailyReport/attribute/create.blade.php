{{ Form::open(['url' => 'mesurement-attribute', 'method' => 'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Category Name --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Types Of Measurements'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Types Of Measurements'), 'required']) }}
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
