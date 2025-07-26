@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="width: 95%;">
    <h2 class="mb-4">{{ __('messages.Application Settings') }}</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            @foreach($settings as $setting)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $setting->name }}</h5>

                            {{-- Value Field --}}
                            <div class="mb-3">
                                @if($setting->type === 'file')
                                    <input type="file"
                                           name="settings[{{ $setting->id }}][value]"
                                           class="form-control">
                                @else
                                    <input type="text"
                                           name="settings[{{ $setting->id }}][value]"
                                           value="{{ $setting->value }}"
                                           class="form-control">
                                @endif
                            </div>

                            {{-- Type (Disabled) --}}
                            <div>
                                <input type="text"
                                       value="{{ $setting->type }}"
                                       class="form-control"
                                       disabled>
                            </div>

                            {{-- Hidden name (so backend still receives it) --}}
                            <input type="hidden"
                                   name="settings[{{ $setting->id }}][name]"
                                   value="{{ $setting->name }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
    </form>
</div>
@endsection
