<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{route('supervisor.home')}}">
            <img alt="Logo" src="{{!settingCache('logo')? asset('logo.svg'):asset(settingCache('logo'))}}"
                 class="h-45px app-sidebar-logo-default"/>
            <img alt="Logo" src="{{!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))}}"
                 class="h-30px app-sidebar-logo-minimize "/>
        </a>

        <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
             data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
             data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                 data-kt-menu="true" data-kt-menu-expand="false">


                <div class="menu-item">
                    <a class="menu-link @if(request()->url()==route('supervisor.home') ) active @endif"
                       href="{{ route('supervisor.home') }}">
                        <span class="menu-icon">
                      <i class="ki-duotone ki-chart-pie-simple fs-2">
                         <i class="path1"></i>
                         <i class="path2"></i>
                        </i>
                        </span>
                        <span class="menu-title">{{t('Home')}}</span>
                    </a>
                </div>

                @can('show teachers')
                    <div class="menu-item">
                        <a class="menu-link @if(Request::is(getGuard().'/teacher*'))active @endif "
                           href="{{ route(getGuard().'.teacher.index') }}">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                    </span>
                            <span class="menu-title">{{t('Teachers')}}</span>
                        </a>
                    </div>
                @endcan

                @can('teacher tracking')

                    <div class="menu-item">
                        <a class="menu-link @if(Request::is(getGuard().'/tracking_teachers*'))active @endif "
                           href="{{ route(getGuard().'.teacher.tracking') }}">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-graph-2 fs-2">
                                     <i class="path1"></i>
                                     <i class="path2"></i>
                                     <i class="path3"></i>
                                    </i>
                                </span>
                            <span class="menu-title">{{t('Track Teachers')}}</span>
                        </a>
                    </div>
                @endcan

                @can('show users')
                    <div class="menu-item">
                        <a class="menu-link {{menuLinkIsActive([getGuard().'/user/*',getGuard().'/user'])}}"
                           href="{{ route(getGuard().'.user.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                            </i>
                        </span>
                            <span class="menu-title">{{t('Students')}}</span>
                        </a>
                    </div>
                @endcan

                @if(Auth::guard('supervisor')->user()->hasAnyPermission(['show lesson tests','show story tests']))
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion {{Request::is('supervisor/lessons_tests')|| Request::is('supervisor/stories_tests') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-tablet-book fs-2">
                                                    <i class="path1"></i>
                                                    <i class="path2"></i>
                                                </i>
                                                </span>
											<span class="menu-title">{{t('Student Tests')}}</span>
											<span class="menu-arrow"></span>
										</span>
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            @can('show lesson tests')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is('supervisor/lessons_tests*') )active @endif"
                                       href="{{ route('supervisor.lessons_tests.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Lessons Tests')}}</span>
                                    </a>
                                </div>
                            @endcan
                            @can('show story tests')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is('supervisor/stories_tests*') )active @endif"
                                       href="{{ route('supervisor.stories_tests.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Stories Tests')}}</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endif

                @if(Auth::guard('supervisor')->user()->hasAnyPermission(['show user records']))
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion {{ Request::is('supervisor/stories_records*') ?'here show':''}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            </span>
											<span class="menu-title">{{t('Marking')}}</span>
											<span class="menu-arrow"></span>
										</span>
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            @can('show user records')

                                <div class="menu-item">
                                    <a href="{{route('supervisor.stories_records.index')}}"
                                       class="menu-link {{Request::is('supervisor/stories_records*')?'active':''}}">
                                                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Stories Recodes')}}</span>
                                    </a>
                                </div>
                            @endcan

                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endif

                @if(guardHasAnyPermission(['show lesson assignments','show story assignments']))
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion
                        {{menuIsActive([getGuard().'/lesson_assignment*',getGuard().'/story_assignment'])}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-some-files fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            </span>
											<span class="menu-title">{{t('Assignments')}}</span>
											<span class="menu-arrow"></span>
										</span>
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            @can('show lesson assignments')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is(getGuard().'/lesson_assignment*') )active @endif"
                                       href="{{ route(getGuard().'.lesson_assignment.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Lessons')}}</span>
                                    </a>
                                </div>
                            @endcan




                            @can('show story assignments')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is(getGuard().'/story_assignment*') )active @endif"
                                       href="{{ route(getGuard().'.story_assignment.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Stories')}}</span>
                                    </a>
                                </div>
                            @endcan

                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endif

                @if(guardHasAnyPermission(['show user story assignments','show user lesson assignments']))
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion
                        {{menuIsActive([getGuard().'/user_story_assignment*',getGuard().'/user_lesson_assignment*'])}}">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-some-files fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            </span>
											<span class="menu-title">{{t('Student Assignments')}}</span>
											<span class="menu-arrow"></span>
										</span>
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">

                            @can('show user lesson assignments')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is(getGuard().'/user_lesson_assignment*') )active @endif"
                                       href="{{ route(getGuard().'.user_lesson_assignment.index') }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title">{{t('Lessons')}}</span>
                                    </a>
                                </div>
                            @endcan

                            @can('show user story assignments')
                                <div class="menu-item">
                                    <a class="menu-link @if(Request::is(getGuard().'/user_story_assignment*') )active @endif"
                                       href="{{ route(getGuard().'.user_story_assignment.index') }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                        <span class="menu-title">{{t('Stories')}}</span>
                                    </a>
                                </div>
                            @endcan

                        </div>
                        <!--end:Menu sub-->
                    </div>
                @endif




                <div class="menu-item">
                    <a href="{{route('supervisor.report.pre_usage_report')}}"
                       class="menu-link {{request()->url() == route('supervisor.report.pre_usage_report')?'active':''}}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-graph-2 fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{t('General Report')}}</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{route('supervisor.report.teacher_pre_usage_report')}}"
                       class="menu-link {{request()->url() == route('supervisor.report.teacher_pre_usage_report')?'active':''}}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-graph-2 fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{t('Teacher Usage Report')}}</span>
                    </a>
                </div>


            </div>


            <!--end::Menu-->


        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
<!--end::Sidebar-->
