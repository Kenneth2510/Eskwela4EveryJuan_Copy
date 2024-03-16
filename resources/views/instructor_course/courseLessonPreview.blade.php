<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title !== "" ? $title : 'Eskwela4EveryJuan'}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* width */
        ::-webkit-scrollbar {
          width: 15px;
        }
      
        /* Track */
        ::-webkit-scrollbar-track {
          box-shadow: inset 0 0 5px grey;
          border-radius: 10px;
        }
      
        /* Handle */
        ::-webkit-scrollbar-thumb {
          background: #00693e; /* Dartmouth Green */
          border-radius: 10px;
        }
      
        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
          background: #004026; /* Darker shade for hover */
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
        }


        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page_content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.5rem;
            background-color: #fff;
            overflow: auto;
            width: 100%;
        }

        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .flex-row {
            display: flex;
            align-items: center;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        label {
            font-size: 1rem;
            margin-right: 0.5rem;
        }

        hr {
            margin: 1.5rem 0;
            border-top: 0.125rem solid #e2e8f0;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        input[type="text"] {
            width: 100%;
            font-size: 2rem;
            font-weight: bold;
            border: none;
            padding: 0;
        }

        .lesson-content {
            width: 100%;
            padding: 1.25rem 1.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
        }

        .lesson-content-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .lesson-content-body {
            padding: 0.625rem 1.25rem;
            margin-top: 0.625rem;
            font-size: 1.25rem;
            font-weight: normal;
            white-space: pre-wrap;
        }

        .lesson-video {
            width: 100%;
            height: 400px;
            margin-top: 0.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .lesson-video iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="min-h-full bg-mainwhitebg font-poppins">
    <section id="start" class="page_content">
        <div style="background-color: #00693e; padding: 0.5rem; padding:20px; color: #fff; border-radius: 0.5rem; z-index: 50;">
            <h1>{{ $course->course_name }}</h1>
            <div class="">
                <h2>{{ $lessonInfo->lesson_title }}</h2>
            </div>
        </div>

        <div style="padding: 0.5rem;">
            <div style="margin-top: 0.25rem; color: #4b5563; font-size: 1rem;">
                <a href="{{ url('/instructor/courses') }}">course></a>
                <a href="{{ url("/instructor/course/$course->course_id") }}">{{$course->course_name}}></a>
                <a href="{{ url("/instructor/course/content/$course->course_id") }}">content></a>
                <a href="">{{ $lessonInfo->lesson_title }}</a>
            </div>
            <div id="lesson_title_area" style="margin-bottom: 1rem;">
                <div class="flex-row" style="padding-bottom: 0.75rem; margin-top: 1rem; border-bottom: 0.125rem solid #2f855a;">
                    <div style="position: relative; display: flex; align-items: center;">
                        <svg style="position: absolute; left: 0; border: 0.125rem solid #000; border-radius: 50%; padding: 0.125rem;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 32 32">
                            <path fill="currentColor" d="M19 10h7v2h-7zm0 5h7v2h-7zm0 5h7v2h-7zM6 10h7v2H6zm0 5h7v2H6zm0 5h7v2H6z"/>
                            <path fill="currentColor" d="M28 5H4a2.002 2.002 0 0 0-2 2v18a2.002 2.002 0 0 0 2 2h24a2.002 2.002 0 0 0 2-2V7a2.002 2.002 0 0 0-2-2ZM4 7h11v18H4Zm13 18V7h11v18Z"/>
                        </svg>
                        <div style="padding-left: 2.5rem; margin-left:25px; font-size: 1.875rem; font-weight: bold; border: none;">{{ $lessonInfo->lesson_title }}</div>
                    </div>
                </div>
                <div class="flex-row">
                    <h3>Estimated Time to Finish</h3>
                    <label for="hours">Hours:</label>
                    <h4>{{ isset($formattedDuration) ? explode(':', $formattedDuration)[0] : '' }}</h4>
                    <label for="minutes" style="margin-left: 1.25rem;">Minutes:</label>
                    <h4>{{ isset($formattedDuration) ? explode(':', $formattedDuration)[1] : '' }}</h4>
                </div>
                <hr>
            </div>

            <div class="mt-5">
                @if ($lessonInfo->picture !== null)
                <div id="lesson_img">
                    <img src="{{ asset("storage/$lessonInfo->picture") }}" alt="">
                </div>
                @endif
            </div>

            <div id="main_content_area">
                @forelse ($lessonContent as $lesson)
                <div data-content-order="{{$lesson->lesson_content_order}}" class="lesson-content">
                    <input type="text" style="font-size: 2rem; font-weight: bold; border: none; padding: 0;" disabled name="lesson_content_title_input" id="" value="{{ $lesson->lesson_content_title }}">
                    @if ($lesson->picture !== null)
                        <img src="{{ asset("storage/$lesson->picture") }}" alt="" style="margin-top: 1.25rem;">
                    @endif
                    <div class="lesson-content-body">
                        {!! $lesson->lesson_content !!}
                    </div>
                    @if ($lesson->video_url !== null)
                    <div class="lesson-video">
                        <h4>Videos embedded:</h4>
                        {{-- Extract the video ID from the iframe src attribute --}}
                        @php
                            $urlParts = parse_url($lesson->video_url);
                            $query = $urlParts['query'] ?? '';
                            parse_str($query, $queryParts);
                            $videoId = $queryParts['v'] ?? '';
                            $videoUrl = "https://www.youtube.com/watch?v=$videoId";
                        @endphp
                    
                        <p>{{ $videoUrl }}</p>
                    </div>
                    
                    
                    @endif
                </div>
                @empty
                <div style="margin-top: 0.5rem; margin-bottom: 1rem;">
                    <p style="padding-left: 1rem;">No Lesson content</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

</body>
</html>
