@extends('layouts.app')

@section('content')
<head>
    <title>{{ env('APP_NAME', 'Pet Food') }} | {{ __('app.section.my_pets.name') }}</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- RowReorder CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">

    <!-- Responsive CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- RowReorder JavaScript -->
    <script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>

    <!-- Responsive JavaScript -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
</head>
<body>
@if (session('success'))
<div class="alert alert-success mx-4">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger mx-4">{{ session('error') }}</div>
@endif
<div id="my-pets" style="width: 100%; max-width: 1000px;">
    <h1>MY PETS</h1>
    <form class="pets-section" action="{{ route('pets.store', ['locale' => app()->getLocale()]) }}" method="POST">
        @csrf
        <table class="table" id="pets_table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Animal Species</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control" id="gbif_id" name="gbif_id" required>
                            @foreach($animals as $gbif_id => $name)
                                <option value="{{ $gbif_id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('gbif_id')
                            <span class="text-danger fs-5">{{ $message }}</span>
                        @enderror
                    </td>
                    <td>
                        <input type="text" name="nickname" class="form-control">
                        @error('nickname')
                            <span class="text-danger fs-5">{{ $message }}</span>
                        @enderror
                    </td>
                    <td>
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="d-flex flex-column">
                                <input id="imgInp" type="file" name="filename" class="form-control-file">
                                @error('filename')
                                    <span class="text-danger fs-5">{{ $message }}</span>
                                @enderror
                                <img id="imgPreview" class="d-none" src="" alt="Pet image" width="100" height="100">
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">Add Pet</button>
                    </td>
                </tr>
                @if($pets)
                @foreach ($pets as $pet)
                    <tr>
                        <td>{{ $pet->species_name }}</td>
                        <td>{{ $pet->nickname }}</td>
                        <td>
                            @if ($pet->filename)
                                <img src="{{ asset('storage/' . $pet->filename) }}" alt="Pet Image" width="100">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('pets.destroy', ['locale' => app()->getLocale(), 'id' => $pet->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @else
                    <p>No pets added yet.</p>
                @endif
            </tbody>
        </table>
    </form>
</div>
<script>
new DataTable('#pets_table', {
    responsive: true,
    paging: false,
    info: false,
    lengthMenu: false,
    searching: false,
});

imgInp.onchange = evt => {
    const [file] = imgInp.files;
    if (file) {
        imgPreview.src = URL.createObjectURL(file);
        imgPreview.classList.remove('d-none');
    }
};
</script>
</body>
@endsection
