<!DOCTYPE html>
<html lang="en">
@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
@endphp

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Choose Role</title>
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> {{-- FontAwesome --}}
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-img {
            max-width: 150px;
            height: auto;
            border-radius: 50%;
            object-fit: contain;
        }

        @media (max-width: 576px) {
            .profile-img {
                max-width: 100px;
            }

            .btn-lg {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center align-items-center vh-100 p-3">

    {{-- Logo --}}
    <div class="mb-4 text-center">
        <img src="{{ $faviconUrl }}" alt="App Logo" class="profile-img img-fluid">
    </div>

    {{-- Choices --}}
    <div class="d-flex flex-column flex-sm-row gap-3 w-100" style="max-width: 400px;">
        <a href="{{ auth()->check() ? route('attendances.index') : route('loginPage') }}" class="btn btn-primary btn-lg flex-fill text-white text-decoration-none">
            <i class="fas fa-user-tie me-2"></i> Staff
        </a>

        <a href="{{ route('parents.index') }}" class="btn btn-success btn-lg flex-fill text-white text-decoration-none">
            <i class="fas fa-user-friends me-2"></i> Parent
        </a>
    </div>

</body>

</html>
