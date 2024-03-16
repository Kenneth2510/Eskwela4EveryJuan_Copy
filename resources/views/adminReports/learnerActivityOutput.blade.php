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
                <h1 class="title">{{$courseData->course_name}}</h1>
                <h1 class="title">{{ $learnerData->learner_fname }} {{ $learnerData->learner_lname }}</h1>
                <h1 class="title">{{ $activityData->activity_title }} - Attempt {{ $attempt }}</h1>
                
                <h1 class="title">{{$learnerActivityOutput->created_at}}</h1>
            </div>
            
            <div class="activityInstructions">
                <h2>Activity Instructions:</h2>
                <p>{{ $activityData->activity_instructions }}</p>
            </div>


            <div class="activityOutput">
                <h2>Your Output:</h2>
                <p>{{ $learnerActivityOutput->answer }}</p>
            </div>


            
            <div class="criteriaScores">
                <h2>Criteria Scores:</h2>
                @foreach ($learnerActivityScore as $score)
                    <div class="criteriaScore">
                        <p><strong>{{ $score->criteria_title }}</strong></p>
                        <p>Score: {{ $score->learner_score }} / {{ $score->criteria_score }}</p>
                    </div>
                @endforeach
            </div>

            @php
            $totalCriteriaScore = 0;
            foreach ($learnerActivityScore as $score) {
                $totalCriteriaScore += $score->criteria_score;
            }
            @endphp
            
            <div class="totalScore">
                <h2>Total:</h2>
                <p>{{ $learnerActivityOutput->total_score }} / {{ $totalCriteriaScore }}</p>
            </div>
            
            <div class="remarks">
                <h2>Instructor Remarks:</h2>
                <p>{{ $learnerActivityOutput->remarks }}</p>
            </div>
            
        </div>
    </div>
</body>
</html>