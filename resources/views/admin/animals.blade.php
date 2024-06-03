@extends('admin.layouts.dashboard')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | Admin Dashboard</title>
</head>
<body>
<div id="admin-dashboard" class="d-flex">
    <form action="{{ route('admin.animal.create') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-wrap justify-content-center">
        <div class="white-box mr-2">
            @csrf
            <h1>ADD SPECIES</h1>
            <div class="form-group required">
                <label for="gbif_id" class="mb-1">GBIF ID</label>
                <span>from </span>
                <a href="https://api.gbif.org/v1/species/search?q=chinchilla">
                    @svg('assets/icons/gbif-logo.svg', 'gbif-logo')
                </a>
                <div class="input-group">
                    <input type="text" value="{{ old('gbif_id', isset($data['key']) ? $data['key'] : '') }}" class="form-control" id="gbif_id" name="gbif_id">
                    <button type="submit" name="autofill" class="btn btn-primary d-flex" id="autofillBtn">Autofill</button>
                </div>
                @error('gbif_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="parent_id" class="mb-1">Parent ID</label>
                <div class="input-group">
                    <input type="text" value="{{ old('parent_id', isset($data['parentKey']) ? $data['parentKey'] : '') }}" class="form-control" id="parent_id" name="parent_id">
                    <button type="submit" name="autofill" class="btn btn-primary d-flex" id="autofillBtn">Autofill</button>
                </div>
                @error('parent_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="name" class="mb-1">Name</label>
                <div class="input-group">
                    <input type="text" value="{{ old('single_name', isset($data['canonicalName']) ? $data['canonicalName'] : '') }}" class="form-control" id="single_name" name="single_name" placeholder="Single">
                    <input type="text" value="{{ old('plural_name') }}" class="form-control" id="plural_name" name="plural_name" placeholder="Plural">
                </div>
            </div>            
            <div class="form-group required">
                <label for="language" class="mb-1">Localization</label>
                <select class="form-control" id="language" name="language">
                    @foreach($supportedLanguages as $code => $language)
                        <option value="{{ $code }}" {{ old('language') == $code ? 'selected' : '' }}>{{ $language['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group required">
                <label for="category" class="mb-1">Category</label>
                <select class="form-control" id="category" name="category">
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group required">
                <label for="rank" class="mb-1">Rank</label>
                <select class="form-control" id="rank" name="rank">
                    @foreach($ranks as $rank)
                        <option value="{{ $rank }}" {{ old('rank', isset($data) && $data['rank'] === $rank ? 'selected' : '') }}>{{ $rank }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="appearance" class="mb-1">Appearance</label>
                <textarea class="form-control" id="appearance" name="appearance" rows="3">{{ old('appearance') }}</textarea>
            </div>
            <div class="form-group">
                <label for="food" class="mb-1">Food</label>
                <textarea class="form-control" id="food" name="food" rows="3">{{ old('food') }}</textarea>
            </div>
    
            <!-- Image upload -->
            <div class="form-group">
                <label for="images" class="mb-1">Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <!-- Image preview -->
        <div class="white-box" id="preview">
            <span class="mb-1">Preview</span>
            <div class="image-preview" class="d-flex flex-wrap"></div>
        </div>
    </form>        
</div>
<script>
// JavaScript logic for handling image preview and removal
const imgInp = document.getElementById('images');
const previewContainer = document.querySelector('.image-preview');
const lastInputGroup = document.querySelector('#preview');

imgInp.onchange = evt => {
    const files = imgInp.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (!file.type.startsWith('image/')) {
             // Alert when a non-image file is selected
            alert('Please select only image files.');
            return;
        }
        const reader = new FileReader();
        reader.onload = () => {
            // Create form-group container for each preview
            const formGroup = document.createElement('div');
            formGroup.classList.add('form-group');

            const img = document.createElement('img');
            img.src = reader.result;
            img.classList.add('img-thumbnail', 'mr-2', 'mb-2');

            // Create radio input
            const radioInput = document.createElement('input');
            radioInput.type = 'radio';
            radioInput.name = 'cover_image';
            radioInput.value = previewContainer.children.length; // Use the current count
            radioInput.id = 'image' + previewContainer.children.length;
            radioInput.classList.add('d-none');
            if (previewContainer.children.length === 0) {
                radioInput.checked = true; // Select first image as default cover image
                img.classList.add('selected');
            }

            // Create label for radio input
            const label = document.createElement('label');
            label.htmlFor = 'image' + previewContainer.children.length;
            label.appendChild(img);

            // Create filename input
            const filenameInput = document.createElement('input');
            filenameInput.type = 'text';
            filenameInput.name = 'filename[]';
            filenameInput.value = document.getElementById('single_name').value ? document.getElementById('single_name').value + (previewContainer.children.length + 1) : 'animal' + (previewContainer.children.length + 1);
            filenameInput.classList.add('form-control', 'mb-2');
            filenameInput.placeholder = 'Filename';

            // Create close button
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2');
            closeButton.textContent = 'Close';
            closeButton.addEventListener('click', () => {
                previewContainer.removeChild(formGroup);
                if (previewContainer.children.length === 0) {
                    lastInputGroup.style.display = 'none'; // Hide last input-group if no images are left
                }
            });

            // Append elements to form-group container
            formGroup.appendChild(radioInput);
            formGroup.appendChild(label);
            formGroup.appendChild(filenameInput);
            formGroup.appendChild(closeButton);

            // Append form-group container to preview container
            previewContainer.appendChild(formGroup);
        };
        reader.readAsDataURL(file);
    }
    if (files.length > 0) {
        lastInputGroup.style.display = 'block'; // Show last input-group when at least one image is added
    }
};

</script>
<style>
    .img-thumbnail {
        border: 2px solid transparent; /* Add border to all images */
        height: 250px;
        max-width: 400px;
    }

    input[type="radio"]:checked + label .img-thumbnail {
        border-color: blue; /* Style selected image with border color */
    }

    #preview {
        display: none;
    }
    .form-group.required label:after { 
        color: red;
        content: "*";
    }
</style>
    
</body>
@endsection
