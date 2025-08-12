<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Choose Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> {{-- FontAwesome --}}
</head>

<body class="bg-light d-flex flex-column justify-content-center align-items-center vh-100 p-4">

    {{-- Logo --}}
    @php
        $logo = App\Models\Setting::where('name', 'logo')->first();
        $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
    @endphp
    <div class="mb-5 text-center">
        <img src="{{ $faviconUrl }}" alt="Profile" class="profile-img">
    </div>

    {{-- Choices --}}
    <div class="d-flex gap-4 flex-wrap justify-content-center" style="max-width: 400px; width: 100%;">
        <a href="{{ route('loginPage') }}"
            class="btn btn-primary btn-lg flex-grow-1 text-white text-decoration-none text-center">
            <i class="fas fa-user-tie me-2"></i> Staff
        </a>

        <a href="{{ route('parents.index') }}"
            class="btn btn-success btn-lg flex-grow-1 text-white text-decoration-none text-center">
            <i class="fas fa-user-friends me-2"></i> parent
        </a>
    </div>

</body>

</html>
