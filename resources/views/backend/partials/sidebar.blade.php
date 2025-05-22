<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="/dashboard" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset($website_settings->site_icon ?? 'No Site Icon') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset($website_settings->logo ?? 'No Site Icon') }}" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                {{-- Dashboard --}}
                @can('dashboard')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                            <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li>
                @endcan

                {{-- System Settings --}}
                @canany(['profile-settings.', 'security-settings.', 'notification-settings.', 'admin-settings'])
                    @php
                        $isSystemSettingsActive = Route::is('backend.edit') || Route::is('profile.edit');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isSystemSettingsActive ? '' : 'collapsed' }}"
                            href="#sidebarSystemSettings" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isSystemSettingsActive ? 'true' : 'false' }}"
                            aria-controls="sidebarSystemSettings">
                            <i class="ri-settings-3-line"></i>
                            <span data-key="t-system-settings">System Settings</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isSystemSettingsActive ? 'show' : '' }}"
                            id="sidebarSystemSettings">
                            <ul class="nav nav-sm flex-column">
                                @can('admin-settings')
                                    <li class="nav-item">
                                        <a href="{{ route('backend.edit') }}"
                                            class="nav-link {{ Route::is('backend.edit') ? 'active' : '' }}"
                                            data-key="t-system">Admin Settings</a>
                                    </li>
                                @endcan
                                @can('profile-settings')
                                    <li class="nav-item">
                                        <a href="{{ route('profile.edit') }}"
                                            class="nav-link {{ Route::is('profile.edit') ? 'active' : '' }}"
                                            data-key="t-profile">Profile Settings</a>
                                    </li>
                                @endcan
                                @can('security-settings')
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('security.edit') ? 'active' : '' }}"
                                            data-key="t-security">Security Settings</a>
                                    </li>
                                @endcan
                                @can('notification-settings')
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('notification.edit') ? 'active' : '' }}"
                                            data-key="t-notification">Notification Settings</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Role and Permission --}}
                @canany(['role.create', 'permission.create', 'user.create'])
                    @php
                        $isRolePermissionActive =
                            Route::is('permissions.index') || Route::is('roles.index') || Route::is('users.create');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRolePermissionActive ? '' : 'collapsed' }}"
                            href="#sidebarRolePermission" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isRolePermissionActive ? 'true' : 'false' }}"
                            aria-controls="sidebarRolePermission">
                            <i class="ri-shield-user-line"></i>
                            <span data-key="t-role-permission">Role Permission</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRolePermissionActive ? 'show' : '' }}"
                            id="sidebarRolePermission">
                            <ul class="nav nav-sm flex-column">
                                @can('role.create')
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}"
                                            class="nav-link {{ Route::is('roles.index') ? 'active' : '' }}"
                                            data-key="t-create-role">Create Role</a>
                                    </li>
                                @endcan
                                @can('permission.create')
                                    <li class="nav-item">
                                        <a href="{{ route('permissions.index') }}"
                                            class="nav-link {{ Route::is('permissions.index') ? 'active' : '' }}"
                                            data-key="t-create-permission">Create Permission</a>
                                    </li>
                                @endcan
                                @can('user.create')
                                    <li class="nav-item">
                                        <a href="{{ route('users.create') }}"
                                            class="nav-link {{ Route::is('users.create') ? 'active' : '' }}"
                                            data-key="t-create-user">

                                            @if (auth()->user()->hasRole('Super Admin'))
                                                Create User
                                            @else
                                                Approve Teacher
                                            @endif

                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Categories --}}
                @canany(['category-create', 'grade-create', 'teachergrades-create', 'location-create',
                    'school-campus-create', 'gradecategory-create', 'course-create', 'novel-create',
                    'novellessoonsfiles-create', 'novelunitlessons-create'])
                    @php
                        $isRolePermissionActive =
                            Route::is('main-category.*') ||
                            Route::is('grades.*') ||
                            Route::is('teachergrades.*') ||
                            Route::is('location.*') ||
                            Route::is('school-campus.*') ||
                            Route::is('gradecategory.*') ||
                            Route::is('course.*') ||
                            Route::is('novel.*') ||
                            Route::is('novelunitdetails.*') ||
                            Route::is('novelunitlessons.*') ||
                            Route::is('novellessoonsfiles.*');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isRolePermissionActive ? 'true' : 'false' }}"
                            aria-controls="sidebarCategory">
                            <i class="ri-list-unordered"></i>
                            <span data-key="t-category">Modules</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRolePermissionActive ? 'show' : '' }}"
                            id="sidebarCategory">
                            <ul class="nav nav-sm flex-column">
                                {{-- Categories --}}
                                @can('category-create')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('main-category.*') ? 'active' : '' }}"
                                            href="#categorySubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('main-category.*') ? 'true' : 'false' }}"
                                            aria-controls="categorySubmenu">
                                            Categories
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('main-category.*') ? 'show' : '' }}"
                                            id="categorySubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('main-category.index') }}" class="nav-link">Manage
                                                        Categories</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                {{-- Locations --}}
                                @can('location-create')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('location.*') ? 'active' : '' }}"
                                            href="#locationSubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('location.*') ? 'true' : 'false' }}"
                                            aria-controls="locationSubmenu">
                                            Locations
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('location.*') ? 'show' : '' }}"
                                            id="locationSubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('location.index') }}" class="nav-link">Add
                                                        Locations</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                {{-- School Campus --}}
                                @can('school-campus-create')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('school-campus.*') ? 'active' : '' }}"
                                            href="#campusSubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('school-campus.*') ? 'true' : 'false' }}"
                                            aria-controls="campusSubmenu">
                                            School Campus
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('school-campus.*') ? 'show' : '' }}"
                                            id="campusSubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('school-campus.index') }}" class="nav-link">Assign
                                                        Campus to Users</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                {{-- Grades --}}
                                @can('grade-create')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('grades.*') ? 'active' : '' }}"
                                            href="#gradeSubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('grades.*') ? 'true' : 'false' }}"
                                            aria-controls="gradeSubmenu">
                                            Grades
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('grades.*') ? 'show' : '' }}"
                                            id="gradeSubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('grades.index') }}" class="nav-link">Manage Grades</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                {{-- Grade Category --}}
                                @can('gradecategory-create')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('gradecategory.*') ? 'active' : '' }}"
                                            href="#gradeCategorySubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('gradecategory.*') ? 'true' : 'false' }}"
                                            aria-controls="gradeCategorySubmenu">
                                            Grade Category
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('gradecategory.*') ? 'show' : '' }}"
                                            id="gradeCategorySubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('gradecategory.index') }}" class="nav-link">Manage
                                                        Grade Categories</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                {{-- Teacher Grades --}}
                                @can('teachergrades-create')
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('teachergrades.*') ? 'active' : '' }}"
                                            href="#teacherGradeSubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('teachergrades.*') ? 'true' : 'false' }}"
                                            aria-controls="teacherGradeSubmenu">
                                            Teacher Grades
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('teachergrades.*') ? 'show' : '' }}"
                                            id="teacherGradeSubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('teachergrades.index') }}" class="nav-link">Assign
                                                        Grade to Teacher</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan

                                {{-- Course Syllabus --}}
                                @can('course-create')
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('course.index') ? 'active' : 'collapsed' }}"
                                            href="#sidebarCourse" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('course.index') ? 'true' : 'false' }}"
                                            aria-controls="sidebarCourse">

                                            <span data-key="t-course">Course</span>
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('course.index') ? 'show' : '' }}"
                                            id="sidebarCourse">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('course.index') }}"
                                                        class="nav-link {{ Route::is('course.index') ? 'active' : '' }}"
                                                        data-key="t-course-syllabus">Course Syllabus</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endcan


                                {{-- Novels --}}
                                @canany(['novel-create', 'novelunitdetails-create', 'novelunitlessons-create',
                                    'novellessoonsfiles-create'])
                                    <li class="nav-item">
                                        <a class="nav-link menu-link {{ Route::is('novel*') ? 'active' : '' }}"
                                            href="#novelSubmenu" data-bs-toggle="collapse" role="button"
                                            aria-expanded="{{ Route::is('novel*') ? 'true' : 'false' }}"
                                            aria-controls="novelSubmenu">
                                            Novels
                                        </a>
                                        <div class="collapse menu-dropdown {{ Route::is('novel*') ? 'show' : '' }}"
                                            id="novelSubmenu">
                                            <ul class="nav nav-sm flex-column">
                                                @can('novel-create')
                                                    <li class="nav-item">
                                                        <a href="{{ route('novel.index') }}" class="nav-link">Novels Unit</a>
                                                    </li>
                                                @endcan
                                                @can('novelunitdetails-create')
                                                    <li class="nav-item">
                                                        <a href="{{ route('novelunitdetails.index') }}"
                                                            class="nav-link">Novels</a>
                                                    </li>
                                                @endcan
                                                @can('novelunitlessons-create')
                                                    <li class="nav-item">
                                                        <a href="{{ route('novelunitlessons.index') }}" class="nav-link">Novel
                                                            Lessons</a>
                                                    </li>
                                                @endcan
                                                @can('novellessoonsfiles-create')
                                                    <li class="nav-item">
                                                        <a href="{{ route('novellessoonsfiles.index') }}" class="nav-link">Novel
                                                            Lessons Files</a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcanany
                            </ul>
                        </div>
                    </li>
                @endcanany




            </ul>
        </div>
    </div>


    <div class="sidebar-background"></div>
</div>
