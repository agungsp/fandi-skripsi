@extends('layouts.sb-admin')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Analisa')

{{-- CONTENT TITLE --}}
@section('title-content', 'Analisa')

{{-- CONTENT --}}
@section('content')
    <div class="card mb-3 shadow">
        <div class="card-body" style="max-height: 480px; overflow-y: auto;">
            <div class="list-group list-group-flush p-1">
                @for ($i = 0; $i < 20; $i++)
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xl-10 col-lg-9 col-md-8 col-sm-8">
                                <span class="align-middle">{{ now()->subHours($i) }} | excel-file-{{ $i+1 }}.xlsx</span>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4">
                                <div class="row">
                                    <div class="col">
                                        @if ($i == 1)
                                            <button class="btn btn-success btn-sm btn-block rounded-pill" type="button">
                                                <i class="fas fa-eye"></i> Lihat
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')
    <script src="{{ asset('js/bs-custom-file-input.js') }}"></script>
    <script>
        $(document).ready(() => {
            bsCustomFileInput.init();
        });
    </script>
@endsection
