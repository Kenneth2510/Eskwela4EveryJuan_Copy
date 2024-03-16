<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $learnerData->learner_fname }} {{ $learnerData->learner_lname }} Pre-Assessment Output</title>
    <style>
        /* Include your styles here */
        /* Ensure that styles match the ones in your layout */
        .questionData {
            margin: 20px 0;
            padding: 20px;
            border-radius: 10px;
            border-width: 2px;
        }

        .border-darthmouthgreen {
            border-color: #00693e;
        }

        .border-red-600 {
            border-color: #b30000;
        }

        .text-darthmouthgreen {
            color: #00693e;
        }

        .text-red-600 {
            color: #b30000;
        }

        .questionChoices {
            margin-top: 10px;
            font-size: 1.25rem;
        }

        .question_isAnswered {
            width: 35px;
            height: 45px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.3s;
        }

        .bg-darthmouthgreen {
            background-color: #00693e;
        }

        .bg-red-600 {
            background-color: #b30000;
        }

        .question_isAnswered:hover {
            border-color: #00693e;
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
            {{-- <div class="header">
                <h1 class="title">{{ $courseData->course_name }} - {{ $learnerData->learner_fname }} {{ $learnerData->learner_lname }} Post-Assessment Output - Attempt {{$attempt}}</h1>
            </div> --}}
    

            <div class="header">
                <h1 class="title">{{$courseData->course_name}}</h1>
                <h1 class="title">{{ $learnerData->learner_fname }} {{ $learnerData->learner_lname }}</h1>
                <h1 class="title">Post-Assessment Output</h1>
                <h1 class="title">Attempt - {{$attempt}}</h1>
                <h1 class="title">{{$postAssessmentData->start_period}} - {{$postAssessmentData->finish_period}}</h1>
            </div>


            @php
            $totalRowCount = count($postAssessmentOutputData);
            @endphp

            <div class="totalScore">
                <h2>Total:</h2>
                <p>{{ $postAssessmentData->score }} / {{ $totalRowCount }}</p>
            </div>


            @foreach ($postAssessmentOutputData as $question)
            <div class="my-5 py-5 px-3 questionData @if ($question->isCorrect == 1) border-darthmouthgreen @else border-red-600 @endif rounded-lg">
                <div class="questionContent">
                    @if ($question->isCorrect == 1)
                        <span class="text-darthmouthgreen"><i class="fa-solid fa-check" style="color: #00693e;"></i> Correct</span>
                    @else
                        <span class="text-red-600"><i class="fa-solid fa-xmark" style="color: #b30000;"></i> Incorrect</span>
                    @endif
                    <h6 class="text-right opacity-40">Question {{ $loop->iteration }}</h6>
                    <p class="p-2 text-xl font-normal font-semibold">{{ $question->question }}</p>
                </div>
                <div class="mt-2 text-lg questionChoices">
                    @foreach (json_decode($question->all_choices) as $choice)
                        <input type="radio" name="{{ $loop->parent->iteration }}" class="w-5 h-5 mx-3 questionChoice" value="{{ $choice }}" @if ($choice === $question->answer) checked @endif disabled>
                        {{ $choice }}<br>
                    @endforeach
                </div>
                <div class="mt-5">
                    <h1 class="text-xl font-semibold"> Correct Answer: {{ json_decode($question->correct_answer)[0] }}</h1>
                </div>
            </div>
        @endforeach
        
        </div>
    </div>
</body>
</html>
