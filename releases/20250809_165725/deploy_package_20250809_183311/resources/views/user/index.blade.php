@extends('layouts.admin')
@php
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
@endphp

@section('page-title')
    {{ __('Manage Users') }}
@endsection

@section('title')
    {{ __('Manage Users') }}
@endsection

@section('action-btn')
@can('create user')
    <div class="d-flex align-items-center justify-content-end gap-3 w-100">
        <div class="search-wrapper flex-grow-1" style="max-width: 420px;">
            <div class="search-container">
                <i class="ti ti-search search-icon"></i>
                <input type="text" class="search-input" id="clientSearch" placeholder="{{ __('Search by name or email...') }}">
                <button class="clear-search" id="clearSearch" style="display: none;">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>

        <div class="filter-wrapper">
            <div class="dropdown">
                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-filter"></i>
                    <span>{{ __('Filter') }}</span>
                </button>
                <ul class="dropdown-menu filter-menu">
                    <li><a class="dropdown-item filter-option active" href="#" data-filter="all">{{ __('All') }}</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="company">{{ __('Companies') }}</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="active">{{ __('Active Plans') }}</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="expired">{{ __('Expired Plans') }}</a></li>
                    <li><a class="dropdown-item filter-option" href="#" data-filter="lifetime">{{ __('Lifetime Plans') }}</a></li>
                </ul>
            </div>
        </div>

        <a href="#" data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true"
           data-bs-toggle="tooltip" title="{{ __('Create New Client') }}"
           data-title="{{ __('Create New Client') }}" class="add-client-btn">
            <i class="ti ti-plus"></i>
            <span>{{ __('Add Client') }}</span>
        </a>
    </div>
@endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Clients') }}</li>
@endsection

@section('content')
<style>
/* Design tokens */
:root { --primary: #667eea; --primary-2: #764ba2; --muted: #6b7280; --card-b: #ffffff; --border: #e5e7eb; }

.search-wrapper { position: relative; }
.search-container { position: relative; background: #fff; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,.08); border: 1px solid #e2e8f0; transition: .25s; overflow: hidden; }
.search-container:focus-within { box-shadow: 0 8px 28px rgba(102,126,234,.18); border-color: var(--primary); transform: translateY(-1px); }
.search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 18px; z-index: 2; }
.search-input { width: 100%; padding: 12px 42px 12px 44px; border: none; outline: none; background: transparent; font-size: 14px; color: #111827; }
.clear-search { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; padding: 6px; border-radius: 8px; }
.clear-search:hover { background: #f3f4f6; color: #4b5563; }

.filter-btn { display:flex; align-items:center; gap:8px; padding: 10px 14px; background:#fff; border:1px solid #e5e7eb; border-radius:14px; color:#374151; font-size:14px; font-weight:500; box-shadow:0 2px 10px rgba(0,0,0,.05); transition:.2s; }
.filter-btn:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-1px); box-shadow:0 6px 16px rgba(102,126,234,.12); }
.filter-menu { border:none; border-radius:12px; box-shadow:0 12px 28px rgba(0,0,0,.12); padding:8px 0; min-width: 180px; }
.filter-option { padding:10px 16px; font-size:14px; color:#374151; }
.filter-option.active { background: var(--primary); color:#fff; }

.add-client-btn { display:flex; align-items:center; gap:8px; padding:12px 18px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%); color:#fff; border:0; border-radius:14px; font-size:14px; font-weight:600; text-decoration:none; box-shadow: 0 6px 18px rgba(102,126,234,.35); transition:.25s; }
.add-client-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(102,126,234,.45); color:#fff; }

.clients-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; padding: 18px 0; }
.client-card { background: var(--card-b); border-radius: 16px; border:1px solid var(--border); box-shadow: 0 8px 28px rgba(0,0,0,.06); overflow:hidden; transition:.25s; position:relative; }
.client-card:hover { transform: translateY(-6px); box-shadow: 0 14px 36px rgba(0,0,0,.10); }
.card-head { padding: 16px; background: linear-gradient(135deg, rgba(102,126,234,.12), rgba(118,75,162,.12)); display:flex; align-items:center; justify-content:space-between; }
.chip { background: rgba(59,130,246,.1); color:#1f2937; border-radius:999px; font-size:11px; font-weight:700; padding:6px 10px; letter-spacing:.3px; text-transform:uppercase; }
.head-actions button { background: transparent; border:none; color:#4b5563; }
.avatar { width:84px; height:84px; border-radius:50%; border:4px solid #fff; box-shadow: 0 10px 24px rgba(0,0,0,.08); object-fit:cover; }
.card-body { padding: 18px; display:flex; flex-direction:column; gap:14px; }
.name { font-size:18px; font-weight:800; color:#111827; margin: 8px 0 2px; }
.email { font-size:13px; color:#6b7280; }
.stats { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.stat { background:#f8fafc; border:1px solid #e5e7eb; border-radius:12px; padding:12px; text-align:center; }
.stat .num { font-size:18px; font-weight:800; color:#1f2937; }
.stat .lbl { font-size:10px; text-transform:uppercase; letter-spacing:.4px; color:#6b7280; }
.actions { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.btn-pill { display:flex; justify-content:center; align-items:center; gap:8px; padding:10px 12px; border-radius:12px; border:2px solid var(--primary); color: var(--primary); font-weight:700; font-size:13px; text-decoration:none; transition:.2s; }
.btn-pill:hover { background: var(--primary); color:#fff; }
.btn-primary-solid { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%); border:0; color:#fff; }
.btn-wallet { border-color:#10b981; color:#10b981; }
.btn-wallet:hover { background:#10b981; color:#fff; }
.status { background: #fff7ed; border:1px solid #fdba74; border-radius:12px; padding:10px; text-align:center; font-size:12px; color:#92400e; }
.status.expired { background:#fef2f2; border-color:#fecaca; color:#b91c1c; }

@media (max-width: 992px) { .actions { grid-template-columns:1fr; } }
</style>

<div class="clients-grid" id="clientsContainer">
    @foreach ($users as $user)
        @php
            $isExpired = !empty($user->plan_expire_date) && $user->plan_expire_date !== null && $user->plan_expire_date < now();
            $isLifetime = empty($user->plan_expire_date) || $user->plan_expire_date === null;
        @endphp
        <div class="client-card" data-user-name="{{ strtolower($user->name) }}" data-user-email="{{ strtolower($user->email) }}" data-user-type="{{ strtolower($user->type) }}" data-plan-status="{{ $isExpired ? 'expired' : ($isLifetime ? 'lifetime' : 'active') }}">
            <div class="card-head">
                <span class="chip">{{ strtoupper($user->type) }}</span>
                <div class="dropdown head-actions">
                    <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @can('edit user')
                        <li><a class="dropdown-item" href="#" data-url="{{ route('users.edit', $user->id) }}" data-ajax-popup="true" data-title="{{ __('Update Client') }}"><i class="ti ti-edit me-2"></i>{{ __('Edit') }}</a></li>
                        @endcan
                        @can('change password account')
                        <li><a class="dropdown-item" href="#" data-ajax-popup="true" data-title="{{ __('Reset Password') }}" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"><i class="ti ti-key me-2"></i>{{ __('Reset Password') }}</a></li>
                        @endcan
                        @if (Auth::user()->type == 'super admin')
                        <li><a class="dropdown-item" href="{{ route('login.with.company', $user->id) }}"><i class="ti ti-replace me-2"></i>{{ __('Login As Company') }}</a></li>
                        @endif
                        @if(Auth::user()->type == 'company')
                        <li><a class="dropdown-item" href="{{ route('userlogs.index', ['month'=>'','user'=>$user->id]) }}"><i class="ti ti-history me-2"></i>{{ __('Logged Details') }}</a></li>
                        @endif
                        @can('delete user')
                        <li>
                            <a href="#" class="dropdown-item text-danger bs-pass-para" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="delete-form-{{ $user->id }}">
                                <i class="ti ti-trash me-2"></i>{{ __('Delete') }}
                            </a>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}{!! Form::close() !!}
                        </li>
                        @endcan
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img class="avatar" src="{{ !empty($user->avatar) ? $profile . $user->avatar : $profile . 'avatar.png' }}" onerror="this.src='{{ asset('assets/images/user/avatar-1.jpg') }}'" alt="{{ $user->name }}">
                    <div class="name">{{ $user->name }}</div>
                    <div class="email">{{ $user->email }}</div>
                </div>
                @if (Auth::user()->type == 'super admin')
                <div class="stats">
                    <div class="stat"><div class="num">{{ $user->getTotalAppoinments() }}</div><div class="lbl">{{ __('Appointments') }}</div></div>
                    <div class="stat"><div class="num">{{ !empty($user->currentPlan) ? $user->currentPlan->name : 'N/A' }}</div><div class="lbl">{{ __('Current Plan') }}</div></div>
                </div>
                <div class="actions">
                    @if ($isExpired)
                        <a href="#" data-url="{{ route('subscription.extension', $user->id) }}" class="btn-pill btn-primary-solid" data-ajax-popup="true" data-title="{{ __('Extend Plan') }} - {{ $user->name }}"><i class="ti ti-calendar-plus"></i>{{ __('Extend Plan') }}</a>
                    @else
                        <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}" class="btn-pill btn-primary-solid" data-ajax-popup="true" data-title="{{ __('Upgrade Plan') }}"><i class="ti ti-arrow-up"></i>{{ __('Upgrade Plan') }}</a>
                    @endif
                    <a href="#" data-url="{{ route('business.upgrade', $user->id) }}" class="btn-pill" data-ajax-popup="true" data-title="{{ __('Business Info') }}"><i class="ti ti-building"></i>{{ __('Businesses') }}</a>
                </div>
                @php
                    $plan = \App\Models\Plan::find($user->plan);
                    $walletEnabled = $plan && $plan->enable_wallet === 'on';
                @endphp
                @if($walletEnabled)
                <div class="actions" style="margin-top:8px; grid-template-columns:1fr;">
                    <a href="{{ route('wallet.index') }}" class="btn-pill btn-wallet"><i class="ti ti-wallet"></i>{{ __('Wallet') }}</a>
                </div>
                @endif
                <div class="status {{ $isExpired ? 'expired' : '' }}">
                    @if ($isExpired)
                        <strong>{{ __('Plan Expired:') }}</strong> {{ \Auth::user()->dateFormat($user->plan_expire_date) }}
                    @else
                        <strong>{{ __('Plan Expires:') }}</strong> {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : __('Lifetime') }}
                    @endif
                </div>
                @endif
            </div>
        </div>
    @endforeach
    @can('create user')
        <a href="#" class="client-card d-flex align-items-center justify-content-center text-decoration-none" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Client') }}" data-url="{{ route('users.create') }}">
            <div class="text-center p-4">
                <div class="mb-3" style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-2));display:inline-flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 10px 24px rgba(102,126,234,.35)"><i class="ti ti-plus"></i></div>
                <div class="fw-bold" style="color:#111827">{{ __('Add New Client') }}</div>
                <div class="small text-muted">{{ __('Click to create a new client account') }}</div>
            </div>
        </a>
    @endcan
</div>

<div id="emptyState" class="text-center py-5" style="display:none;color:#6b7280;">
    <i class="ti ti-users" style="font-size:56px;color:#cbd5e1"></i>
    <h5 class="mt-3 mb-1" style="color:#374151">{{ __('No clients found') }}</h5>
    <div>{{ __('Try adjusting your search or filter criteria') }}</div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('clientSearch');
    const clearBtn = document.getElementById('clearSearch');
    const container = document.getElementById('clientsContainer');
    const cards = Array.from(container.querySelectorAll('.client-card'));
    const emptyState = document.getElementById('emptyState');
    const filters = document.querySelectorAll('.filter-option');

    let currentFilter = 'all';
    let searchTerm = '';

    function filterCards() {
        let visible = 0;
        cards.forEach(card => {
            const name = card.getAttribute('data-user-name') || '';
            const email = card.getAttribute('data-user-email') || '';
            const type = card.getAttribute('data-user-type') || '';
            const status = card.getAttribute('data-plan-status') || '';
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesFilter = currentFilter === 'all' ||
                (currentFilter === 'company' && type === 'company') ||
                (currentFilter === 'active' && status === 'active') ||
                (currentFilter === 'expired' && status === 'expired') ||
                (currentFilter === 'lifetime' && status === 'lifetime');
            const show = matchesSearch && matchesFilter;
            card.style.display = show ? 'block' : 'none';
            if (show) visible++;
        });
        emptyState.style.display = visible === 0 ? 'block' : 'none';
        container.style.display = visible === 0 ? 'none' : 'grid';
    }

    searchInput?.addEventListener('input', e => {
        searchTerm = (e.target.value || '').toLowerCase().trim();
        clearBtn.style.display = searchTerm ? 'block' : 'none';
        filterCards();
    });
    clearBtn?.addEventListener('click', () => { searchInput.value = ''; searchTerm=''; clearBtn.style.display='none'; filterCards(); });
    filters.forEach(f => f.addEventListener('click', e => { e.preventDefault(); currentFilter = f.getAttribute('data-filter'); filters.forEach(x=>x.classList.remove('active')); f.classList.add('active'); filterCards(); }));
});
</script>
@endsection

@extends('layouts.admin')
@php
    // $profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
   {{__('Manage Users')}}
@endsection
@section('title')
   {{__('Manage Users')}}
@endsection
@section('action-btn')
@can('create user')
<div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end" data-bs-placement="top" >  
    <a href="#" data-size="md" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New User')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
    @if(Auth::user()->type == 'company')
    <a href="{{ route('userlogs.index') }}" class="btn btn-sm btn-primary btn-icon m-1"
        data-size="lg" data-bs-whatever="{{ __('UserlogDetail') }}"> <span
            class="text-white">
            <i class="ti ti-user" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Userlog Detail') }}"></i></span>
    </a>
@endif
</div>
@endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
@endsection
@section('content')

<div class="row">
    @foreach ($users as $user)
        <div class="col-xl-3">
            <div class="card text-center">
               <div class="d-flex justify-content-between align-items-center px-3 pt-3">
                    <div class="border-0 pb-0 ">
                        <h6 class="mb-0">
                            <div class="badge p-2 px-3 rounded bg-primary">{{ ucfirst($user->type) }}</div>
                        </h6>
                    </div>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <button type="button" class="btn"
                                data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="feather icon-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @can('edit user')
                                    <a href="#" class="dropdown-item user-drop" data-url="{{ route('users.edit',$user->id) }}" data-ajax-popup="true" data-title="{{__('Update User')}}"><i class="ti ti-edit"></i><span class="ml-2">{{__('Edit')}}</span></a>
                                @endcan
                                @can('change password account')
                                   <a href="#" class="dropdown-item user-drop" data-ajax-popup="true" data-title="{{__('Reset Password')}}" data-url="{{route('user.reset',\Crypt::encrypt($user->id))}}"><i class="ti ti-key"></i>
                                     <span class="ml-2">{{__('Reset Password')}}</span></a>  
                                @endcan
                                @can('delete user')
                                     <a href="#" class="bs-pass-para dropdown-item user-drop"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$user->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash"></i><span class="ml-2">{{__('Delete')}}</span></a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id],'id'=>'delete-form-'.$user->id]) !!}
                                        {!! Form::close() !!} 
                                @endcan
                                {{-- @can('manage contact') --}}
                                @if(\Auth::user()->type == 'company')
                                    <a href="{{ route('userlogs.index', ['month'=>'','user'=>$user->id]) }}"
                                        class="dropdown-item user-drop"
                                        data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('User Log') }}"> 
                                        <i class="ti ti-history"></i>
                                        <span class="ml-2">{{__('Logged Details')}}</span></a>
                                @endif
                            </div>
                        </div>
                    </div>
               </div>
                <div class="card-body">
                    <div class="avatar">
                        <a href="{{(!empty($user->avatar))? asset(Storage::url('uploads/avatar/'.$user->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))}}" target="_blank">
                             <img src="{{(!empty($user->avatar))? asset(Storage::url('uploads/avatar/'.$user->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))}}" class="rounded-circle img_users_fix_size">
                        </a>
                    </div>
                    <h4 class="mt-2">{{ $user->name }}</h4>
                    <small>{{ $user->email }}</small>
                    @if(\Auth::user()->type == 'super admin')
                    <div class=" mb-0 mt-3">
                        <div class=" p-3">
                            <div class="row">
                                   <div class="col-5 text-start">
                                        <h6 class="mb-0  mt-1">{{!empty($user->currentPlan)?$user->currentPlan->name:''}}</h6>
                                    </div>
                                     <div class="col-7 text-end">
                                        <a href="#" data-url="{{ route('plan.upgrade',$user->id) }}" class="btn btn-sm btn-primary btn-icon" data-size="lg" data-ajax-popup="true" data-title="{{__('Upgrade Plan')}}">{{__('Upgrade Plan')}}</a>  
                                    </div>
                                
                                    <div class="col-6 text-start mt-4">
                                        <h6 class="mb-0 px-3">{{$user->totalBusiness($user->id)}}</h6>
                                        <p class="text-muted text-sm mb-0">{{__('Business')}}</p>
                                    </div>

                                <div class="col-6 text-end mt-4">
                                    <h6 class="mb-0 px-3">{{$user->getTotalAppoinments()}}</h6>
                                    <p class="text-muted text-sm mb-0">{{__('Appointments')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 mb-0">
                    
                        <button class="btn btn-sm btn-neutral mt-3 font-weight-500">
                            <a>{{__('Plan Expired : ') }} {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date): __('Lifetime')}}</a>
                        </button>
                    
                    </p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    @can('create user')
    <div class="col-md-3">
        <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="md" data-title="{{ __('Create New User') }}" data-url="{{ route('users.create') }}">
            <div class="badge bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add New User') }}</p>
        </a>
    </div>
    @endcan
</div>
@endsection

@extends('layouts.admin')
@php
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
    {{ __('Client Management') }}
@endsection
@section('title')
    {{ __('Client Management') }}
@endsection
@section('action-btn')
    @can('create user')
        <div class="d-flex align-items-center justify-content-end gap-3">
            <!-- Search Bar -->
            <div class="search-wrapper">
                <div class="search-container">
                    <i class="ti ti-search search-icon"></i>
                    <input type="text" class="search-input" id="clientSearch" placeholder="{{ __('Search clients...') }}">
                    <button class="clear-search" id="clearSearch" style="display: none;">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            <!-- Filter Dropdown -->
            <div class="filter-wrapper">
                <div class="dropdown">
                    <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-filter"></i>
                        <span>{{ __('Filter') }}</span>
                    </button>
                    <ul class="dropdown-menu filter-menu">
                        <li><a class="dropdown-item filter-option active" href="#" data-filter="all">{{ __('All Clients') }}</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="company">{{ __('Companies') }}</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="active">{{ __('Active Plans') }}</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="expired">{{ __('Expired Plans') }}</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="lifetime">{{ __('Lifetime Plans') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Add Client Button -->
            <a href="#" data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true" 
               data-bs-toggle="tooltip" title="{{ __('Create New Client') }}" 
               data-title="{{ __('Create New Client') }}" 
               class="add-client-btn">
                <i class="ti ti-plus"></i>
                <span>{{ __('Add Client') }}</span>
            </a>
        </div>
    @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Clients') }}</li>
@endsection

@section('content')
<style>
/* Modern SaaS Dashboard Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
    --border-radius: 16px;
    --border-radius-sm: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Search and Filter Components */
.search-wrapper {
    position: relative;
}

.search-container {
    position: relative;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    transition: var(--transition);
    overflow: hidden;
}

.search-container:focus-within {
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
    border-color: #667eea;
    transform: translateY(-2px);
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 18px;
    z-index: 2;
}

.search-input {
    width: 280px;
    padding: 12px 16px 12px 48px;
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
    color: #2d3748;
}

.search-input::placeholder {
    color: #a0aec0;
    font-weight: 400;
}

.clear-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    transition: var(--transition);
}

.clear-search:hover {
    background: #f7fafc;
    color: #4a5568;
}

/* Filter Components */
.filter-wrapper {
    position: relative;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: var(--border-radius);
    color: #4a5568;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
}

.filter-menu {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 8px 0;
    min-width: 180px;
}

.filter-option {
    padding: 10px 20px;
    font-size: 14px;
    color: #4a5568;
    transition: var(--transition);
}

.filter-option:hover {
    background: #f7fafc;
    color: #667eea;
}

.filter-option.active {
    background: #667eea;
    color: white;
}

/* Add Client Button */
.add-client-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.add-client-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Client Cards Grid */
.clients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    padding: 24px 0;
}

/* Modern Client Card */
.client-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    overflow: hidden;
    position: relative;
    border: 1px solid #f1f5f9;
}

.client-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--card-shadow-hover);
}

/* Card Header */
.card-header {
    background: var(--primary-gradient);
    padding: 20px;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    opacity: 0;
    transition: var(--transition);
}

.client-card:hover .card-header::before {
    opacity: 1;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: relative;
    z-index: 2;
}

.client-type {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
}

.card-actions {
    position: relative;
}

.action-menu-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.action-menu-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

/* Status Indicator */
.status-indicator {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.status-active { background: #10b981; }
.status-expired { background: #ef4444; }
.status-lifetime { background: #3b82f6; }

/* Card Body */
.card-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Profile Section */
.profile-section {
    text-align: center;
    margin-bottom: 24px;
    flex-shrink: 0;
}

.client-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    object-fit: cover;
    margin: 0 auto 16px;
    transition: var(--transition);
}

.client-card:hover .client-avatar {
    transform: scale(1.05);
}

.client-name {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
    line-height: 1.3;
}

.client-email {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 0;
    word-break: break-word;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
    width: 100%;
    flex-shrink: 0;
}

.stat-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: var(--border-radius-sm);
    padding: 16px 12px;
    text-align: center;
    transition: var(--transition);
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e0;
}

.stat-number {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 6px;
    line-height: 1.2;
}

.stat-label {
    font-size: 10px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    line-height: 1.2;
}

/* Action Buttons */
.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
    width: 100%;
    flex-shrink: 0;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    border: none;
    border-radius: var(--border-radius-sm);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    min-height: 44px;
    width: 100%;
    box-sizing: border-box;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.action-btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-secondary {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
}

.btn-secondary:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-warning {
    background: var(--warning-gradient);
    color: white;
    box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
    color: white;
}

/* Extend Subscription Button */
.extend-subscription-wrapper {
    margin-top: 16px;
}

.extend-btn {
    width: 100%;
    grid-column: 1 / -1;
    background: var(--warning-gradient);
    color: white;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);
}

.extend-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
    color: white;
}

/* Subscription Status */
.subscription-status {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
    border-radius: var(--border-radius-sm);
    padding: 10px 12px;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 0;
    margin-top: auto;
}

.subscription-status.expired {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border: 1px solid #ef4444;
}

.subscription-status.expired .status-text {
    color: #dc2626;
}

.subscription-status::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--warning-gradient);
}

.status-text {
    color: #92400e;
    font-size: 12px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    line-height: 1.3;
}

/* Add Client Card */
.add-client-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 2px dashed #cbd5e0;
    border-radius: var(--border-radius);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    text-align: center;
    transition: var(--transition);
    cursor: pointer;
    min-height: 400px;
    position: relative;
    overflow: hidden;
}

.add-client-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    opacity: 0;
    transition: var(--transition);
}

.add-client-card:hover::before {
    opacity: 1;
}

.add-client-card:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, #f0f4ff 0%, #e6f3ff 100%);
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
}

.add-icon {
    width: 64px;
    height: 64px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    transition: var(--transition);
}

.add-client-card:hover .add-icon {
    transform: scale(1.1);
}

.add-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.add-subtitle {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.5;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 24px;
    color: #6b7280;
}

.empty-icon {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 24px;
}

.empty-title {
    font-size: 24px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
}

.empty-subtitle {
    color: #6b7280;
    font-size: 16px;
    margin-bottom: 32px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .clients-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .search-input {
        width: 200px;
    }
    
    .clients-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
        gap: 10px;
        width: 100%;
    }
    
    .action-btn {
        padding: 14px 16px;
        font-size: 14px;
        min-height: 48px;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .search-input {
        width: 160px;
    }
    
    .add-client-btn span {
        display: none;
    }
    
    .filter-btn span {
        display: none;
    }
}

/* Loading Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-animate {
    animation: fadeInUp 0.6s ease forwards;
}

/* Loading Skeleton */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<div class="clients-grid" id="clientsContainer">
    @foreach ($users as $user)
        @php
            $isExpired = !empty($user->plan_expire_date) && $user->plan_expire_date !== null && $user->plan_expire_date < now();
            $isLifetime = empty($user->plan_expire_date) || $user->plan_expire_date === null;
        @endphp
        <div class="client-card card-animate" 
             data-user-id="{{ $user->id }}" 
             data-user-name="{{ strtolower($user->name) }}" 
             data-user-email="{{ strtolower($user->email) }}"
             data-user-type="{{ strtolower($user->type) }}"
             data-plan-status="{{ $isExpired ? 'expired' : ($isLifetime ? 'lifetime' : 'active') }}">
            
            <!-- Status Indicator -->
            <div class="status-indicator status-{{ $isExpired ? 'expired' : ($isLifetime ? 'lifetime' : 'active') }}"></div>
            
            <!-- Card Header -->
            <div class="card-header">
                <div class="header-content">
                    <div class="client-type">
                        {{ ucfirst($user->type) }}
                    </div>
                    <div class="card-actions">
                        <button class="action-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @can('edit user')
                                <li>
                                    <a class="dropdown-item" href="#" data-url="{{ route('users.edit', $user->id) }}" 
                                       data-ajax-popup="true" data-title="{{ __('Update Client') }}">
                                        <i class="ti ti-edit me-2"></i>{{ __('Edit') }}
                                    </a>
                                </li>
                            @endcan
                            @can('change password account')
                                <li>
                                    <a class="dropdown-item" href="#" data-ajax-popup="true" 
                                       data-title="{{ __('Reset Password') }}" 
                                       data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}">
                                        <i class="ti ti-key me-2"></i>{{ __('Reset Password') }}
                                    </a>
                                </li>
                            @endcan
                            @can('delete user')
                                <li>
                                    <a class="dropdown-item text-danger" href="#" 
                                       data-confirm="{{ __('Are You Sure?') }}"
                                       data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                       data-confirm-yes="delete-form-{{ $user->id }}">
                                        <i class="ti ti-trash me-2"></i>{{ __('Delete') }}
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                    {!! Form::close() !!}
                                </li>
                            @endcan
                            @if (\Auth::user()->type == 'company')
                                <li>
                                    <a class="dropdown-item" href="{{ route('userlogs.index', ['month' => '', 'user' => $user->id]) }}">
                                        <i class="ti ti-history me-2"></i>{{ __('Logged Details') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->type == 'super admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('login.with.company', $user->id) }}">
                                        <i class="ti ti-replace me-2"></i>{{ __('Login As Company') }}
                                    </a>
                                </li>
                            @endif
                            @if ($user->is_enable_login == 1)
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.login', \Crypt::encrypt($user->id)) }}">
                                        <i class="ti ti-road-sign me-2"></i>
                                        <span class="text-danger">{{ __('Login Disable') }}</span>
                                    </a>
                                </li>
                            @elseif ($user->is_enable_login == 0 && $user->password == null)
                                <li>
                                    <a class="dropdown-item" href="#" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                       data-ajax-popup="true" data-title="{{ __('New Password') }}">
                                        <i class="ti ti-road-sign me-2"></i>
                                        <span class="text-success">{{ __('Login Enable') }}</span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.login', \Crypt::encrypt($user->id)) }}">
                                        <i class="ti ti-road-sign me-2"></i>
                                        <span class="text-success">{{ __('Login Enable') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="card-body">
                <!-- Profile Section -->
                <div class="profile-section">
                    <img src="{{ !empty($user->avatar) ? $profile . $user->avatar : $profile . 'avatar.png' }}"
                         class="client-avatar" alt="{{ $user->name }}" 
                         onerror="this.src='{{ asset('assets/images/user/avatar-1.jpg') }}'">
                    <h3 class="client-name">{{ $user->name }}</h3>
                    <p class="client-email">{{ $user->email }}</p>
                </div>

                @if (\Auth::user()->type == 'super admin')
                    <!-- Stats Grid -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">{{ $user->getTotalAppoinments() }}</div>
                            <div class="stat-label">{{ __('Appointments') }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ !empty($user->currentPlan) ? $user->currentPlan->name : 'N/A' }}</div>
                            <div class="stat-label">{{ __('Current Plan') }}</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @if ($isExpired)
                            <a href="#" data-url="{{ route('subscription.extension', $user->id) }}" 
                               class="action-btn btn-warning" data-ajax-popup="true" 
                               data-title="{{ __('Extend Plan') }} - {{ $user->name }}">
                                <i class="ti ti-calendar-plus"></i>
                                {{ __('Extend Plan') }}
                            </a>
                        @else
                            <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}" 
                               class="action-btn btn-primary" data-ajax-popup="true" 
                               data-title="{{ __('Upgrade Plan') }}">
                                <i class="ti ti-arrow-up"></i>
                                {{ __('Upgrade Plan') }}
                            </a>
                        @endif
                        
                        <a href="#" data-url="{{ route('business.upgrade', $user->id) }}" 
                           class="action-btn btn-secondary" data-ajax-popup="true" 
                           data-title="{{ __('Business Info') }}">
                            <i class="ti ti-building"></i>
                            {{ __('Businesses') }}
                        </a>
                    </div>

                    <!-- Subscription Status -->
                    <div class="subscription-status {{ $isExpired ? 'expired' : '' }}">
                        <p class="status-text">
                            <i class="ti ti-calendar"></i>
                            @if ($isExpired)
                                {{ __('Plan Expired: ') }}
                                {{ \Auth::user()->dateFormat($user->plan_expire_date) }}
                            @else
                                {{ __('Plan Expires: ') }}
                                {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : __('Lifetime') }}
                            @endif
                        </p>
                    </div>
                    

                @endif
            </div>
        </div>
    @endforeach
    
    @can('create user')
        <div class="add-client-card card-animate" data-ajax-popup="true" data-size="lg" 
             data-title="{{ __('Create New Client') }}" data-url="{{ route('users.create') }}">
            <div class="add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h4 class="add-title">{{ __('Add New Client') }}</h4>
            <p class="add-subtitle">{{ __('Click here to create a new client account') }}</p>
        </div>
    @endcan
</div>

<!-- Empty State -->
<div id="emptyState" class="empty-state" style="display: none;">
    <div class="empty-icon">
        <i class="ti ti-users"></i>
    </div>
    <h3 class="empty-title">{{ __('No clients found') }}</h3>
    <p class="empty-subtitle">{{ __('Try adjusting your search or filter criteria') }}</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('clientSearch');
    const clearSearchBtn = document.getElementById('clearSearch');
    const clientsContainer = document.getElementById('clientsContainer');
    const clientCards = clientsContainer.querySelectorAll('.client-card');
    const emptyState = document.getElementById('emptyState');
    const filterOptions = document.querySelectorAll('.filter-option');
    
    let currentFilter = 'all';
    let searchTerm = '';

    // Enhanced search and filter functionality
    function performSearch() {
        let visibleCount = 0;
        
        clientCards.forEach(card => {
            const userName = card.getAttribute('data-user-name');
            const userEmail = card.getAttribute('data-user-email');
            const userType = card.getAttribute('data-user-type');
            const planStatus = card.getAttribute('data-plan-status');
            
            const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
            const matchesFilter = currentFilter === 'all' || 
                                (currentFilter === 'company' && userType === 'company') ||
                                (currentFilter === 'active' && planStatus === 'active') ||
                                (currentFilter === 'expired' && planStatus === 'expired') ||
                                (currentFilter === 'lifetime' && planStatus === 'lifetime');
            
            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
                visibleCount++;
                
                // Staggered animation
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, visibleCount * 100);
            } else {
                card.style.display = 'none';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
            clientsContainer.style.display = 'none';
        } else {
            emptyState.style.display = 'none';
            clientsContainer.style.display = 'grid';
        }
    }

    // Search input handler
    searchInput.addEventListener('input', function() {
        searchTerm = this.value.toLowerCase();
        clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
        performSearch();
    });

    // Clear search
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchTerm = '';
        this.style.display = 'none';
        performSearch();
    });

    // Filter functionality
    filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            currentFilter = this.getAttribute('data-filter');
            
            // Update active filter
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            performSearch();
        });
    });

    // Enhanced animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('card-animate');
            }
        });
    }, observerOptions);

    clientCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Loading states for actions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.action-btn')) {
            const btn = e.target.closest('.action-btn');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>Loading...';
            btn.style.pointerEvents = 'none';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.pointerEvents = 'auto';
            }, 3000);
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'k':
                    e.preventDefault();
                    searchInput.focus();
                    break;
                case 'n':
                    e.preventDefault();
                    document.querySelector('.add-client-card').click();
                    break;
            }
        }
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize dropdowns
    const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
@endsection
