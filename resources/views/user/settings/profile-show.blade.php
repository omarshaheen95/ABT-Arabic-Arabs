@extends('user.layout.container_v2')

@section('title', 'User Profile')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">

                    <div class="text-center mt-3">
                        <img src="{{ $user->image ?? asset('assets/media/users/default.jpg') }}"
                             alt="Profile Picture"
                             class="rounded-circle shadow-lg p-2"
                             style="width: 140px; height: 140px; object-fit: fill;">
                    </div>
                    <hr>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Name')}}</div>
                            <div class="col-md-8">{{ $user->name ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Grade')}}</div>
                            <div class="col-md-8">{{ $user->grade->name ?? '—' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('School')}}</div>
                            <div class="col-md-8">{{ $user->school->name ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Teacher')}}</div>
                            <div class="col-md-8">{{ $user->teacher->name ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Email')}}</div>
                            <div class="col-md-8">{{ $user->email ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Mobile')}}</div>
                            <div class="col-md-8">{{ $user->mobile ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Gender')}}</div>
                            <div class="col-md-8 text-capitalize">{{ $user->gender ?? '—' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Active to')}}</div>
                            <div class="col-md-8">
                                {{ \Carbon\Carbon::parse($user->active_to)->format('d M Y')}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{t('Joined On')}}</div>
                            <div class="col-md-8">{{ $user->created_at->format('d M Y') }}</div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
