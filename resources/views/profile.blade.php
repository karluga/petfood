@extends('layouts.app')

@section('content')
<head>
    <title>Pet Food | Profile</title>
</head>
<body>
    @if (session('success'))
    <div class="alert alert-success w-100">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger w-100">{{ session('error') }}</div>
    @endif
    <h1>My Profile</h1>
    <div class="user-profile">
        <form action="{{ route('profile.upload-image', ['locale' => request()->segment(1)]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="profile-image">
            <div class="user-img">
                <span class="label hide">OLD IMAGE</span>
                <img id="oldImg" class="profile-img object-fit-cover" src="{{ Auth::user()->filename ? asset('storage/profile_pictures/' . Auth::user()->filename) : asset('assets/icons/default_userpng.png') }}" alt="Profile Image" width="100" height="100">
                <span class="text-box-small">{{ Auth::user()->filename ?: 'placeholder' }}</span>
                <input accept="image/*" type="file" id="imgInp" name="new_image" required>
                <label class="for-input" for="imgInp"><img src="{{ asset('assets/icons/photo-svgrepo-com.png') }}" alt="*" height="25"> Choose Image</label>
            </div>
            <div class="user-img hide">
                <span class="label">NEW IMAGE</span>
                <img id="previewImage" class="profile-img object-fit-cover" src="" alt="New Image" width="100" height="100">
                <span id="filename" class="text-box-small red"></span>
                <button type="submit" class="for-input"><img src="{{ asset('assets/icons/cloud-upload.png') }}" alt="*" height="25"> Upload</button>            
            </div>
        </div>
    </form>
    <form action="{{ route('profile.update', ['locale' => request()->segment(1)]) }}" method="POST">
        @csrf
        <div class="account-info">
            <div class="input-box">
            <div class="field-group-2">
                <input type="text" value="{{ Auth::user()->username }}" name="username">
                <span class="underline"></span>
                <label>Username</label>
              </div>
            </div>
            <div class="input-box">
                <button type="button" data-bs-toggle="modal" data-bs-target="#passwordChangeModal" class="for-input"><img src="{{ asset('assets/icons/padlock.png') }}" alt="*" height="25"> Change Password</button>    
            </div>
            <div class="input-box">
                <div class="field-group-2">
                    <input type="text" value="{{ Auth::user()->name }}" name="full_name">
                    <span class="underline"></span>
                    <label>Full Name</label>
                </div>
                <input type="checkbox" id="displayName" class="custom-checkbox" name="display_name" {{ Auth::user()->display_name ? 'checked' : '' }}><label for="displayName">Set as display name</label>
                <span class="text-box-small">Not verified</span>
                <span class="text-box-small green">How/Why?</span>
            </div>
            <div class="input-box">
                <div class="field-group-2">
                    <input type="text" value="{{ Auth::user()->email }}" name="email">
                    <span class="underline"></span>
                    <label>E-mail</label>
                </div>
                
                @if(Auth::user()->email_verified_at)
                    <span class="text-box-small verified">Verified</span>
                @else
                    <span class="text-box-small">
                        <a href="{{ route('profile.verify-email', ['locale' => app()->getLocale(), 'id' => Auth::id(), 'hash' => sha1(Auth::user()->getEmailForVerification())]) }}">Not verified</a>
                    </span>
                    <span class="text-box-small green">How/Why?</span>
                @endif
            </div>
        </div>
        <button type="submit" class="for-input submit">
            @svg('assets/icons/floppy-disk.svg', 'floppy-disk')
            Save Changes
        </button>
    </form>
    </div>
    <h1 class="title">Connections</h1>
    <div class="connections section">
        <div class="social-container">
            @svg('assets/icons/facebook-logo.svg', 'facebook-logo')
            @if(Auth::user()->facebook_id)
                <img class="checkmark-img" src="{{ asset('assets/icons/checkmark-green.png') }}" alt="Checkmark">
                <span class="name">{{ Auth::user()->name }}</span>
            @else
                <span class="not-connected">Not connected</span>
            @endif
        </div>
        
        <div class="social-container">
            @svg('assets/icons/google-logo.svg', 'google-logo')
            @if(Auth::user()->google_id)
                <img class="checkmark-img" src="{{ asset('assets/icons/checkmark-green.png') }}" alt="Checkmark">
                <span class="name">{{ Auth::user()->name }}</span>
            @else
                <span class="not-connected">Not connected</span>
            @endif
        </div>
    </div>
    <h1 class="title">Roles</h1>
    <div class="role-container">
        <a href="#user" class="role active">User</a>
        <a href="#pet_owner" class="role">Pet Owner</a>
        <a href="#expert" class="role">Expert</a>
        <a href="#auditor" class="role">Auditor</a>
        <a href="#content_creator" class="role">Content Creator</a>
        <a href="#admin" class="role">Admin</a>
    </div>
    <h2 class="subheading">More Details</h2>
    <div class="full-width">
        <div class="role-description section" id="pet_owner">
            <span class="role">Pet Owner</span>
            <div class="req">Requirements</div>
            <ul>
                <li>One of your saved pets, along with an image associated to it.</li>
            </ul>
            <button class="green-btn">
                Apply all of my pets
            </button>
            <a href="">My Pets section</a>
        </div>
        <div class="role-description section" id="content_creator">
            <span class="role">Content Creator</span>
            <div class="req">Requirements</div>
            <ul>
                <li>Get verified by any admin or auditor</li>
                <li>Get the <a href="">verified</a> badge</li>
            </ul>
            <p>We will send you an email to walk you through the steps to being a content creator.</p>
            <button class="red-btn">
                You cannot apply to this role
            </button>
        </div>
        <div class="role-description section" id="auditor">
            <span class="role">Auditor</span>
            <div class="req">Requirements</div>
            <ul>
                <li>You need to already be a verified content creator.</li>
            </ul>
            <p>Being a content auditor is no joke, we have to be sure that our website's content isn't only accurate and up-to-date but also aligns with the brand's tone and messaging, meets SEO standards, and complies with any relevant legal and ethical guidelines.
                An auditors duties include approving the content creators created content and evaluating it before it is public.</p>
            <button class="green-btn">
                Apply
            </button>
        </div>
        <div class="role-description section" id="expert">
            <span class="role">Expert</span>
            <div class="req">Requirements</div>
            <ul>
                <li>Any valid sertificate of completion from a government entity.</li>
                <li>The sertificate has to be anything related to pets.</li>
            </ul>
            <p>Anyone who has had the experience in any of these fields can be a valuable asset to our community. 
                Here are some examples:</p>
            <ol>
                <li>Veterinary Medicine</li>
                <li>Pet Grooming</li>
                <li>Pet Training</li>
                <li>Pet Sitting and Dog Walking</li>
                <li>Pet Retail</li>
                <li>Pet Food Industry</li>
                <li>Animal Shelter and Rescue</li>
                <li>Pet Photography</li>
                <li>Animal Behaviorist</li>
                <li>Pet Insurance</li>
            </ol>
            <button class="green-btn">
                Apply
            </button>
        </div>
        <div class="role-description section" id="admin">
            <span class="role">Admin</span>
            <p>Being an admin is the highest responsibility of any community. The admins oversee all activity on the website.</p>
            <button class="red-btn">
                You cannot apply to this role
            </button>
        </div>
    </div>
    <h1 class="title verified">Verified Badge <img src="{{ asset('assets/icons/verified.png') }}" alt="Verified Icon" height="40"></h1>
    <div class="full-width section role-description">
        This badge is only necessary to verify that you are a real person, in case you want to apply to a specific role. The process takes a considerable amount of time.
        This is done using a third party service <a href="id.me">ID.me</a>.
        We will need you to:
        <ol>
            <li>Scan the front side of the document of your choice - ID card or passport.</li>
            <li>Then you will be asked to scan a picture of yourself to complete the verification process.</li>
        </ol>
        <button class="green-btn">
            Get Verified
        </button>
    </div>
    <!-- Modal Password change -->
    <div class="modal fade" id="passwordChangeModal" tabindex="-1" role="dialog" aria-labelledby="passwordChangeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{ route('profile.change-password', app()->getLocale()) }}">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="passwordChangeModalLabel">Change Password</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body d-flex flex-wrap">
                <div class="field-group-2">
                    <input type="{{  Auth::user()->google_id ? 'text' : 'password' }}" name="current_password" value="{{  Auth::user()->google_id ? Auth::user()->name . '@' . Auth::user()->google_id : '' }}" required>
                    <span class="underline"></span>
                    <label>Current Password</label>
                </div>
                <div class="field-group-2 mt-2">
                    <input type="password" name="new_password" required>
                    <span class="underline"></span>
                    <label>New Password</label>
                </div>
                <div class="field-group-2 mt-2">
                    <input type="password" name="confirm_password" required>
                    <span class="underline"></span>
                    <label>Confirm Password</label>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Change Password</button>
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
</body>
@endsection