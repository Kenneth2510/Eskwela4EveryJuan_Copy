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
        @if($courseCategory === 'Simple')
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Instructor Name</th>
                    <th>Created At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courseData as $course)
                    <tr>
                        <td>{{ $course->course_name }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->created_at }}</td>
                        <td>{{ $course->course_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else 
        @foreach ($courseData as $course)
            <div>
                <h2>{{ $course->course_name }}</h2>
                <p><strong>Instructor:</strong> {{ $course->name }}</p>
                <p><strong>Created At:</strong> {{ $course->created_at }}</p>
                <p><strong>Status:</strong> {{ $course->course_status }}</p>
                <h3>Syllabus</h3>
                <ul>
                    @foreach ($course->syllabusData as $syllabus)
                        <li>{{ $syllabus->category }} - {{ $syllabus->topic_title }}</li>
                    @endforeach
                </ul>
                <h3>Learners in the Course</h3>
                <ul>
                        <li>Total: {{ $course->learnerCourseData }}</li>
                </ul>
            </div>
        @endforeach
        @endif
    </div>
</body>
</html>
