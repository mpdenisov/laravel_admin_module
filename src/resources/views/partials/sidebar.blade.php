<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">

            @role(\App\Models\Role::ADMIN)
            <li @if(Request::path() == config('admin.route').'/menu') class="active" @endif>
                <a href="{{ url(config('admin.route').'/menu') }}">
                    <i class="fa fa-list"></i>
                    <span class="title">{{ trans('Admin::admin.partials-sidebar-menu') }}</span>
                </a>
            </li>
            <li @if(Request::path() == 'users') class="active" @endif>
                <a href="{{ url('users') }}">
                    <i class="fa fa-users"></i>
                    <span class="title">{{ trans('Admin::admin.partials-sidebar-users') }}</span>
                </a>
            </li>
            <li @if(Request::path() == 'roles') class="active" @endif>
                <a href="{{ url('roles') }}">
                    <i class="fa fa-gavel"></i>
                    <span class="title">{{ trans('Admin::admin.partials-sidebar-roles') }}</span>
                </a>
            </li>
            <li @if(Request::path() == config('admin.route').'/files') class="active" @endif>
                <a href="{{ route('files') }}">
                    <i class="fa fa-gavel"></i>
                    <span class="title">Files</span>
                </a>
            </li>
            <li @if(Request::path() == config('admin.route').'/actions') class="active" @endif>
                <a href="{{ url(config('admin.route').'/actions') }}">
                    <i class="fa fa-users"></i>
                    <span class="title">{{ trans('Admin::admin.partials-sidebar-user-actions') }}</span>
                </a>
            </li>
            @endrole

            @foreach($menus as $menu)
                @if($menu->menu_type != 2 && is_null($menu->parent_id))
                    <li @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($menu->plural_name)) class="active" @endif>
                        <a href="{{ route(config('admin.route').'.'.strtolower($menu->plural_name).'.index') }}">
                            <i class="fa {{ $menu->icon }}"></i>
                            <span class="title">{{ $menu->title }}</span>
                        </a>
                    </li>
                @else
                    @if(!is_null($menu->children()->first()) && is_null($menu->parent_id))
                        <li>
                            <a href="#">
                                <i class="fa {{ $menu->icon }}"></i>
                                <span class="title">{{ $menu->title }}</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @foreach($menu['children'] as $child)
                                    <li
                                            @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($child->plural_name)) class="active active-sub" @endif>
                                        <a href="{{ route(strtolower(config('admin.route').'.'.$child->plural_name).'.index') }}">
                                            <i class="fa {{ $child->icon }}"></i>
                                            <span class="title">
                                                {{ $child->title  }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endif
            @endforeach
            <li>
                {!! Form::open(['url' => 'logout']) !!}
                <button type="submit" class="logout">
                    <i class="fa fa-sign-out fa-fw"></i>
                    <span class="title">{{ trans('Admin::admin.partials-sidebar-logout') }}</span>
                </button>
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</div>
