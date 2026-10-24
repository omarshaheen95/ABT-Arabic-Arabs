<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="<?php echo e(route('manager.home')); ?>">
            <img alt="Logo" src="<?php echo e(!settingCache('logo')? asset('logo.svg'):asset(settingCache('logo'))); ?>"
                 class="h-45px app-sidebar-logo-default"/>
            <img alt="Logo" src="<?php echo e(!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))); ?>"
                 class="h-30px app-sidebar-logo-minimize "/>
        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <!--begin::Minimized sidebar setup:
if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
    1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
    2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
    3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
    4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
}
-->
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

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show statistics')): ?>
                    <div class="menu-item">
                        <a class="menu-link <?php if(Request::is('manager/home*') ): ?> active <?php endif; ?>"
                           href="<?php echo e(route('manager.home')); ?>">
                            <span class="menu-icon">
                          <i class="ki-duotone ki-chart-pie-simple fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                            </i>
                            </span>
                            <span class="menu-title"><?php echo e(t('Dashboard')); ?></span>
                        </a>
                    </div>
                <?php endif; ?>

















                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show schools')): ?>
                        <div class="menu-item">
                            <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/user_role_and_permission/school/*',getGuard().'/school*'])); ?>"
                               href="<?php echo e(route(getGuard().'.school.index')); ?>">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-home-3 fs-2">
                                     <i class="path1"></i>
                                     <i class="path2"></i>
                                    </i>
                                </span>
                                <span class="menu-title"><?php echo e(t('Schools')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show supervisors')): ?>

                        <div class="menu-item">
                            <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/user_role_and_permission/supervisor/*',getGuard().'/supervisor*'])); ?>"
                               href="<?php echo e(route(getGuard().'.supervisor.index')); ?>">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-people fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                     <span class="path4"></span>
                                     <span class="path5"></span>
                                    </i>
                                </span>
                                <span class="menu-title"><?php echo e(t('Supervisors')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show teachers')): ?>
                        <div class="menu-item">
                            <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/user_role_and_permission/teacher/*',getGuard().'/teacher*'])); ?>"
                               href="<?php echo e(route(getGuard().'.teacher.index')); ?>">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                    </span>
                                <span class="menu-title"><?php echo e(t('Teachers')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher tracking')): ?>

                        <div class="menu-item">
                            <a class="menu-link <?php if(Request::is(getGuard().'/tracking_teachers*')): ?>active <?php endif; ?> "
                               href="<?php echo e(route(getGuard().'.teacher.tracking')); ?>">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-graph-2 fs-2">
                                     <i class="path1"></i>
                                     <i class="path2"></i>
                                     <i class="path3"></i>
                                    </i>
                                </span>
                                <span class="menu-title"><?php echo e(t('Track Teachers')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show users')): ?>
                        <div class="menu-item">
                            <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/user/*',getGuard().'/user'])); ?> "
                               href="<?php echo e(route(getGuard().'.user.index')); ?>">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user fs-2">
                             <i class="path1"></i>
                             <i class="path2"></i>
                            </i>
                        </span>
                                <span class="menu-title"><?php echo e(t('Students')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(guardHasAnyPermission(['show lesson tests','show story tests'])): ?>
                        <div data-kt-menu-trigger="click"
                             class="menu-item menu-accordion <?php echo e(menuIsActive([getGuard().'/lessons_tests',getGuard().'/lessons_tests/*',getGuard().'/stories_tests',getGuard().'/stories_tests/*'])); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-tablet-book fs-2">
                                                    <i class="path1"></i>
                                                    <i class="path2"></i>
                                                </i>
                                                </span>
											<span class="menu-title"><?php echo e(t('Student Tests')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show lesson tests')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/lessons_tests',getGuard().'/lessons_tests/*'])); ?>"
                                           href="<?php echo e(route(getGuard().'.lessons_tests.index')); ?>">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Lessons Tests')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show story tests')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/stories_tests',getGuard().'/stories_tests/*'])); ?>"
                                           href="<?php echo e(route(getGuard().'.stories_tests.index')); ?>">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Stories Tests')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!--end:Menu sub-->
                        </div>
                    <?php endif; ?>

                    <?php if(guardHasAnyPermission(['show lesson assignments','show story assignments'])): ?>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion
                        <?php echo e(menuIsActive([getGuard().'/lesson_assignment*',getGuard().'/story_assignment'])); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-some-files fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            </span>
											<span class="menu-title"><?php echo e(t('Assignments')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show lesson assignments')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php if(Request::is(getGuard().'/lesson_assignment*') ): ?>active <?php endif; ?>"
                                           href="<?php echo e(route(getGuard().'.lesson_assignment.index')); ?>">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Lessons')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>




                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show story assignments')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php if(Request::is(getGuard().'/story_assignment*') ): ?>active <?php endif; ?>"
                                           href="<?php echo e(route(getGuard().'.story_assignment.index')); ?>">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Stories')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <!--end:Menu sub-->
                        </div>
                    <?php endif; ?>

                    <?php if(guardHasAnyPermission(['show roles','show permissions'])): ?>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion
                        <?php echo e(menuIsActive([getGuard().'/role*',getGuard().'/permission'])); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                             <i class="ki-duotone ki-lock-3 fs-1">
                                             <span class="path1"></span>
                                             <span class="path2"></span>
                                             <span class="path3"></span>
                                            </i>
                                            </span>
											<span class="menu-title"><?php echo e(t('Roles & Permissions')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show roles')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php if(Request::is(getGuard().'/role*') ): ?>active <?php endif; ?>"
                                           href="<?php echo e(route(getGuard().'.role.index')); ?>">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                            <span class="menu-title"><?php echo e(t('Roles')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>



                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show permissions')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php if(Request::is(getGuard().'/permission*') ): ?>active <?php endif; ?>"
                                           href="<?php echo e(route(getGuard().'.permission.index')); ?>">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title"><?php echo e(t('Permissions')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <!--end:Menu sub-->
                        </div>
                    <?php endif; ?>

                    <?php if(guardHasAnyPermission(['show user story assignments','show user lesson assignments'])): ?>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion
                        <?php echo e(menuIsActive([getGuard().'/user_story_assignment*',getGuard().'/user_lesson_assignment*'])); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-some-files fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            </span>
											<span class="menu-title"><?php echo e(t('Student Assignments')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show user lesson assignments')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php if(Request::is(getGuard().'/user_lesson_assignment*') ): ?>active <?php endif; ?>"
                                           href="<?php echo e(route(getGuard().'.user_lesson_assignment.index')); ?>">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Lessons')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show user story assignments')): ?>
                                    <div class="menu-item">
                                        <a class="menu-link <?php if(Request::is(getGuard().'/user_story_assignment*') ): ?>active <?php endif; ?>"
                                           href="<?php echo e(route(getGuard().'.user_story_assignment.index')); ?>">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                            <span class="menu-title"><?php echo e(t('Stories')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <!--end:Menu sub-->
                        </div>
                    <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show lessons')): ?>
                    <div class="menu-item">
                        <a class="menu-link <?php if(Request::routeIs('manager.lesson.index') || Request::is('manager/assessment*')|| Request::is('manager/lesson/*')): ?>active <?php endif; ?> "
                           href="<?php echo e(route('manager.lesson.index')); ?>">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                </span>
                            <span class="menu-title"><?php echo e(t('Lessons')); ?></span>
                        </a>
                    </div>
                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show stories')): ?>
                    <div class="menu-item">
                        <a class="menu-link <?php if(Request::routeIs('manager.story.index') || Request::is('manager/story/*')): ?>active <?php endif; ?> "
                           href="<?php echo e(route('manager.story.index')); ?>">
                                <span class="menu-icon">
                                   <i class="ki-duotone ki-user-tick fs-2">
                                     <span class="path1"></span>
                                     <span class="path2"></span>
                                     <span class="path3"></span>
                                    </i>
                                </span>
                            <span class="menu-title"><?php echo e(t('Stories')); ?></span>
                        </a>
                    </div>
                <?php endif; ?>



                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show motivational certificate')): ?>
                        <div class="menu-item">
                            <a class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/motivational_certificates*'])); ?>"
                               href="<?php echo e(route(getGuard().'.motivational_certificates.index')); ?>">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-teacher fs-2">
                                 <span class="path1"></span>
                                 <span class="path2"></span>
                                </i>
                        </span>
                                <span class="menu-title"><?php echo e(t('Motivational Certificates')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(guardHasAnyPermission(['show hidden lessons','show hidden stories'])): ?>
                        <div data-kt-menu-trigger="click"
                             class="menu-item menu-accordion <?php echo e(Request::is(getGuard().'/hidden_lesson')|| Request::is(getGuard().'/hidden_story') ?'here show':''); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-book-open fs-2">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                        <i class="path3"></i>
                                        <i class="path4"></i>
                                    </i>
                                            </span>
											<span class="menu-title"><?php echo e(t('Hiding control')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show hidden lessons')): ?>
                                    <div class="menu-item">
                                        <a href="<?php echo e(route(getGuard().'.hidden_lesson.index')); ?>"
                                           class="menu-link <?php echo e(Request::is(getGuard().'/hidden_lesson*')?'active':''); ?>">
                                                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Hidden Lessons')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show hidden stories')): ?>
                                    <div class="menu-item">
                                        <a href="<?php echo e(route(getGuard().'.hidden_story.index')); ?>"
                                           class="menu-link <?php echo e(Request::is(getGuard().'/hidden_story*')?'active':''); ?>">
                                                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                            <span class="menu-title"><?php echo e(t('Hidden Stories')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <!--end:Menu sub-->
                        </div>
                    <?php endif; ?>

                <?php if(guardHasAnyPermission(['show user records'])): ?>
                    <div data-kt-menu-trigger="click"
                         class="menu-item menu-accordion <?php echo e(menuIsActive([getGuard().'/stories_records*'])); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                    </i>
                                            </span>
											<span class="menu-title"><?php echo e(t('Marking')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show user records')): ?>
                                <div class="menu-item">
                                    <a href="<?php echo e(route('manager.stories_records.index')); ?>"
                                       class="menu-link <?php echo e(menuLinkIsActive([getGuard().'/stories_records*'])); ?>">
                                                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                        <span class="menu-title"><?php echo e(t('Stories Recodes')); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!--end:Menu sub-->
                    </div>

                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show packages')): ?>
                    <div class="menu-item">
                        <a href="<?php echo e(route('manager.package.index')); ?>"
                           class="menu-link <?php echo e(Request::is('manager/package*')?'active':''); ?>">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-package fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                            </i>
                        </span>
                            <span class="menu-title"><?php echo e(t('Packages')); ?></span>
                        </a>
                    </div>
                <?php endif; ?>


                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion <?php echo e(Route::is('manager.year.*') ||Request::is('manager/activity-log*') || Request::is('manager/settings*') || Request::is('manager/text_translation') ||  Request::is('manager/import_files*') || Request::is('manager/login_sessions*'|| Request::is(getGuard().'/achievement_levels*')) ?'here show':''); ?>">
                                           <span class="menu-link">
                                                <span class="menu-icon">
                                                <i class="ki-duotone ki-setting-4 fs-2">
                                    </i>
                                            </span>
											<span class="menu-title"><?php echo e(t('Settings')); ?></span>
											<span class="menu-arrow"></span>
										</span>
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show achievement levels')): ?>
                            <div class="menu-item">
                                <a href="<?php echo e(route(getGuard().'.achievement_levels.index')); ?>"
                                   class="menu-link <?php echo e(Route::is(getGuard().'.achievement_levels.*')?'active':''); ?>">
                                            <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                    <span class="menu-title"><?php echo e(t('Achievement Levels')); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show years')): ?>
                            <div class="menu-item">
                                <a href="<?php echo e(route('manager.year.index')); ?>"
                                   class="menu-link <?php echo e(Route::is('manager.year.*')?'active':''); ?>">
                                <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                    <span class="menu-title"><?php echo e(t('Years')); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import files')): ?>
                            <div class="menu-item">
                                <a href="<?php echo e(route('manager.import_files.index')); ?>"
                                   class="menu-link <?php echo e(Request::is('manager/import_files*')?'active':''); ?>">
                        <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                    <span class="menu-title"><?php echo e(t('Import Files')); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show translation')): ?>
                            <div class="menu-item">
                                <a href="<?php echo e(route('manager.text_translation.index')); ?>"
                                   class="menu-link <?php echo e(Request::is('manager/text_translation*')?'active':''); ?>">
                                <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                    <span class="menu-title"><?php echo e(t('Text translation')); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show settings')): ?>
                            <div class="menu-item">
                                <a href="<?php echo e(route('manager.settings.general')); ?>"
                                   class="menu-link <?php echo e(Request::is('manager/settings*')?'active':''); ?>">
                                                <span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
                                    <span class="menu-title"><?php echo e(t('General Settings')); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show activity logs')): ?>
                            <div class="menu-item">
                                <a class="menu-link <?php if(Request::is('manager/activity-log*') ): ?>active <?php endif; ?>"
                                   href="<?php echo e(route('manager.activity-log.index')); ?>">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                    <span class="menu-title"><?php echo e(t('Activity Log')); ?></span>
                                </a>
                            </div>

                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show login sessions')): ?>
                            <div class="menu-item">
                                <a class="menu-link <?php if(Request::is('manager/login_sessions*') ): ?>active <?php endif; ?>"
                                   href="<?php echo e(route('manager.login_sessions.index')); ?>">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                    <span class="menu-title"><?php echo e(t('Login Sessions')); ?></span>
                                </a>
                            </div>

                        <?php endif; ?>
                    </div>
                    <!--end:Menu sub-->
                </div>


            </div>


            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
<!--end::Sidebar-->
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/layout/sidebar.blade.php ENDPATH**/ ?>