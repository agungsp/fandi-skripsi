<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Document</title>
    {{-- <link href="{{ asset('sb-admin/css/styles.css') }}" rel="stylesheet" /> --}}
    <style>
        table {
            border-collapse: collapse;
            font-size: 11pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
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
            color: #fff;
            background-color: #343a40;
            border-color: #454d55;
        }
    </style>
</head>
<body>
    <h3>{{ $file->name }} | Hasil Asosiasi Final</h3>
    <hr>
    <div class="table-responsive">
        <table class="table table-borderd">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asosiasi</th>
                    <th>Support</th>
                    <th>Confidence</th>
                    <th>Support &times; Confidence</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row['association'] }}</td>
                        <td>{{ $row['support'] }} %</td>
                        <td>{{ $row['confidence'] }} %</td>
                        <td>{{ $row['sxc'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
