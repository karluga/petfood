@extends('admin.layouts.dashboard')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | Admin Dashboard</title>
</head>
<body>
{{-- @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
@endforeach --}}
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
                    <button type="submit" name="autofill" class="btn btn-primary d-flex z-0" id="autofillBtn">Autofill</button>
                </div>
                @error('gbif_id')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="parent_id" class="mb-1">Parent ID</label>
                <div class="input-group">
                    <input type="text" value="{{ old('parent_id', isset($data['parentKey']) ? $data['parentKey'] : '') }}" class="form-control" id="parent_id" name="parent_id">
                </div>
                @error('parent_id')
                    <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="name" class="mb-1">Name</label>
                <div class="input-group">
                    <input type="text" value="{{ old('single_name', isset($data['canonicalName']) ? $data['canonicalName'] : '') }}" class="form-control" id="single_name" name="single_name" placeholder="Single">
                    <input type="text" value="{{ old('plural_name') }}" class="form-control" id="plural_name" name="plural_name" placeholder="Plural">
                </div>
                @error('single_name')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
                @error('plural_name')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group required">
                <label for="name" class="mb-1">Slug</label>
                <div class="input-group">
                    <input type="text" value="{{ old('slug', isset($data['canonicalName']) ? strtolower($data['canonicalName']) : '') }}" class="form-control" id="slug" name="slug" placeholder="The name that shows up in the link">
                </div>
                @error('slug')
                <span class="text-danger fs-5">{{ $message }}</span>
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
                <span class="text-danger fs-5">{{ $message }}</span>
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
                <span class="text-danger fs-5">{{ $message }}</span>
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
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="food" class="mb-1">Food</label>
                <textarea class="form-control" id="food" name="food" rows="3">{{ old('food') }}</textarea>
                @error('food')
                <span class="text-danger fs-5">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="imgInp" class="mb-1">Images</label>
                <p class="fs-5 mb-2">
                    <i class="fa-solid fa-circle-info"></i>
                    Allowed: jpeg, png, jpg, and max 10 MB
                </p>
                <input type="file" class="form-control" id="imgInp" name="images[]" multiple accept="image/*">
                @if($errors->has('images.*'))
                <p class="text-danger fs-5">Please check images before submitting again!</p>
                @endif
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        <!-- Image preview -->
        {{-- @if($errors->has('images.*')) style="display: block;" @endif --}}
        <div class="white-box" id="preview">
            <span class="mb-1">Preview</span>
            <p class="fs-5">
                <i class="fa-solid fa-circle-info"></i>
                Click on any image to set it as the cover image.
            </p>
            <div class="image-preview">
                <!-- Generated -->
            </div>
        </div>
    </form> 
</div>
{{-- {{dd($errors->get('images.*'))}} --}}
<script>
    const previewContainer = document.querySelector('.image-preview');
    const lastInputGroup = document.querySelector('#preview');
    let selectedFiles = [];
    let db; // IndexedDB database instance

    // Function to open IndexedDB database
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('selectedImagesDB', 1);

            request.onerror = function(event) {
                console.error('IndexedDB error:', event.target.errorCode);
                reject(event.target.error);
            };

            request.onsuccess = function(event) {
                db = event.target.result;
                resolve();
            };

            request.onupgradeneeded = function(event) {
                db = event.target.result;
                const objectStore = db.createObjectStore('images', { keyPath: 'id', autoIncrement: true });
                objectStore.createIndex('filename', 'filename', { unique: false });
            };
        });
    }

    // Function to add an image to IndexedDB
    function addImageToDB(dataUrl, filename) {
        const transaction = db.transaction(['images'], 'readwrite');
        const objectStore = transaction.objectStore('images');
        const newItem = { dataUrl: dataUrl, filename: filename };

        objectStore.add(newItem);
    }

    // Function to load images from IndexedDB on page load
    function loadImagesFromDB() {
        const transaction = db.transaction(['images'], 'readonly');
        const objectStore = transaction.objectStore('images');
        const cursorRequest = objectStore.openCursor();

        let index = 0; // Initialize index counter

        cursorRequest.onsuccess = function(event) {
            const cursor = event.target.result;
            if (cursor) {
                renderImage(cursor.value.dataUrl, cursor.value.filename, cursor.key, index); // Pass key and index
                selectedFiles.push({ dataUrl: cursor.value.dataUrl, filename: cursor.value.filename });

                cursor.continue();
                index++; // Increment index after each successful cursor continuation
            } else {
                updateFileInput(); // Update file input after loading images
                setInitialSelectedCover();
            }
        };

    }


    // Function to update selected cover index in localStorage
    function updateSelectedCoverIndex(index) {
        localStorage.setItem('selectedCoverIndex', index);
        console.log('Selected cover index updated in localStorage:', index);
    }

    // Function to get the selected cover index from localStorage
    function getSelectedCoverIndex() {
        return localStorage.getItem('selectedCoverIndex');
    }

    // Function to set the initial selected cover index
    function setInitialSelectedCover() {
        const selectedIndex = getSelectedCoverIndex();
        if (selectedIndex !== null) {
            document.getElementById('image' + selectedIndex).checked = true;
        } else if (selectedFiles.length > 0) {
            document.getElementById('image0').checked = true;
            updateSelectedCoverIndex(0);
        }
    }

    // Function to remove an image from IndexedDB
    function removeImageFromDB(index) {
        const transaction = db.transaction(['images'], 'readwrite');
        const objectStore = transaction.objectStore('images');
        const request = objectStore.delete(index);

        request.onsuccess = function(event) {
            console.log('Image with index', index, 'deleted from IndexedDB');
        };

        request.onerror = function(event) {
            console.error('Error deleting image:', event.target.errorCode);
        };
    }

    // Update file input value programmatically
    function updateFileInput() {
        imgInp.value = '';
        const newFileList = new ClipboardEvent('').clipboardData || new DataTransfer();
        selectedFiles.forEach(file => {
            const blob = dataURItoBlob(file.dataUrl);
            const newFile = new File([blob], file.filename);
            newFileList.items.add(newFile);
        });
        imgInp.files = newFileList.files;
        console.log('Updated filenames:', selectedFiles.map(file => file.filename));

        // Show the lastInputGroup if there are files
        if (selectedFiles.length > 0) {
            lastInputGroup.style.display = 'block';
        }
    }

   // Function to render image into preview container
// Function to render image into preview container
function renderImage(dataUrl, filename, key, index) {
    const formGroup = document.createElement('div');
    formGroup.classList.add('form-group');

    const img = document.createElement('img');
    img.src = dataUrl;
    img.classList.add('img-thumbnail', 'mr-2', 'mb-2');

    const radioInput = document.createElement('input');
    radioInput.type = 'radio';
    radioInput.name = 'cover_image';
    radioInput.value = index;
    radioInput.id = 'image' + key;
    radioInput.classList.add('d-none');

    radioInput.addEventListener('change', function() {
        updateSelectedCoverIndex(index);
    });

    const label = document.createElement('label');
    label.htmlFor = 'image' + key;
    label.classList.add('w-100');
    label.appendChild(img);

    const filenameInput = document.createElement('input');
    filenameInput.type = 'text';
    filenameInput.name = 'filename[]';
    filenameInput.value = filename;
    filenameInput.classList.add('form-control', 'mb-2');
    filenameInput.placeholder = 'Filename';

    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2');
    closeButton.textContent = 'Remove';
    closeButton.addEventListener('click', () => {
        previewContainer.removeChild(formGroup);
        selectedFiles.splice(index, 1); // Remove from selectedFiles array
        removeImageFromDB(key); // Remove from IndexedDB using the key
        updateFileInput();
        // After removing, re-index all images if necessary
        previewContainer.childNodes.forEach((node, idx) => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                const radioInput = node.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.value = idx;
                    radioInput.id = 'image' + idx;
                    node.querySelector('label').htmlFor = 'image' + idx;
                }
            }
        });
    });

    formGroup.appendChild(radioInput);
    formGroup.appendChild(label);
    formGroup.appendChild(filenameInput);
    formGroup.appendChild(closeButton);
    previewContainer.appendChild(formGroup);

    // Check for Laravel validation errors related to images
    @php
        $imageErrors = $errors->get('images.*');
    @endphp

    const errorsJson = JSON.parse('{!! addslashes(json_encode($imageErrors)) !!}');
    console.log('Errors JSON:', errorsJson);
    console.log('Index:', index);
    console.log('Key:', key);

    // PROBLEM: in IndexedDB the index id or index is always being incremented
    const errorKey = `images.${index}`;
    if (errorsJson && errorsJson[errorKey]) {
        const errorMessages = errorsJson[errorKey];
        console.log('Error messages found:', errorMessages);

        errorMessages.forEach(errorMessage => {
            const errorParagraph = document.createElement('p');
            errorParagraph.classList.add('text-danger');
            errorParagraph.innerHTML = errorMessage;
            formGroup.appendChild(errorParagraph);
        });
    } else {
        console.log('No error messages found for index:', index);
    }
}


    // Helper function to convert data URI to blob
    function dataURItoBlob(dataURI) {
        const splitDataURI = dataURI.split(',');
        const byteString = atob(splitDataURI[1]);
        const mimeString = splitDataURI[0].split(':')[1].split(';')[0];
        const arrayBuffer = new ArrayBuffer(byteString.length);
        const uintArray = new Uint8Array(arrayBuffer);

        for (let i = 0; i < byteString.length; i++) {
            uintArray[i] = byteString.charCodeAt(i);
        }

        return new Blob([arrayBuffer], { type: mimeString });
    }

    // Event listener for file input change
    imgInp.onchange = evt => {
        const files = Array.from(imgInp.files);
        files.forEach((file, index) => {
            if (!file.type.startsWith('image/')) {
                alert('Please select only image files.');
                return;
            }
            if (file.size > 100 * 1024 * 1024) { // Adjusted the size limit to 10MB
                alert('Please select an image file no bigger than 10MB.');
                return;
            }

            // Logic to get name from single_name input field and sanitize
            let singleName = document.querySelector('#single_name').value.trim();
            singleName = sanitizeFilename(singleName);

            // Determine filename based on singleName or default pattern
            let filename;
            if (singleName) {
                filename = `${singleName}${selectedFiles.length + 1}`;
            } else {
                filename = `Animal${selectedFiles.length + 1}`;
            }

            const reader = new FileReader();
            reader.onload = () => {
                const key = Date.now() + index;
                selectedFiles.push({ dataUrl: reader.result, filename: file.name, key });
                addImageToDB(reader.result, filename, key); // Add to IndexedDB
                renderImage(reader.result, filename, key, index);
                updateFileInput();
                if (selectedFiles.length === 1) {
                    document.getElementById('image0').checked = true;
                    updateSelectedCoverIndex(0);
                }
            };
            reader.readAsDataURL(file);
        });
        if (selectedFiles.length > 0) {
            lastInputGroup.style.display = 'block';
        }
    };
 
    // Function to sanitize filename based on provided illegal characters
    function sanitizeFilename(name) {
        const illegalChars = ['|', '<', '>', ':', '"', '/', '\\', '?', '*'];
        illegalChars.forEach(char => {
            name = name.replace(new RegExp('\\' + char, 'g'), ''); // Remove illegal characters
        });
        return name.trim(); // Trim any leading or trailing whitespace
    }
    
    // Initialize IndexedDB and load images from it on page load
    openDB().then(loadImagesFromDB).catch(console.error);

    // Function to clear all data from IndexedDB
    function clearIndexedDB() {
    const transaction = db.transaction(['images'], 'readwrite');
    const objectStore = transaction.objectStore('images');
    const request = objectStore.clear();

    request.onsuccess = function(event) {
        console.log('IndexedDB cleared successfully');
    };

    request.onerror = function(event) {
        console.error('Error clearing IndexedDB:', event.target.errorCode);
    };
}


</script>


    
<style>
.img-thumbnail {
    border: 2px solid transparent;
    max-width: 100%;
}
input[type="radio"]:checked + label .img-thumbnail {
    border-color: blue;
}
#preview {
    display: none;
    max-width: 300px;
}
</style>
    
</body>
@endsection

