{{ Form::open(['route' => 'material.subcategory.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Hidden Category ID --}}
        {{ Form::hidden('category_id', $category->id) }}

        {{-- Sub Category Name --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Sub Category Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Sub Category Name'), 'required']) }}
            </div>
        </div>

        {{-- Attribute Dropdown --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('attribute_id', __('Material Units'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('attribute_id', $attribute, null, ['class' => 'form-control select', 'placeholder' => __('Select Material Units'), 'required']) }}
            </div>
        </div>

        {{-- Price --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('price', __('Price'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Price'), 'required']) }}
            </div>
        </div>
        
         <div class="col-sm-6 col-md-12">
    <div class="form-group">
        {{ Form::label('status', __('Status'), ['class'=>'form-label']) }}<x-required></x-required>
        {{ Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => 'form-control', 'placeholder' => __('Select Status'), 'required']) }}
    </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
