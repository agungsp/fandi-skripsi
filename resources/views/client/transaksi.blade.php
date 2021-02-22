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
                                        <button id="btnCalculate" class="btn btn{{ $file->imported ? '' : '-outline'  }}-{{ $file->calculated ? 'success' : 'primary' }} btn-sm btn-block rounded-pill" {{ $file->calculated ? 'disabled' : '' }} type="button" value="{{ $file->id }}" data-imported="{{ $file->imported }}" data-calculated="{{ $file->calculated }}">
                                            @if (!$file->imported && !$file->calculated)
                                                <i class="fas fa-file-import"></i> Import
                                            @elseif ($file->imported && !$file->calculated)
                                                <i class="fas fa-calculator"></i> Hitung
                                            @elseif ($file->imported && $file->calculated)
                                                <i class="fas fa-check-circle"></i> Selesai
                                            @endif
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        elapsed: <span id="counter_{{ $file->id }}">0</span>
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
                <div class="modal-body" id="modalSettingBody">
                    <form id="formSetting">
                        @csrf
                        <input type="hidden" name="file_id" id="file_id" value="">
                        <div class="form-group">
                            <label for="confidence" class="d-flex justify-content-between">
                                Confidence
                                <input type="number" class="form-control text-right"
                                       name="confidence_value" id="confidence_value"
                                       style="width: 120px; height: 30px;" value="{{ $setting->confidence }}"
                                       min="0.000001" max="100"
                                       step="0.000001" onchange="changeCSNumValue('confidence')">
                            </label>
                            <input type="range" min="0.000001"
                                   max="100" value="{{ $setting->confidence }}"
                                   step="0.000001" name="confidence"
                                   id="confidence" class="form-control"
                                   oninput="changeCSRangeValue('confidence')" required>
                        </div>
                        <div class="form-group">
                            <label for="support" class="d-flex justify-content-between">
                                Support
                                <input type="number" class="form-control text-right"
                                       name="support_value" id="support_value"
                                       style="width: 120px; height: 30px;" value="{{ $setting->support }}"
                                       min="0.000001" max="100"
                                       step="0.000001" onchange="changeCSNumValue('support')">
                            </label>
                            <input type="range" min="0.000001"
                                   max="100" value="{{ $setting->support }}"
                                   step="0.000001" name="support"
                                   id="support" class="form-control"
                                   oninput="changeCSRangeValue('support')" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between" id="modalSettingFooter">
                    <button id="btnDeleteFile" class="btn btn-danger rounded-pill btn-sm" data-file="">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>

                    <div>
                        <button type="reset" id="btnCancel" class="btn btn-outline-secondary rounded-pill btn-sm" data-dismiss="modal">Batal</button>
                        <button id="btnSave" class="btn btn-sm btn-primary rounded-pill" type="button" value="">
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
    </div>

    <div id="modalDeleteBody" class="d-none">
        <p>This file will be deleted. Are you sure?</p>
    </div>

    <div id="modalDeleteFooter" class="d-none">
        <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm" onclick="location.reload();">Batal</button>
        <form id="formDeleteFile" action="{{ route('transaksi.deleteFile') }}" method="post">
            @csrf
            <input type="hidden" name="file_id_to_delete" id="file_id_to_delete">
            <button type="submit" class="btn btn-sm btn-danger rounded-pill" type="button">
                Hapus
            </button>
        </form>
    </div>
@endsection

{{-- JS --}}
@section('js')
    <script src="{{ asset('js/bs-custom-file-input.js') }}"></script>
    <script src="{{ asset('js/humanize-duration.js') }}"></script>
    <script>
        const htmlImport   = '<i class="fas fa-file-import"></i> Import';
        const htmlProccess = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Proses';
        const htmlHitung   = '<i class="fas fa-calculator"></i> Hitung';
        const htmlSelesi   = '<i class="fas fa-check-circle"></i> Selesai';

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

        function changeCSNumValue(elem) {
            $('#' + elem).val(
                $('#' + elem + '_value').val()
            );
        }

        function changeCSRangeValue(elem) {
            $('#' + elem + '_value').val(
                $('#' + elem).val()
            );
        }

        $('body').on('click', '#btnCalculate', function () {
            let button          = $(this);
            button.html(htmlProccess);
            button.attr('disabled', true);
            let file_id         = button.attr('value');
            let data_imported   = button.attr('data-imported');
            let data_calculated = button.attr('data-calculated');
            let url             = '';
            let buttonHtml      = '';
            let buttonToggleClass = '';
            let buttonDisabled    = false;
            let is_imported       = false;
            let is_calculated     = false;
            let exec_time = 0;

            if (data_imported == 0 && data_calculated == 0) {
                url               = "{{ route('transaksi.import') }}";
                buttonHtml        = htmlHitung;
                buttonToggleClass = 'btn-outline-primary btn-primary';
                is_imported       = true;
            }
            else if (data_imported == 1 && data_calculated == 0) {
                url               = "{{ route('transaksi.calculate') }}";
                buttonHtml        = htmlSelesi;
                buttonToggleClass = 'btn-primary btn-success';
                buttonDisabled    = true;
                is_imported       = true;
                is_calculated     = true;
            }

            this.addInterval = setInterval(() => {
                exec_time += 1000;
                $('#counter_'+file_id).html(humanizeDuration(exec_time));
            }, 1000);


            $.ajax({
                type   : "POST",
                url    : url,
                data   : {'file_id': file_id},
                success: (response) => {
                    button.toggleClass(buttonToggleClass);
                    button.html(buttonHtml);
                    button.attr('disabled', buttonDisabled);
                    button.attr('data-imported', is_imported ? 1 : 0);
                    button.attr('data-calculated', is_calculated ? 1 : 0);
                    clearInterval(this.addInterval);
                }
            });
        });

        $('body').on('click', '#btnSetting', function () {
            switchBtnMode('cal', true);
            let file_id = $(this).attr('value');
            $.get('{{ route('transaksi') }}/'+file_id+'/getSetting', (response) => {
                $('#modalSettingTitle').html(response.name)
                $('#file_id').val(response.id);
                $('#btnDeleteFile').attr('data-file', response.id);
                $('#btnSave').attr('value', response.id);
                $('#confidence').val(response.confidence);
                $('#confidence_value').val(response.confidence);
                $('#support').val(response.support);
                $('#support_value').val(response.support);
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
                        }, 1000);
                    }
                }
            });
        });

        $('body').on('click', '#btnDeleteFile', function () {
            let file_id = $(this).attr('data-file');
            $('#modalSettingBody').html(
                $('#modalDeleteBody').html()
            );
            $('#modalSettingFooter').html(
                $('#modalDeleteFooter').html()
            );
            $('#file_id_to_delete').val(file_id);
        });

        $(document).ready(() => {
            bsCustomFileInput.init();

            let confidence_value = $('#confidence').val();
            let support_value    = $('#support').val();
            $('#confidence_value').val(confidence_value);
            $('#support_value').val(support_value);
        });
    </script>
@endsection
