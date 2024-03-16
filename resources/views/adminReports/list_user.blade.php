<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <style>
        /* Reset default margin and padding */
        body, h1, h2, h3, h4, h5, h6, p, table, th, td {
            margin: 0;
            padding: 0;
        }

        /* Set font family */
        body {
            font-family: Arial, sans-serif;
        }

        /* Set basic styling for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Set styling for the headings */
        h1, h2, h3, h4, h5, h6 {
            margin-bottom: 10px;
        }

        /* Set styling for the container */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Set styling for the header */
        .header {
            background-color: #f7f7f7;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Set styling for the title */
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #025c26;
        }

        /* Set styling for the table headings */
        .table-head {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        /* Set styling for the table rows */
        .table-row {
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Eskwela4EveryJuan</h1>
            <h2>User Data</h2>
        </div>
        <table>
            <thead>
                <tr class="table-head">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Created At</th>
                    <th>Status</th>
                    @if ($category === 'Learners')
                        <th>Business Name</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($category === 'Learners')
                    @foreach ($learnerData as $learner)
                        <tr class="table-row">
                            <td>{{ $learner->name }}</td>
                            <td>{{ $learner->learner_email }}</td>
                            <td>{{ $learner->learner_contactno }}</td>
                            <td>{{ $learner->learner_gender }}</td>
                            <td>{{ $learner->learner_bday }}</td>
                            <td>{{ $learner->created_at }}</td>
                            <td>{{ $learner->status }}</td>
                            <td>{{ $learner->business_name }}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($instructorData as $instructor)
                        <tr class="table-row">
                            <td>{{ $instructor->name }}</td>
                            <td>{{ $instructor->instructor_email }}</td>
                            <td>{{ $instructor->instructor_contactno }}</td>
                            <td>{{ $instructor->instructor_gender }}</td>
                            <td>{{ $instructor->instructor_bday }}</td>
                            <td>{{ $instructor->created_at }}</td>
                            <td>{{ $instructor->status }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>
