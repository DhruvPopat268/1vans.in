{{ Form::model($engineer_attendance, [
    'route' => ['engineer-attendance.update', $engineer_attendance->id],
    'method' => 'PUT',
    'enctype' => 'multipart/form-data',
    'class' => 'needs-validation',
    'novalidate'
]) }}

<div class="modal-body">
    @php
        $plan = \App\Models\Utility::getChatGPTSettings();
    @endphp

    <div class="row">
        {{-- Check In Time --}}
        <div class="col-md-6 mb-3">
    {{ Form::label('check_in', __('Check In Time'), ['class' => 'form-label']) }}
    {{ Form::datetimeLocal('check_in',
        $engineer_attendance->check_in
            ? \Carbon\Carbon::parse($engineer_attendance->check_in)->format('Y-m-d\TH:i')
            : null,
        ['class' => 'form-control', 'readonly' => true, 'required' => true]) }}
    <div class="invalid-feedback">{{ __('Please select a valid check-in time.') }}</div>
</div>


        {{-- Check Out Time --}}
        <div class="col-md-6 mb-3">
            {{ Form::label('check_out', __('Check Out Time'), ['class' => 'form-label']) }}
            {{ Form::datetimeLocal('check_out',
                $engineer_attendance->check_out
                    ? \Carbon\Carbon::parse($engineer_attendance->check_out)->format('Y-m-d\TH:i')
                    : null,
                ['class' => 'form-control', 'required' => true]) }}
            <div class="invalid-feedback">{{ __('Please select a valid check-out time.') }}</div>
        </div>


    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
