@extends('layouts.sb-admin')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.css') }}">
    <style>
        .btn-excel {
            border-radius: 30px 0px 0px 30px;
        }

        .btn-pdf {
            border-radius: 0px 30px 30px 0px;
        }
    </style>
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
                                @if ($file->calculated)
                                    <a href="{{ route('analisa.details', $file->id) }}"
                                       class="btn btn-block btn-outline-primary btn-sm rounded-pill"
                                       target="_blank">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    {{-- <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                        <a href="{{ route('analisa.toExcel', $file->name) }}" class="btn btn-outline-success btn-sm btn-excel">
                                            <i class="fas fa-file-excel"></i> Excel
                                        </a>
                                        <a href="{{ route('analisa.toPdf', $file->name) }}" class="btn btn-outline-danger btn-sm btn-pdf">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </div> --}}
                                @endif
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
    <script src="{{ asset('DataTables/datatables.js') }}"></script>
    <script>
        let tableRules = null;

        $('body').on('click', '#btnView', function () {
            let file_id = $(this).attr('data-file');
            tableRules = $('#tableRules').DataTable({
                processing: true,
                serverSide: true,
                ajax      : '{{ route('analisa') }}/' + file_id + '/rules',
                scrollX   : true,
                searching: false,
                columns   : [
                    {
                        data: 'num',
                        name: 'num',
                        type: 'num',
                    },
                    {
                        data: 'rules',
                        name: 'rules',
                        type: 'string',
                    },
                    {
                        data: 'support',
                        name: 'support',
                        type: 'num-fmt',
                    },
                    {
                        data: 'confidence',
                        name: 'confidence',
                        type: 'num-fmt',
                    }
                ],
                order: [[ 0, "asc" ]],
            });
        });

        $('#modalView').on('hidden.bs.modal', function (e) {
            tableRules.destroy();
        })

        $(document).ready(() => {

        });
    </script>
@endsection
