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


        <div>
            <h1>Course Gradesheets</h1>
            <h2>Overall Course Grades and Remarks</h2>
            <table>
                <thead>
                    <tr>
                        <th>Learner Name</th>
                        <th>Grade</th>
                        <th>Remarks</th>
                        <th>Date Started</th>
                        <th>Date Finished</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>{{ $gradeWithActivityData->learner_fname }} {{ $gradeWithActivityData->learner_lname }}</td>
                            <td>{{ $gradeWithActivityData->grade }}</td>
                            <td>{{ $gradeWithActivityData->remarks }}</td>
                            <td>{{ $gradeWithActivityData->start_period }}</td>
                            <td>{{ $gradeWithActivityData->finish_period }}</td>
                        </tr>
                </tbody>
            </table>
    
            <h2>Activities Scores and Progress</h2>
            <h3>{{ $gradeWithActivityData->learner_fname }} {{ $gradeWithActivityData->learner_lname }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Average Score</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gradeWithActivityData->activities as $activity)
                        @php
                            $activitySyllabusCollection = collect($activitySyllabusData);
                            $activitySyllabus = $activitySyllabusCollection->where('activity_id', $activity->activity_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $activity->activity_title }}</td>
                            <td>{{ $activity->average_score }}</td>
                            <td>{{ $activitySyllabus ? $activitySyllabus->total_score : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        
    
            <h2>Quizzes Scores and Progress</h2>
            <h3>{{ $gradeWithActivityData->learner_fname }} {{ $gradeWithActivityData->learner_lname }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Quiz</th>
                        <th>Average Score</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gradeWithActivityData->quizzes as $quiz)
                        @php
                            $quizSyllabusCollection = collect($quizSyllabusData);
                            $quizSyllabus = $quizSyllabusCollection->where('quiz_id', $quiz->quiz_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $quiz->quiz_title }}</td>
                            <td>{{ $quiz->average_score }}</td>
                            <td>{{ $quizSyllabus ? $quizSyllabus->total_score : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        
    <h3>{{ $gradeWithActivityData->learner_fname }} {{ $gradeWithActivityData->learner_lname }}</h3>

    <!-- Pre-assessment -->
    <h4>Pre-assessment</h4>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Score</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($learnerPreAssessmentData as $preAssessment)
                <tr>
                    <td>{{ $preAssessment->status }}</td>
                    <td>{{ $preAssessment->score }}</td>
                    <td>{{ $preAssessment->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Post-assessment -->
    <h4>Post-assessment</h4>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Score</th>
                <th>Remarks</th>
                <th>Attempt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($learnerPostAssessmentData as $postAssessment)
                <tr>
                    <td>{{ $postAssessment->status }}</td>
                    <td>{{ $postAssessment->score }}</td>
                    <td>{{ $postAssessment->remarks }}</td>
                    <td>{{ $postAssessment->attempt }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

        
        </div>

    </div>
</body>
</html>