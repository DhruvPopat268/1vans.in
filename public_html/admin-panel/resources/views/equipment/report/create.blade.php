{{ Form::open(['route' => 'equipment.report.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>{{ __('Location') }}</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="form-group">
                <label>{{ __('Description') }}</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div id="equipment-wrapper">
                <div class="row mb-2 equipment-item">
                    <div class="col-md-4">
                        <select name="equipment[0][equipment_id]" class="form-control equipment-select" required>
                            <option value="">{{ __('Select Equipment') }}</option>
                            @foreach($equipments as $equipment)
                                <option value="{{ $equipment->id }}" data-rate="{{ $equipment->rate }}">{{ $equipment->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="time" name="equipment[0][start_time]" class="form-control start-time" required>
                    </div>
                    <div class="col-md-2">
                        <input type="time" name="equipment[0][end_time]" class="form-control end-time" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="equipment[0][total_hours]" class="form-control total-hours" readonly>
                    </div>
                    <input type="hidden" name="equipment[0][rate]" class="equipment-rate">
                </div>


            </div>
            <button type="button" class="btn btn-sm btn-light" id="add-equipment">{{ __('Add More Equipment') }}</button>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
<script>
    const maxEquipments = {{ count($equipments) }};
</script>

<script>
    let equipmentIndex = 1;

    document.getElementById('add-equipment').addEventListener('click', function () {
        const currentCount = document.querySelectorAll('.equipment-item').length;

        if (currentCount >= maxEquipments) {
            alert("You can't add more equipment. Maximum limit reached.");
            return;
        }

        const wrapper = document.getElementById('equipment-wrapper');
        const html = `
            <div class="row mb-2 equipment-item">
                <div class="col-md-4">
                    <select name="equipment[${equipmentIndex}][equipment_id]" class="form-control equipment-select" required>
                        <option value="">{{ __('Select Equipment') }}</option>
                        @foreach($equipments as $equipment)
                            <option value="{{ $equipment->id }}" data-rate="{{ $equipment->rate }}">{{ $equipment->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="time" name="equipment[${equipmentIndex}][start_time]" class="form-control start-time" required>
                </div>
                <div class="col-md-2">
                    <input type="time" name="equipment[${equipmentIndex}][end_time]" class="form-control end-time" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="equipment[${equipmentIndex}][total_hours]" class="form-control total-hours" readonly>
                </div>
                <input type="hidden" name="equipment[${equipmentIndex}][rate]" class="equipment-rate">
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        equipmentIndex++;
    });


document.addEventListener('change', function (e) {
    if (e.target.classList.contains('start-time') || e.target.classList.contains('end-time')) {
        const row = e.target.closest('.equipment-item');
        const startVal = row.querySelector('.start-time').value;
        const endVal = row.querySelector('.end-time').value;
        const hoursInput = row.querySelector('.total-hours');

        if (startVal && endVal) {
            const [startHour, startMin] = startVal.split(':').map(Number);
            const [endHour, endMin] = endVal.split(':').map(Number);

            const start = startHour * 60 + startMin;
            const end = endHour * 60 + endMin;

            let diff = end - start;
            if (diff < 0) diff += 24 * 60; // handle overnight times

            const totalHours = Math.floor(diff / 60); // hours part
            const totalMinutes = diff % 60; // minutes part

            hoursInput.value = `${totalHours} hours ${totalMinutes} minutes`;
        } else {
            hoursInput.value = '';
        }
    }

    if (e.target.classList.contains('equipment-select')) {
        const rate = e.target.options[e.target.selectedIndex].dataset.rate;
        e.target.closest('.equipment-item').querySelector('.equipment-rate').value = rate;
    }
});
    </script>
