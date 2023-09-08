<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('dashboard.index') }}">
            <span class="align-middle me-3">
                <span class="align-middle">{{ env('APP_NAME') }}</span>
            </span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                General
            </li>
            <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Manage
            </li>
            <li
                class="sidebar-item {{ !request()->is('users/*/details') && request()->is('users*') ? 'active' : '' }} ">
                <a data-target="#users" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="users"></i>
                    <span class="align-middle">Users</span>
                </a>
                <ul id="users"
                    class="sidebar-dropdown list-unstyled collapse {{ !request()->is('users/*/details') && request()->is('users*') ? 'show' : '' }}"
                    data-parent="#sidebar">
                    <li class="sidebar-item {{ request()->is('users') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('users.index') }}">
                            <i class="align-middle" data-feather="users"></i>
                            <span class="align-middle">All Users</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('users/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('users.create') }}">
                            <i class="align-middle" data-feather="user-plus"></i>
                            <span class="align-middle">Add New User</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="sidebar-item {{ request()->is('advisors*') || request()->is('advisor/create') ? 'active' : '' }} ">
                <a data-target="#advisors" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Advisors</span>
                </a>
                <ul id="advisors"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('advisors*') || request()->is('advisor/create') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('advisors') ? 'active' : '' }}">
                        <a class="sidebar-link" href="/advisors?role=2">
                            <i class="align-middle" data-feather="sliders"></i>
                            <span class="align-middle">All Advisors</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item {{ request()->is('users/create') || request()->is('advisor/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('advisor.create') }}">
                            <i class="align-middle" data-feather="user-plus"></i>
                            <span class="align-middle">Add New Advisor</span>
                        </a>
                    </li>


                </ul>
            </li>

            <li
                class="sidebar-item {{ request()->is('agencies*') || request()->is('agency/create') ? 'active' : '' }} ">
                <a data-target="#agencies" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="layers"></i>
                    <span class="align-middle">Agencies</span>
                </a>
                <ul id="agencies"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('agencies*') || request()->is('agency/create') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('agencies') ? 'active' : '' }}">
                        <a class="sidebar-link" href="/agencies?role=3">
                            <i class="align-middle" data-feather="layers"></i>
                            <span class="align-middle">All Agencies</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('agency/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('agency.create') }}">
                            <i class="align-middle" data-feather="user-plus"></i>
                            <span class="align-middle">Add New Agency</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li
                class="sidebar-item {{ request()->is('creatives*') || request()->is('creative/create') ? 'active' : '' }} ">
                <a data-target="#creatives" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="cloud"></i>
                    <span class="align-middle">Creatives</span>
                </a>
                <ul id="creatives"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('creatives*') || request()->is('creative/create') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('creatives') ? 'active' : '' }}">
                        <a class="sidebar-link" href="/creatives?role=4">
                            <i class="align-middle" data-feather="cloud"></i>
                            <span class="align-middle">All Creatives</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-item {{ request()->is('users/create') || request()->is('creative/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('creative.create') }}">
                            <i class="align-middle" data-feather="user-plus"></i>
                            <span class="align-middle">Add New Creative</span>
                        </a>
                    </li>

                </ul>
            </li>


            <li class="sidebar-item {{ request()->is('jobs*') ? 'active' : '' }} ">
                <a data-target="#jobs" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="list"></i>
                    <span class="align-middle">Jobs</span>
                </a>
                <ul id="jobs" class="sidebar-dropdown list-unstyled collapse {{ request()->is('jobs*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('jobs') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('jobs.index') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">All Jobs</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('jobs/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('jobs.create') }}">
                            <i class="align-middle" data-feather="list"></i>
                            <span class="align-middle">Add New Job</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('groups*') ? 'active' : '' }} ">
                <a data-target="#groups" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="globe"></i>
                    <span class="align-middle">Groups</span>
                </a>
                <ul id="groups"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('groups*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('groups') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('groups.index') }}">
                            <i class="align-middle" data-feather="globe"></i>
                            <span class="align-middle">All Groups</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('groups/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('groups.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New Group</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('posts*') ? 'active' : '' }} ">
                <a data-target="#posts" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="edit"></i>
                    <span class="align-middle">Posts</span>
                </a>
                <ul id="posts"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('posts*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('posts') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('posts.index') }}">
                            <i class="align-middle" data-feather="edit"></i>
                            <span class="align-middle">All Posts</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li
                class="sidebar-item {{ request()->is('locations*') || request()->is('state/create') ? 'active' : '' }} ">
                <a data-target="#locations" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="map-pin"></i>
                    <span class="align-middle">Locations</span>
                </a>
                <ul id="locations"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('locations*') || request()->is('state/create') || request()->is('city/create') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('locations') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('locations.index') }}">
                            <i class="align-middle" data-feather="map-pin"></i>
                            <span class="align-middle">All Locations</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('state/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('state.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New State</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('city/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('city.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New City</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('categories*') ? 'active' : '' }} ">
                <a data-target="#categories" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="layout"></i>
                    <span class="align-middle">Categories</span>
                </a>
                <ul id="categories"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('categories*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('categories') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('categories.index') }}">
                            <i class="align-middle" data-feather="layout"></i>
                            <span class="align-middle">All Categories</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('categories/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('categories.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New Category</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('industries*') ? 'active' : '' }} ">
                <a data-target="#industries" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="radio"></i>
                    <span class="align-middle">Industry Experiences</span>
                </a>
                <ul id="industries"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('industries*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('industries') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('industries.index') }}">
                            <i class="align-middle" data-feather="radio"></i>
                            <span class="align-middle">All Industries</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('industries/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('industries.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New Industry</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('medias*') ? 'active' : '' }} ">
                <a data-target="#medias" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="video"></i>
                    <span class="align-middle">Media Experiences</span>
                </a>
                <ul id="medias"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('medias*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('medias') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('medias.index') }}">
                            <i class="align-middle" data-feather="video"></i>
                            <span class="align-middle">All Media Experiences</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('medias/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('medias.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New Media</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('experiences*') ? 'active' : '' }} ">
                <a data-target="#years_of_experience_sidebar" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="crop"></i>
                    <span class="align-middle">Years Of Experience</span>
                </a>
                <ul id="years_of_experience_sidebar"
                    class="sidebar-dropdown list-unstyled collapse {{ request()->is('experiences*') ? 'show' : '' }}"
                    data-parent="#sidebar">

                    <li class="sidebar-item {{ request()->is('experiences') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('experiences.index') }}">
                            <i class="align-middle" data-feather="crop"></i>
                            <span class="align-middle">All Experiences</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('experiences/create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('experiences.create') }}">
                            <i class="align-middle" data-feather="plus-circle"></i>
                            <span class="align-middle">Add New Experience</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-header">
                Reports
            </li>
            <li class="sidebar-item {{ request()->is('reports') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('reports.index') }}">
                    <i class="align-middle" data-feather="trending-up"></i>
                    <span class="align-middle">Sales</span>
                </a>
            </li>

            <li class="sidebar-header">
                Personal
            </li>
            <li class="sidebar-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a class="sidebar-link" href="javascript:void(0);"
                        onclick="document.getElementById('logout-form').submit();">
                        <i class="align-middle" data-feather="log-out"></i>
                        <span class="align-middle">Logout</span>
                    </a>
                </form>
            </li>

        </ul>
    </div>
</nav>