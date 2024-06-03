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
                <div class="form-group">
                    <label for="gbif_id" class="mb-1">
                        <span>GBIF ID from </span>
                        <a href="https://api.gbif.org/v1/species/search?q=chinchilla">
                            @svg('assets/icons/gbif-logo.svg', 'gbif-logo')
                        </a>
                    </label>
                    <div class="input-group">
                        <input type="text" value="{{ isset($data['key']) ? $data['key'] : '' }}" class="form-control" id="gbif_id" name="gbif_id">
                        <button type="submit" name="autofill" class="btn btn-primary d-flex" id="autofillBtn">Autofill</button>
                    </div>
                    @error('gbif_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="form-group">
                    <label for="name" class="mb-1">Name</label>
                    <div class="input-group">
                        <input type="text" value="{{ isset($data['canonicalName']) ? $data['canonicalName'] : '' }}" class="form-control" id="single_name" name="single_name" placeholder="Single">
                        <input type="text" class="form-control" id="plural_name" name="plural_name" placeholder="Plural">
                    </div>
                </div>            
                <div class="form-group">
                    <label for="language" class="mb-1">Localization</label>
                    <select class="form-control" id="language" name="language">
                        @foreach($supportedLanguages as $code => $language)
                            <option value="{{ $code }}">{{ $language['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="category" class="mb-1">Category</label>
                    <select class="form-control" id="category" name="category">
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="rank" class="mb-1">Rank</label>
                    <select class="form-control" id="rank" name="rank">
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ isset($data) && $data['rank'] === $rank ? 'selected' : '' }}>{{ $rank }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="appearance" class="mb-1">Appearance</label>
                    <textarea class="form-control" id="appearance" name="appearance" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="food" class="mb-1">Food</label>
                    <textarea class="form-control" id="food" name="food" rows="3"></textarea>
                </div>

                <!-- Image upload -->
                <div class="form-group">
                    <label for="images" class="mb-1">Images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple>
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
<!-- Image preview script -->
<script>
    // JavaScript logic for handling image preview and removal
    const imgInp = document.getElementById('images');
    const previewContainer = document.querySelector('.image-preview');
    const lastInputGroup = document.querySelector('#preview');
    
    imgInp.onchange = evt => {
        const files = imgInp.files;
        let count = previewContainer.children.length; // Count of previews
    
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) {
                continue; // Skip non-image files
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
                radioInput.value = count;
                radioInput.id = 'image' + count;
                radioInput.classList.add('d-none');
                if (count === 0) {
                    radioInput.checked = true; // Select first image as default cover image
                    img.classList.add('selected');
                }
    
                // Create label for radio input
                const label = document.createElement('label');
                label.htmlFor = 'image' + count;
                label.appendChild(img);
    
                // Create filename input
                const filenameInput = document.createElement('input');
                filenameInput.type = 'text';
                filenameInput.name = 'filename[]';
                filenameInput.value = document.getElementById('single_name').value ? document.getElementById('single_name').value + (count + 1) : 'animal' + (count + 1);
                filenameInput.classList.add('form-control', 'mb-2');
                filenameInput.placeholder = 'Filename';
    
                // Create close button
                const closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2');
                closeButton.textContent = 'Close';
                closeButton.addEventListener('click', () => {
                    previewContainer.removeChild(formGroup);
                    count--; // Decrement count when an image is removed
                    if (document.querySelector('input[name="cover_image"]:checked') === null) {
                        document.querySelector('input[name="cover_image"]').checked = true; // Default to first image if checked image is removed
                        previewContainer.firstElementChild.querySelector('input[type="radio"]').checked = true;
                        previewContainer.firstElementChild.querySelector('.img-thumbnail').classList.add('selected');
                    }
                    if (count === 0) {
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
    
                count++; // Increment count for the next preview
            };
            reader.readAsDataURL(file);
        }
        if (files.length > 0) {
            lastInputGroup.style.display = 'block'; // Show last input-group when at least one image is added
        }
    };
    
    // Hide last input-group if no images are initially present
    if (document.querySelector('.image-preview').children.length === 0) {
        lastInputGroup.style.display = 'none';
    }
    
    </script>
    
    <!-- Style for selected image -->
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
    </style>
    
</body>
@endsection
