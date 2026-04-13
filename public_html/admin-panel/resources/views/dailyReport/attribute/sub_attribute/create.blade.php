{{ Form::open(['route' => 'mesurement.subattribute.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Hidden Category ID --}}
        {{ Form::hidden('attribute_id', $attribute->id) }}

        {{-- Sub Category Name --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Unit Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Unit Name'), 'required']) }}
            </div>
        </div>



    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
