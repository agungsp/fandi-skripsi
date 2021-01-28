@php
    // Ubah dari "true" menjadi "false" jika ingin menyembunyikan tabel kaidah asosiasi
    $show_kaidah_asosiasi = true;
@endphp
@extends('layouts.sb-admin')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('data-tables/datatables.min.css') }}">
    <style>
        .btn-float{
            position  : fixed;
            top       : 75px;
            right     : 40px;
            z-index   : 1000;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Analisa Detail')

{{-- CONTENT TITLE --}}
@section('title-content', 'Analisa Detail | ' . $file->name)

{{-- RIGHT TITLE CONTENT --}}
@section('right-title-content')

@endsection

{{-- CONTENT --}}
@section('content')
    <a href="{{ route('analisa.toPdf', $file->name) }}" class="btn btn-danger btn-float rounded-pill shadow">
        <i class="fas fa-file-pdf"></i> Export "Hasil Asosiasi Final"
    </a>

    <div class="card mb-3 shadow">
        <div class="card-body">
            <h5>Rules</h5>
            <div class="table-responsive">
                <table id="tableRules" class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rules</th>
                            <th>Support</th>
                            <th>Confidence</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @for ($i = 2; $i <= $max_combination; $i++)
        <div class="card mb-3 shadow">
            <div class="card-body">
                <h5>Itemset Kombinasi {{ $i }}</h5>
                <div class="table-responsive">
                    <table id="tableItemset{{ $i }}" class="table table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Itemset</th>
                                <th>Jumlah</th>
                                <th>Support</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    @endfor

    @if ($show_kaidah_asosiasi)
        <div class="card mb-3 shadow">
            <div class="card-body">
                <h5>Kaidah Asosiasi</h5>
                <div class="table-responsive">
                    <table id="tableAssociationRule" class="table table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Asosiasi</th>
                                <th>Support</th>
                                <th>Confidence</th>
                                <th>Support &times; Confidence</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card mb-3 shadow">
        <div class="card-body">
            <h5>Hasil Asosiasi Final</h5>
            <div class="table-responsive">
                <table id="tableFinalResult" class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Asosiasi</th>
                            <th>Support</th>
                            <th>Confidence</th>
                            <th>Support &times; Confidence</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')
    <script src="{{ asset('data-tables/datatables.min.js') }}"></script>
    <script>
        let tableRules = $('#tableRules').DataTable({
            processing: true,
            serverSide: true,
            ajax      : '{{ route('analisa.rules', $file->id) }}',
            scrollX   : true,
            searching : false,
            lengthMenu: [5, 10, 25, 50],
            columns   : [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    type: 'num',
                    className: 'text-right',
                },
                {
                    data: 'rules',
                    name: 'rules',
                    type: 'string',
                    sorting: false,
                },
                {
                    data: 'support',
                    name: 'support',
                    type: 'num-fmt',
                    className: 'text-right',
                },
                {
                    data: 'confidence',
                    name: 'confidence',
                    type: 'num-fmt',
                    className: 'text-right',
                }
            ],
            order: [[ 0, "asc" ]],
        });

        for (let i = 2; i <= {{ $max_combination }}; i++) {
            $('#tableItemset' + i).DataTable({
                processing: true,
                serverSide: true,
                ajax      : '{{ route('analisa') }}/{{ $file->id }}/' + i + '/itemsetCombine',
                scrollX   : true,
                searching : false,
                lengthMenu: [5, 10, 25, 50],
                columns   : [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        type: 'num',
                        className: 'text-right',
                    },
                    {
                        data: 'itemset',
                        name: 'itemset',
                        type: 'string',
                        sorting: false,
                    },
                    {
                        data: 'count',
                        name: 'count',
                        type: 'num',
                        className: 'text-right',
                    },
                    {
                        data: 'support',
                        name: 'support',
                        type: 'num-fmt',
                        className: 'text-right',
                    }
                ],
                order: [[ 0, "asc" ]],
            });
        }

        let tableAssociationRule = $('#tableAssociationRule').DataTable({
            processing: true,
            serverSide: true,
            ajax      : '{{ route('analisa.associationRule', $file->id) }}',
            scrollX   : true,
            searching : false,
            lengthMenu: [5, 10, 25, 50],
            columns   : [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    type: 'num',
                    className: 'text-right',
                },
                {
                    data: 'association',
                    name: 'association',
                    type: 'string',
                    sorting: false,
                },
                {
                    data: 'support',
                    name: 'support',
                    type: 'num-fmt',
                    className: 'text-right',
                },
                {
                    data: 'confidence',
                    name: 'confidence',
                    type: 'num-fmt',
                    className: 'text-right',
                },
                {
                    data: 'sxc',
                    name: 'sxc',
                    type: 'num-fmt',
                    className: 'text-right',
                }
            ],
            order: [[ 0, "asc" ]],
        });

        let tableFinalResult = $('#tableFinalResult').DataTable({
            processing: true,
            serverSide: true,
            ajax      : '{{ route('analisa.finalResult', $file->id) }}',
            scrollX   : true,
            searching : false,
            lengthMenu: [5, 10, 25, 50],
            columns   : [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    type: 'num',
                    className: 'text-right',
                },
                {
                    data: 'association',
                    name: 'association',
                    type: 'string',
                    sorting: false,
                },
                {
                    data: 'support',
                    name: 'support',
                    type: 'num-fmt',
                    className: 'text-right',
                },
                {
                    data: 'confidence',
                    name: 'confidence',
                    type: 'num-fmt',
                    className: 'text-right',
                },
                {
                    data: 'sxc',
                    name: 'sxc',
                    type: 'num-fmt',
                    className: 'text-right',
                }
            ],
            order: [[ 0, "asc" ]],
        });

        $(document).ready(() => {

        });
    </script>
@endsection
