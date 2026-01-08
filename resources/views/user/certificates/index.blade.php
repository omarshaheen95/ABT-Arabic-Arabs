@extends('user.layout')
@push('style')
        <link rel="stylesheet" href="{{asset('user_assets/css/pages/certificates.css')}}" />
@endpush
@section('page-name', 'certificates')

@section('content')
    <div class="certificates-container">
        <!-- Header Section -->
        <header class="certificates-header">
            <div class="header-text">
                <h1 class="certificates-title">{{t('Certificates')}}</h1>
                <p class="certificates-subtitle">@if($type=='lessons') {{t('Lessons Certificates')}} @elseif($type=='stories') {{t('Stories Certificates')}} @else {{t('Motivation Certificates')}}  @endif</p>
            </div>
            <div class="header-actions">
                <button class="view-toggle-btn glass-button-component" id="viewToggleBtn" data-view="grid" aria-label="Switch to table view">
                    <img class="grid-icon" src="{{asset('user_assets/images/icons/grid.svg')}}" alt="Grid view" width="24" height="24">
                    <img class="table-icon" src="{{asset('user_assets/images/icons/list.svg')}}" alt="Table view" width="24" height="24" style="display: none;">
                </button>
                <select class="filter-dropdown" id="certificatesFilter">
                    <option value="{{route('certificate.index',['type' => 'lessons'])}}" @if($type=='lessons') selected @endif>{{t('Lessons Certificates')}}</option>
                    <option value="{{route('certificate.index',['type' => 'stories'])}}" @if($type=='stories') selected @endif>{{t('Stories Certificates')}}</option>
                    <option value="{{route('certificate.index',['type' => 'motivation'])}}" @if($type=='motivation') selected @endif>{{t('Motivation Certificates')}}</option>
                </select>
            </div>
        </header>

        @switch($type)
            @case('lessons')
            @include('user.certificates.components.lessons_certificates',['student_test' => $student_tests])
            @break

            @case('stories')
            @include('user.certificates.components.stories_certificates',['student_test' => $student_tests])
            @break

            @case('motivation')
            @include('user.certificates.components.motivation_certificates',['student_test' => $student_tests])
            @break
        @endswitch
    </div>

    @include('user.certificates.components.test_answer_dialog')
    @include('user.general.components.loading_dialog')
@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/certificates.js')}}"></script>
@endpush
