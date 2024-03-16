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
            <h2>Course Data</h2>
        </div>

        <div class="container">
            <div class="header">
                <h1 class="title">{{ $learnerData->learner_fname }} {{ $learnerData->learner_lname }}</h1>
                <h2>Enrolled Courses</h2>
            </div>
    
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Status</th>
                        <th>Course Progress</th>
                        <th>Start Period</th>
                        <th>Finish Period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($learnerCourseData as $courseData)
                        <tr>
                            <td>{{ $courseData->course_name }}</td>
                            <td>{{ $courseData->status }}</td>
                            <td>{{ $courseData->course_progress }}</td>
                            <td>{{ $courseData->start_period }}</td>
                            <td>{{ $courseData->finish_period }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>