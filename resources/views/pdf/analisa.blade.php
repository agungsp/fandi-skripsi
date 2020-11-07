@extends('layouts.pdf')

{{-- TITLE --}}
@section('title', 'Hasil Analisa')

@section('title-content')
    {{ $file->name }}
@endsection

{{-- CONTENT --}}
@section('content')
    <table class="" style="">
        <thead class="">
            <tr>
                <th>Antecedent</th>
                <th>Consequent</th>
                <th>Support</th>
                <th>Confidence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $row)
                <tr>
                    <td>{{ $row->antecedent }}</td>
                    <td>{{ $row->consequent }}</td>
                    <td>{{ $row->support }}</td>
                    <td>{{ $row->confidence }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
