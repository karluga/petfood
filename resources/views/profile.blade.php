@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ __('app.section.profile.name') }}</title>
</head>
<body>
<div id="profile">
    @if (session('success'))
    <div class="alert alert-success mx-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger mx-4">{{ session('error') }}</div>
    @endif
    <h1 class="text-center">{{ __('app.section.profile.name') }}</h1>
    <div class="user-profile">
        <form action="{{ route('profile.upload-image', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="profile-image">
            <div class="user-img">
                <span class="label hide">{{ __('app.section.profile.old_image') }}</span>
                <img id="oldImg" class="profile-img object-fit-cover" src="{{ Auth::user()->filename ? asset('storage/profile_pictures/' . Auth::user()->filename) : asset('assets/icons/default_userpng.png') }}" alt="{{ __('app.section.profile.old_image') }}" width="100" height="100">
                <span class="text-box-small">{{ Auth::user()->filename ?: __('app.section.profile.choose_image') }}</span>
                <input accept="image/*" type="file" id="imgInp" name="new_image" required>
                <label class="for-input" for="imgInp"><img src="{{ asset('assets/icons/photo-svgrepo-com.png') }}" alt="*" height="25"> {{ __('app.section.profile.choose_image') }}</label>
            </div>
            <div class="user-img hide">
                <span class="label">{{ __('app.section.profile.new_image') }}</span>
                <img id="previewImage" class="profile-img object-fit-cover" src="" alt="{{ __('app.section.profile.new_image') }}" width="100" height="100">
                <span id="filename" class="text-box-small red"></span>
                <button type="submit" class="for-input"><img src="{{ asset('assets/icons/cloud-upload.png') }}" alt="*" height="25"> {{ __('app.section.profile.upload') }}</button>            
            </div>
        </div>
    </form>
    <form action="{{ route('profile.update', ['locale' => app()->getLocale()]) }}" method="POST">
        @csrf
        <div class="account-info">
            <div class="input-box">
            <div class="field-group-2">
                <input type="text" value="{{ Auth::user()->username }}" name="username">
                <span class="underline"></span>
                <label>{{ __('app.section.profile.username') }}</label>
              </div>
            </div>
            <div class="input-box">
                <button type="button" data-bs-toggle="modal" data-bs-target="#passwordChangeModal" class="for-input"><img src="{{ asset('assets/icons/padlock.png') }}" alt="*" height="25"> {{ __('app.section.profile.change_password') }}</button>    
            </div>
            <div class="input-box">
                <div class="field-group-2">
                    <input type="text" value="{{ Auth::user()->name }}" name="full_name">
                    <span class="underline"></span>
                    <label>{{ __('app.section.profile.full_name') }}</label>
                </div>
                <input type="checkbox" id="displayName" class="custom-checkbox" name="display_name" {{ Auth::user()->display_name ? 'checked' : '' }}><label for="displayName">{{ __('app.section.profile.set_display_name') }}</label>
                {{-- TODO make feature to verify real people --}}
                <span class="text-box-small">{{ __('app.section.profile.not_verified') }}</span>
                <span class="text-box-small green">{{ __('app.section.profile.how_why') }}</span>
            </div>
            <div class="input-box">
                <div class="field-group-2">
                    <input type="text" value="{{ Auth::user()->email }}" name="email">
                    <span class="underline"></span>
                    <label>{{ __('app.section.profile.email') }}</label>
                </div>
                
                @if(Auth::user()->email_verified_at)
                    <span class="text-box-small verified">{{ __('app.section.profile.verified') }}</span>
                @else
                    <span class="text-box-small">
                        <a href="{{ route('profile.verify-email', ['locale' => app()->getLocale(), 'id' => Auth::id(), 'hash' => sha1(Auth::user()->getEmailForVerification())]) }}">{{ __('app.section.profile.not_verified') }}</a>
                    </span>
                    <span class="text-box-small green">{{ __('app.section.profile.how_why') }}</span>
                @endif
            </div>
        </div>
        <button type="submit" class="for-input submit">
            @svg('assets/icons/floppy-disk.svg', 'floppy-disk')
            {{ __('app.navigation.save_changes') }}
        </button>
    </form>
    </div>
    <h1 class="title">{{ __('app.section.profile.connections') }}</h1>
    <div class="connections section">
        <div class="social-container">
            @svg('assets/icons/facebook-logo.svg', 'facebook-logo')
            @if(Auth::user()->facebook_id)
                <img class="checkmark-img" src="{{ asset('assets/icons/checkmark-green.png') }}" alt="Checkmark">
                <span class="name">{{ Auth::user()->name }}</span>
            @else
                <span class="not-connected">{{ __('app.section.profile.not_connected') }}</span>
            @endif
        </div>
        
        <div class="social-container">
            @svg('assets/icons/google-logo.svg', 'google-logo')
            @if(Auth::user()->google_id)
                <img class="checkmark-img" src="{{ asset('assets/icons/checkmark-green.png') }}" alt="Checkmark">
                <span class="name">{{ Auth::user()->name }}</span>
            @else
                <span class="not-connected">{{ __('app.section.profile.not_connected') }}</span>
            @endif
        </div>
    </div>
    <h1 class="title">{{ __('app.section.profile.roles') }}</h1>
    <div class="role-container">
        <a href="#user" class="role active">{{ __('app.section.profile.user') }}</a>
        <a href="#pet_owner" class="role">{{ __('app.section.profile.requirements.pet_owner.title') }}</a>
        <a href="#expert" class="role">{{ __('app.section.profile.requirements.expert.title') }}</a>
        <a href="#auditor" class="role">{{ __('app.section.profile.requirements.auditor.title') }}</a>
        <a href="#content_creator" class="role">{{ __('app.section.profile.requirements.content_creator.title') }}</a>
        <a href="#admin" class="role">{{ __('app.section.profile.requirements.admin.title') }}</a>
    </div>
    <h2 class="subheading">{{ __('app.section.profile.more_details') }}</h2>
    <div class="full-width">
        <div class="role-description section" id="pet_owner">
            <span class="role">{{ __('app.section.profile.pet_owner') }}</span>
            <div class="req">{{ __('app.section.profile.requirements_title') }}</div>
            <ul>
                @foreach(__('app.section.profile.requirements.pet_owner.requirement_list') as $requirement)
                    <li>{!! $requirement !!}</li>
                @endforeach
            </ul>
            <button class="green-btn">
                {{ __('app.section.profile.apply_button') }}
            </button>
            <a href="">{{ __('app.section.profile.requirements.pet_owner.link') }}</a>
        </div>
        <div class="role-description section" id="content_creator">
            <span class="role">{{ __('app.section.profile.requirements.content_creator.title') }}</span>
            <div class="req">{{ __('app.section.profile.requirements_title') }}</div>
            <ul>
                @foreach(__('app.section.profile.requirements.content_creator.requirement_list') as $requirement)
                    <li>{!! $requirement !!}</li>
                @endforeach
            </ul>
            <p>{{ __('app.section.profile.requirements.content_creator.description') }}</p>
            <button class="red-btn">
                {{ __('app.section.profile.cannot_apply_button') }}
            </button>
        </div>
        <div class="role-description section" id="auditor">
            <span class="role">{{ __('app.section.profile.requirements.auditor.title') }}</span>
            <div class="req">{{ __('app.section.profile.requirements_title') }}</div>
            <ul>
                @foreach(__('app.section.profile.requirements.auditor.requirement_list') as $requirement)
                    <li>{{ $requirement }}</li>
                @endforeach
            </ul>
            <p>{{ __('app.section.profile.requirements.auditor.description') }}</p>
            <button class="green-btn">
                {{ __('app.section.profile.apply_button') }}
            </button>
        </div>
        <div class="role-description section" id="expert">
            <span class="role">{{ __('app.section.profile.requirements.expert.title') }}</span>
            <div class="req">{{ __('app.section.profile.requirements_title') }}</div>
            <ul>
                @foreach(__('app.section.profile.requirements.expert.requirement_list') as $requirement)
                    <li>{{ $requirement }}</li>
                @endforeach
            </ul>
            <p>{{ __('app.section.profile.requirements.expert.description') }}</p>
            <button class="green-btn">
                {{ __('app.section.profile.apply_button') }}
            </button>
        </div>
        <div class="role-description section" id="admin">
            <span class="role">{{ __('app.section.profile.requirements.admin.title') }}</span>
            <p>{{ __('app.section.profile.requirements.admin.description') }}</p>
            <button class="red-btn">
                {{ __('app.section.profile.cannot_apply_button') }}
            </button>
        </div>
    </div>
    <h1 class="title verified">{{ __('app.section.profile.verified_badge') }} <img src="{{ asset('assets/icons/verified.png') }}" alt="{{ __('app.section.profile.verified_badge') }}" height="40"></h1>
    <div class="full-width section role-description">
        {{ __('app.section.profile.verified_badge_description') }}
        <ol>
            <li>{{ __('app.section.profile.verified_badge_step_1') }}</li>
            <li>{{ __('app.section.profile.verified_badge_step_2') }}</li>
        </ol>
        <button class="green-btn">
            {{ __('app.section.profile.get_verified') }}
        </button>
    </div>
    <!-- Modal Password change -->
    <div class="modal fade" id="passwordChangeModal" tabindex="-1" role="dialog" aria-labelledby="passwordChangeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{ route('profile.change-password', app()->getLocale()) }}">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="passwordChangeModalLabel">{{ __('app.section.profile.password_change') }}</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ __('app.navigation.close') }}">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body d-flex flex-wrap">
                <div class="field-group-2">
                    <input type="{{  Auth::user()->google_id ? 'text' : 'password' }}" name="current_password" value="{{  Auth::user()->google_id ? Auth::user()->name . '@' . Auth::user()->google_id : '' }}" required>
                    <span class="underline"></span>
                    <label>{{ __('app.section.profile.current_password') }}</label>
                </div>
                <div class="field-group-2 mt-2">
                    <input type="password" name="new_password" required>
                    <span class="underline"></span>
                    <label>{{ __('app.section.profile.new_password') }}</label>
                </div>
                <div class="field-group-2 mt-2">
                    <input type="password" name="confirm_password" required>
                    <span class="underline"></span>
                    <label>{{ __('app.section.profile.confirm_password') }}</label>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.navigation.close') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('app.section.profile.change_password') }}</button>
            </div>
        </form>
        </div>
    </div>
    <script>
    imgInp.onchange = evt => {
        const [file] = imgInp.files;
        if (file) {
            previewImage.src = URL.createObjectURL(file);
            const profileImageContainer = document.querySelector('.profile-image');
            const filenameElement = document.getElementById('filename');
            filenameElement.textContent = file.name;
            profileImageContainer.classList.add('visible');
        }
    };
    </script>
</div>
</body>
@endsection
