@extends('inc.master')

@section('head')

<title>Add Attendance Setting</title>
<style>
    /* label{
        font-size: 1.2rem;
    } */
</style>
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pagebody">
        <div class="br-section-wrapper pt-4">
            <div class="row">
                <div class="col-md-6">

                        <h6 class="br-section-label text-center mb-1">Add Attendance Setting</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form action="{{route('attendance_setting.store')}}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Delay Time: <span class="tx-danger">*</span></label>
                                         <div style="position:relative;">
                                            <div id="delayTime_text" style="background:#fff;padding: .375rem .75rem;border: var(--bs-border-width) solid var(--bs-border-color);"></div>
                                            <input style="position: absolute;top: 0;left: 0;opacity: 0;" type="text" class=" form-control" id="delayTime" name="delayTime" autocomplete="off" required>
                                        </div>
                                        @error('delayTime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Entry Last Time: <span class="tx-danger">*</span></label>
                                        <div style="position:relative;">
                                            <div id="entry_last_time_text" style="background:#fff;padding: .375rem .75rem;border: var(--bs-border-width) solid var(--bs-border-color);"></div>
                                            <input style="position: absolute;top: 0;left: 0;opacity: 0;" type="text" class=" form-control" id="entry_last_time" name="entry_last_time" autocomplete="off" required>
                                        </div>
                                        @error('entry_last_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                    {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                                    <button class="btn btn-info" id="cus-submit-btn">Save</button>
                                    </div>
                                </div>
                            </form>

                        </div>


                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script type="text/javascript">
    const date = new Date();
    const time24 = date.getHours().toString().padStart(2,'0') + ':' + date.getMinutes().toString().padStart(2,'0');
    $("#delayTime_text").text(to12HourFormat(time24));
    $("#entry_last_time_text").text(to12HourFormat(time24));
    function to12HourFormat(time24) {
        let [hours, minutes] = time24.split(":");
        hours = parseInt(hours);

        const suffix = hours >= 12 ? "PM" : "AM";
        hours = hours % 12 || 12;

        return `${hours}:${minutes} ${suffix}`;
    }
    $("#delayTime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: time24, // safer Laravel format
        allowInput: true,
        onChange: function(selectedDates, timeStr) {
            $("#delayTime_text").text(to12HourFormat(timeStr));
        }
    });
    $("#entry_last_time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: time24, // safer Laravel format
        allowInput: true,
        onChange: function(selectedDates, timeStr) {
            $("#entry_last_time_text").text(to12HourFormat(timeStr));
        }
    });
</script>
@endsection

