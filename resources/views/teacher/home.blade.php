@extends('teacher.layout.container')

@section('charts')
    <div class="row gy-5 g-xl-10 justify-content-center">
        <div class="col-sm-6 col-xl-2 mb-xl-10">
            <div class="card h-lg-100" style="background-color: #F1416C;background-image:url('/assets_v1/media/svg/shapes/wave-bg-red.svg')">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="d-flex justify-content-center w-100 m-0">
                        <div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);">
                            <i class="ki-duotone ki-profile-user fs-2hx text-white">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                                <i class="path4"></i>
                            </i>
                        </div>

                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5 align-items-center w-100">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-2x text-white lh-1 ls-n2">{{$school_students}} </span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="d-flex justify-content-center m-0">
                            <span class="fw-semibold fs-6 text-white text-center">{{t('School Students')}}</span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
        </div>

        <div class="col-sm-6 col-xl-2 mb-xl-10">
            <div class="card h-lg-100" style="background-color: #f141bf;background-image:url('/assets_v1/media/svg/shapes/wave-bg-red.svg')">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="d-flex justify-content-center w-100 m-0">
                        <div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);">
                            <i class="ki-duotone ki-note-2 fs-2hx text-white">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                                <i class="path4"></i>
                            </i>
                        </div>

                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5 align-items-center w-100">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-2x text-white lh-1 ls-n2">{{$tests}} </span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="d-flex justify-content-center m-0">
                            <span class="fw-semibold fs-6 text-white text-center">{{t('Students Tests')}}</span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
        </div>

        <div class="col-sm-6 col-xl-2 mb-xl-10">
            <div class="card h-lg-100" style="background-color: #1da2b7;background-image:url('/assets_v1/media/svg/shapes/wave-bg-red.svg')">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="d-flex justify-content-center w-100 m-0">
                        <div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);">
                            <i class="ki-duotone ki-user fs-2hx text-white">
                                <i class="path1"></i>
                                <i class="path2"></i>
                            </i>
                        </div>

                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5 align-items-center w-100">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-2x text-white lh-1 ls-n2">{{$students}} </span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="d-flex justify-content-center m-0">
                            <span class="fw-semibold fs-6 text-white text-center">{{t('Students')}}</span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
        </div>
    </div>
@endsection
