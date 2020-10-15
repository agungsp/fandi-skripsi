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
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card mb-3 shadow">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <span class="small">
                        Input file penjualan | Confidence: {{ $setting->confidence }}% | Support: {{ $setting->support }}%
                    </span>
                </div>
            </div>
            <form action="{{ route('transaksi.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-10 col-sm-12 p-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="excelFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 p-2">
                        <button type="submit" class="btn btn-primary btn-block rounded-pill">
                            <i class="fas fa-file-upload"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3 shadow">
        <div class="card-body" style="max-height: 340px; overflow-y: auto;">
            <div class="list-group list-group-flush p-1">
                @if (count($files) == 0)
                    File not exist.
                @endif
                @foreach ($files as $file)
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xl-10 col-lg-9 col-md-8 col-sm-8">
                                <span class="align-middle">{{ $file->created_at }} | {{ $file->name }}</span>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="text-secondary align-middle" role="button"><i class="fas fa-cog"></i></a>
                                    </div>
                                    <div class="col">
                                        <button id="btnCalculate" class="btn btn-{{ $file->calculated ? 'success' : 'primary' }} btn-sm btn-block rounded-pill" type="button" value="{{ $file->id }}">
                                            <div id="sucMode" class="{{ $file->calculated ? '' : 'd-none' }}">
                                                <i class="fas fa-check-circle"></i> Selesai
                                            </div>
                                            <div id="calMode" class="{{ $file->calculated ? 'd-none' : '' }}">
                                                <i class="fas fa-calculator"></i> Hitung
                                            </div>
                                            <div id="procMode" class="d-none">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Proses
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
        function switchBtnMode(mode) {
            $('#btnCalculate').attr('disabled', true);
            $('#btnCalculate').removeClass('btn-primary');
            $('#btnCalculate').removeClass('btn-success');
            $('#calMode').addClass('d-none');
            $('#procMode').addClass('d-none');
            $('#sucMode').addClass('d-none');
            switch (mode) {
                case 'suc':
                    $('#btnCalculate').attr('disabled', true);
                    $('#btnCalculate').addClass('btn-success');
                    $('#sucMode').removeClass('d-none');
                    break;
                case 'cal':
                    $('#btnCalculate').attr('disabled', false);
                    $('#btnCalculate').addClass('btn-primary');
                    $('#calMode').removeClass('d-none');
                    break;
                case 'proc':
                    $('#btnCalculate').attr('disabled', true);
                    $('#btnCalculate').addClass('btn-primary');
                    $('#procMode').removeClass('d-none');
                    break;
                default:
                    break;
            }
        }

        $('body').on('click', '#btnCalculate', () => {
            switchBtnMode('proc');
            let file_id = $('#btnCalculate').attr('value');
            $.ajax({
                type   : "POST",
                url    : "{{ route('transaksi.calculate') }}",
                data   : {'file_id': file_id},
                success: (response) => {
                    console.log(response);
                    switchBtnMode('suc');
                }
            });
        });

        $(document).ready(() => {
            bsCustomFileInput.init();
        });
    </script>
@endsection
