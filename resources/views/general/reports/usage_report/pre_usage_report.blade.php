<<<<<<< HEAD
@extends(request()->get('current_guard').'.layout.container')
=======
@extends(getGuard().'.layout.container')
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{ t('Students') }}
    </li>
@endpush
@section('title',$title)

@section('content')

    <div class="row">
<<<<<<< HEAD
        <form  action="{{route(request()->get('current_guard').'.report.usage_report')}}" id="filter">
=======
        <form  action="{{route(getGuard().'.report.usage_report')}}" id="filter">
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
            {{csrf_field()}}
            <div class="row kt-margin-b-20">
                <div class="col-lg-12 mb-2">
                    <label>{{t('Grade')}} :</label>
                    <select id="grades" name="grades[]" class="form-select grade" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                            data-allow-clear="true" multiple>
                        <option value="all" >{{t('All')}}</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 mb-2">
                    <label>{{t('Select date')}} :</label>
                    <input autocomplete="disabled" class="form-control form-control-solid" id="date_range" name="date_range_report" value="" placeholder="{{t('Pick date range')}}" />
                    <input type="hidden" name="start_date" id="start_date_range" />
                    <input type="hidden" name="end_date" id="end_date_range" />
                </div>

                <div class="separator my-4"></div>
                <div class="d-flex justify-content-end">
                    <button type="submit" id="save" class="btn btn-primary">{{ t('Get Report') }}</button>&nbsp;
                </div>


            </div>

        </form>
    </div>

@endsection
@section('script')
    <!-- DataTables -->
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){

            initializeDateRangePicker('date_range')


            onSelectAllClick('grades')

        });
    </script>
@endsection
