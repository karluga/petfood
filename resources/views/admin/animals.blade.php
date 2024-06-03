@extends('admin.layouts.dashboard')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | Admin Dashboard</title>
</head>
<body>
@if (session('success'))
<div class="alert alert-success mx-4">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger mx-4">{{ session('error') }}</div>
@endif
<div id="admin-dashboard" class="d-flex">
    <form action="{{ route('admin.animal.create') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-wrap justify-content-center">
        <div class="white-box mr-2">
            @csrf
            <h1>ADD SPECIES</h1>
            <div class="form-group required">
                <label for="gbif_id">GBIF ID</label>
                <span>from </span>
                <a href="https://api.gbif.org/v1/species/search?q=chinchilla" title="Example Link">
                    @svg('assets/icons/gbif-logo.svg', 'gbif-logo mb-1')
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
                @error('single_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                @error('plural_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="name" class="mb-1">Slug</label>
                <div class="input-group">
                    <input type="text" value="{{ old('slug', isset($data['canonicalName']) ? strtolower($data['canonicalName']) : '') }}" class="form-control" id="slug" name="slug" placeholder="Slug">
                </div>
                @error('slug')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>      
            <div class="form-group required">
                <label for="language" class="mb-1">Localization</label>
                <select class="form-control" id="language" name="language">
                    @foreach($supportedLanguages as $code => $language)
                        <option value="{{ $code }}" {{ old('language') == $code ? 'selected' : '' }}>{{ $language['name'] }}</option>
                    @endforeach
                </select>
                @error('language')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="category" class="mb-1">Category</label>
                <select class="form-control" id="category" name="category">
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')
                <span class="text-danger">{{ $message }}</span>
                @enderror
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
                @error('appearance')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="food" class="mb-1">Food</label>
                <textarea class="form-control" id="food" name="food" rows="3">{{ old('food') }}</textarea>
                @error('food')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- Image upload -->
            <div class="form-group">
                <label for="images" class="mb-1">Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                @error('images')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <!-- Image preview -->
        <div class="white-box" id="preview">
            <span class="mb-1">Preview</span>
            <p class="fs-5">
                <i class="fa-solid fa-circle-info"></i>
                Click on any image to set it as the cover image.
            </p>
            <div class="image-preview" class="d-flex flex-wrap"></div>
        </div>
    </form>        
</div>
<script>

// JavaScript logic for handling image preview and removal
const imgInp = document.getElementById('images');
const previewContainer = document.querySelector('#preview');
const lastInputGroup = document.querySelector('#preview');
let selectedFiles = []; // Array to store selected files

imgInp.onchange = evt => {
    const files = imgInp.files;

    // Append newly selected files to the existing selectedFiles array
    selectedFiles = selectedFiles.concat(Array.from(files));

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
                } else if (document.querySelector('input[name="cover_image"]:checked') === null) {
                    document.querySelector('input[name="cover_image"]').checked = true; // Default to first image if checked image is removed
                }
            });

            // Append elements to form-group container
            formGroup.appendChild(radioInput);
            formGroup.appendChild(label);
            formGroup.appendChild(filenameInput);
            formGroup.appendChild(closeButton);

            // Append form-group container to preview container
            previewContainer.appendChild(formGroup);

            // Log data from selectedFiles array and filename[] array
            console.log('selectedFiles array data:', selectedFiles);
            console.log('filename[] array data:', document.querySelectorAll('input[name="filename[]"]'));
        };
        reader.readAsDataURL(file);
    }
    if (selectedFiles.length > 0) {
        lastInputGroup.style.display = 'block';
    }

    // Update imgInp.files indirectly using FormData
    const formData = new FormData();
    for (let file of selectedFiles) {
        formData.append('images[]', file);
    }
    const newFiles = formData.getAll('images[]');

    // Reset file input field
    imgInp.value = '';

    // Programmatically set the new files to the file input field
    const newFileList = new ClipboardEvent('').clipboardData || new DataTransfer();
    for (const file of newFiles) {
        newFileList.items.add(file);
    }
    imgInp.files = newFileList.files;
};


</script>

<style>
    .img-thumbnail {
        border: 2px solid transparent;
        height: 250px;
        max-width: 100%;
    }

    input[type="radio"]:checked + label .img-thumbnail {
        border-color: blue;
    }

    #preview {
        display: none;
        max-width: 300px;
    }
    .form-group.required label:after { 
        color: red;
        content: "*";
    }
</style>
    
</body>
@endsection
