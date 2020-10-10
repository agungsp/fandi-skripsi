@extends('layouts.sb-admin')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Transaksi')

{{-- CONTENT TITLE --}}
@section('title-content', 'Transaksi')

{{-- CONTENT --}}
@section('content')
    <div class="card mb-3 shadow">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <span class="small">
                        Input file penjualan | Confidence: 00% | Support: 00%
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-sm-12 p-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-12 p-2">
                    <button type="button" class="btn btn-primary btn-block rounded-pill">
                        <i class="fas fa-file-upload"></i> Upload
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3 shadow">
        <div class="card-body" style="max-height: 340px; overflow-y: auto;">
            <div class="list-group list-group-flush p-1">
                @for ($i = 0; $i < 20; $i++)
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xl-10 col-lg-9 col-md-8 col-sm-8">
                                <span class="align-middle">{{ now()->subHours($i) }} | excel-file-{{ $i+1 }}.xlsx</span>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="text-secondary align-middle"><i class="fas fa-cog"></i></a>
                                    </div>
                                    <div class="col">
                                        @if ($i == 0)
                                            <button class="btn btn-primary btn-sm btn-block rounded-pill" type="button">
                                                <i class="fas fa-calculator"></i> Hitung
                                            </button>
                                        @elseif($i == 1)
                                            <button class="btn btn-success btn-sm btn-block rounded-pill" type="button">
                                                <i class="fas fa-check-circle"></i> Selesai
                                            </button>
                                        @else
                                            <button class="btn btn-primary btn-sm btn-block rounded-pill" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Proses
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
