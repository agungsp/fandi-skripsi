<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table {
            border-collapse: collapse;
            font-size      : 11pt;
            font-family    : Arial, Helvetica, sans-serif;
        }

        .table {
            width        : 100%;
            margin-bottom: 1rem;
            color        : #212529;
        }

        .table th,
        .table td {
            padding       : 0.75rem;
            vertical-align: top;
            border-top    : 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom : 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table .thead-dark th {
            color           : #fff;
            background-color: #343a40;
            border-color    : #454d55;
        }
    </style>
    <title>Result from file {{ $file->name }}</title>
</head>
<body>
    {{-- <h4>Result from file {{ $file->name }}</h4> --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><strong>Antecedent</strong></th>
                <th><strong>Consequent</strong></th>
                <th><strong>Support</strong></th>
                <th><strong>Confidence</strong></th>
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
</body>
</html>
