@extends(getGuard().'.layout.container')

@push('breadcrumb')
    <li class="breadcrumb-item">
        {{ t('Students') }}
    </li>
@endpush
@section('title',$title)

@section('content')

    <div class="row">
        <form action="{{$url}}" id="filter">
            {{csrf_field()}}
            <div class="row kt-margin-b-20">
                @if(guardIs('supervisor'))
                    <div class="col-lg-12 mb-2">
                        <label>{{t('Teachers')}} :</label>
                        <select id="teachers" name="teacher_id" class="form-select grade" data-control="select2"
                                data-placeholder="{{t('Select Teacher')}}"
                                data-allow-clear="true">
                            <option></option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-lg-12 mb-2">
                    <label>{{t('Grade')}} :</label>
                    <select id="grades" name="grades[]" class="form-select grade" data-control="select2"
                            data-placeholder="{{t('Select Grade')}}"
                            data-allow-clear="true" multiple>
                        <option value="all">{{t('All')}}</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">{{ t('Year') }}:</label>
                    <select class="form-select" data-placeholder="{{t('Select Year')}}" data-control="select2"
                            data-allow-clear="true" name="year_id" id="year_id">
                        <option></option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 mb-2">
                    <label>{{t('Select date')}} :</label>
                    <div class="input-group">
                        <input autocomplete="disabled" class="form-control form-control-solid" id="date_range"
                               name="date_range_report" value="" placeholder="{{t('Pick date range')}}"/>
                        <button class="btn btn-light-danger" type="button" id="clear_date_range"
                                title="{{t('Clear')}}">&times;</button>
                    </div>
                    <div class="form-text">{{t('Leave empty to cover the whole year')}}</div>
                    {{-- Left empty on purpose: no date range means the full school year. --}}
                    <input type="hidden" name="start_date" id="start_date_range" value=""/>
                    <input type="hidden" name="end_date" id="end_date_range" value=""/>
                </div>

                <div class="col-lg-12 mb-2">
                    {{-- the hidden field makes sure the choice is always submitted,
                         so an unticked box is not read as "not answered" --}}
                    <input type="hidden" name="include_archived" value="0"/>
                    <div class="form-check form-check-custom">
                        <input class="form-check-input" type="checkbox" value="1" id="include_archived"
                               name="include_archived" checked/>
                        <label class="form-check-label ms-2" for="include_archived">
                            {{t('Include archived students')}}
                        </label>
                    </div>
                    <div class="form-text">{{t('Archived students kept their activity; unticking hides it from the report.')}}</div>
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
        $(document).ready(function () {

            // No default range: the report covers the whole year until the user picks one.
            initializeDateRangePicker('date_range')

            $('#clear_date_range').on('click', function () {
                $('#date_range').val('');
                $('#start_date_range').val('');
                $('#end_date_range').val('');
            });

            onSelectAllClick('grades')

        });
    </script>
@endsection
