@extends('layouts.app')

@section('content')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pet Food</title>

    <!-- Fonts -->

    <!-- Scripts -->
</head>
<body>
    <h1>My Profile</h1>
    <div class="user-profile">
    <form action="">
        @csrf
        <div class="profile-image">
            <div class="user-img">
                <span class="label hide">OLD IMAGE</span>
                <img id="oldImg" class="profile-img" src="{{ Auth::user()->filename ? asset('assets/profile_pictures/' . Auth::user()->filename) : asset('assets/icons/default_userpng.png') }}" alt="Profile Image" width="100" height="100">
                <span class="text-box-small">{{ Auth::user()->filename ?: 'placeholder' }}</span>
                <input accept="image/*" type="file" id="imgInp" required>
                <label class="for-input" for="imgInp"><img src="{{ asset('assets/icons/photo-svgrepo-com.png') }}" alt="*" height="25"> Choose Image</label>
            </div>
            <div class="user-img hide">
                <span class="label">NEW IMAGE</span>
                <img id="previewImage" class="profile-img" src="" alt="New Image" width="100" height="100">
                <span id="filename" class="text-box-small red"></span>
                <button type="submit" class="for-input"><img src="{{ asset('assets/icons/cloud-upload.png') }}" alt="*" height="25"> Upload</button>            
            </div>
        </div>
    </form>
    <form action="">
        @csrf
        <div class="account-info">
            <div class="input-box">
            <div class="field-group-2">
                <input type="text" value="{{ Auth::user()->username }}" name="nickname" required>
                <span class="underline"></span>
                <label>Nickname</label>
              </div>
            </div>
            <div class="input-box">
                <button type="button" data-bs-toggle="modal" data-bs-target="#passwordChangeModal" class="for-input"><img src="{{ asset('assets/icons/padlock.png') }}" alt="*" height="25"> Change Password</button>    
            </div>
            <div class="input-box">
                <div class="field-group-2">
                    <input type="text" value="{{ Auth::user()->name }}" name="full_name" required>
                    <span class="underline"></span>
                    <label>Full Name</label>
                </div>
                <input type="checkbox" id="displayName" class="custom-checkbox" name="display_name"><label for="displayName">Set as display name</label>
                <span class="text-box-small">Not verified</span>
                <span class="text-box-small green">How/Why?</span>
            </div>
            <div class="input-box">
                <div class="field-group-2">
                    <input type="text" value="{{ Auth::user()->email }}" name="email" required>
                    <span class="underline"></span>
                    <label>E-mail</label>
                </div>
                <span class="text-box-small">Not verified</span>
                <span class="text-box-small green">How/Why?</span>
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
            <span class="not-connected">
                Not connected
            </span>
        </div>
        <div class="social-container">
            @svg('assets/icons/google-logo.svg', 'google-logo')
            <img class="checkmark-img" src="{{ asset('assets/icons/checkmark-green.png') }}" alt="Checkmark">
            <span class="name">Kārlis Braķis</span>
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
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="passwordChangeModalLabel">Change Password</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body d-flex flex-wrap">
                <div class="field-group-2 mb-2">
                    <input type="text" required>
                    <span class="underline"></span>
                    <label>Current Password</label>
                </div>
                <div class="field-group-2">
                    <input type="text" required>
                    <span class="underline"></span>
                    <label>New Password</label>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
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