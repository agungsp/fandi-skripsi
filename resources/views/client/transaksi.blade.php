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
                                        <a href="#" id="btnSetting" class="text-secondary align-middle" role="button" value="{{ $file->id }}">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <button id="btnCalculate" class="btn btn-{{ $file->calculated ? 'success' : 'primary' }} btn-sm btn-block rounded-pill" {{ $file->calculated ? 'disabled' : '' }} type="button" value="{{ $file->id }}">
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
    <div id="modalSetting" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalSettingTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSettingTitle"></h5>
                </div>
                <div class="modal-body">
                    <form id="formSetting">
                        @csrf
                        <input type="hidden" name="file_id" id="file_id">
                        <div class="form-group">
                            <label for="confidence" class="d-flex justify-content-between">
                                Confidence
                                <span id="confidence_value"></span>
                            </label>
                            <input type="range" min="0" max="100" value="" step="1" name="confidence" id="confidence" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="support" class="d-flex justify-content-between">
                                Support
                                <span id="support_value"></span>
                            </label>
                            <input type="range" min="0" max="100" value="" step="1" name="support" id="support" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="reset" id="btnCancel" class="btn btn-outline-secondary rounded-pill btn-sm" data-dismiss="modal">Batal</button>
                    <button id="btnSave" class="btn btn-sm btn-primary rounded-pill" type="button" value="{{ $file->id }}">
                        <div id="sucSettingMode" class="">
                            <i class="fas fa-check-circle"></i> Selesai
                        </div>
                        <div id="calSettingMode" class="">
                            <i class="fas fa-save"></i> Simpan
                        </div>
                        <div id="procSettingMode" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Proses
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS --}}
@section('js')
    <script src="{{ asset('js/bs-custom-file-input.js') }}"></script>
    <script>
        function switchBtnMode(mode, isSetting = false) {
            if (isSetting) {
                $('#btnSave').attr('disabled', true);
                $('#btnCancel').attr('disabled', true);
                $('#btnSave').removeClass('btn-primary');
                $('#btnSave').removeClass('btn-success');
                $('#calSettingMode').addClass('d-none');
                $('#procSettingMode').addClass('d-none');
                $('#sucSettingMode').addClass('d-none');
            } else {
                $('#btnCalculate').attr('disabled', true);
                $('#btnCalculate').removeClass('btn-primary');
                $('#btnCalculate').removeClass('btn-success');
                $('#calMode').addClass('d-none');
                $('#procMode').addClass('d-none');
                $('#sucMode').addClass('d-none');
            }
            switch (mode) {
                case 'suc':
                    if (isSetting) {
                        $('#btnSave').attr('disabled', true);
                        $('#btnCancel').attr('disabled', true);
                        $('#btnSave').addClass('btn-success');
                        $('#sucSettingMode').removeClass('d-none');
                    } else {
                        $('#btnCalculate').attr('disabled', true);
                        $('#btnCalculate').addClass('btn-success');
                        $('#sucMode').removeClass('d-none');
                    }
                    break;
                case 'cal':
                    if (isSetting) {
                        $('#btnSave').attr('disabled', false);
                        $('#btnCancel').attr('disabled', false);
                        $('#btnSave').addClass('btn-primary');
                        $('#calSettingMode').removeClass('d-none');
                    } else {
                        $('#btnCalculate').attr('disabled', false);
                        $('#btnCalculate').addClass('btn-primary');
                        $('#calMode').removeClass('d-none');
                    }
                    break;
                case 'proc':
                    if (isSetting) {
                        $('#btnSave').attr('disabled', true);
                        $('#btnCancel').attr('disabled', true);
                        $('#btnSave').addClass('btn-primary');
                        $('#procSettingMode').removeClass('d-none');
                    } else {
                        $('#btnCalculate').attr('disabled', true);
                        $('#btnCalculate').addClass('btn-primary');
                        $('#procMode').removeClass('d-none');
                    }
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
                    switchBtnMode('suc');
                }
            });
        });

        $('body').on('click', '#btnSetting', () => {
            switchBtnMode('cal', true);
            let file_id = $('#btnSetting').attr('value');
            $.get('{{ route('transaksi') }}/'+file_id+'/getSetting', (response) => {
                $('#modalSettingTitle').html(response.name)
                $('#file_id').val(response.id);
                $('#confidence').val(response.confidence);
                $('#confidence_value').html(response.confidence);
                $('#support').val(response.support);
                $('#support_value').html(response.support);
                $('#modalSetting').modal('show');
            });
        });

        $('body').on('click', '#btnSave', () => {
            switchBtnMode('proc', true);
            let formSetting = $('#formSetting').serialize();
            $.ajax({
                type   : "POST",
                url    : "{{ route('transaksi.setSetting') }}",
                data   : formSetting,
                success: (response) => {
                    switchBtnMode('suc', true);
                    if (response) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });

        $(document).ready(() => {
            bsCustomFileInput.init();

            let confidence_value = $('#confidence').val();
            let support_value    = $('#support').val();
            $('#confidence_value').html(confidence_value);
            $('#support_value').html(support_value);

            $('body').on('input', '#confidence', function() {
                let value = $('#confidence').val();
                $('#confidence_value').html(value);
            });

            $('body').on('input', '#support', function() {
                let value = $('#support').val();
                $('#support_value').html(value);
            });
        });
    </script>
@endsection
