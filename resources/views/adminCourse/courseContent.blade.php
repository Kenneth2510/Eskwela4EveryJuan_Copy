@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Course Syllabus Management</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>
    <div class="mb-5">
        <a href="/admin/courseManage" class="">
            <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
        </a>
    </div>


    <div style="background-color:{{$mainBackgroundCol}};" class="z-50 p-2 text-white rounded-xl">
        <a href="{{ url("/admin/courseManage/$course->course_id") }}" class="my-2 bg-gray-400 rounded-full ">
            <svg class="fill-white"  xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
        </a>
        <h1 class="w-1/2 py-4 text-5xl font-semibold"><span class="">{{ $course->course_name }}</span></h1>
        {{-- subheaders --}}
        <div class="flex flex-col fill-mainwhitebg">
            <div class="flex flex-row my-2">
                <svg class="mr-2 " xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80H240Zm0-80h480v-640h-80v280l-100-60-100 60v-280H240v640Zm0 0v-640 640Zm200-360 100-60 100 60-100-60-100 60Z"/></svg>
                <p>{{ $course->course_code }}</p>
            </div>
            <div class="flex flex-row my-2">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120 200-272v-240L40-600l440-240 440 240v320h-80v-276l-80 44v240L480-120Zm0-332 274-148-274-148-274 148 274 148Zm0 241 200-108v-151L480-360 280-470v151l200 108Zm0-241Zm0 90Zm0 0Z"/></svg>
                <p>{{ $course->course_difficulty }}</p>
            </div>
            <div class="flex flex-row my-2">
                <p>Status: {{ $course->course_status }}</p>
            </div>
            <div class="flex">
                
                <div class="flex flex-row my-2">
                    <svg width="24" height="24" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_75_1498)">
                        <path d="M2.5 7.07007C4.7125 6.14507 7.885 5.14757 10.97 4.83757C14.295 4.50257 17.115 4.99507 18.75 6.71757V31.0826C16.4125 29.7576 13.45 29.5751 10.7175 29.8501C7.7675 30.1501 4.7925 31.0026 2.5 31.8776V7.07007ZM21.25 6.71757C22.885 4.99507 25.705 4.50257 29.03 4.83757C32.115 5.14757 35.2875 6.14507 37.5 7.07007V31.8776C35.205 31.0026 32.2325 30.1476 29.2825 29.8526C26.5475 29.5751 23.5875 29.7551 21.25 31.0826V6.71757ZM20 4.45757C17.5375 2.34007 13.9675 2.02507 10.7175 2.35007C6.9325 2.73257 3.1125 4.03007 0.7325 5.11257C0.514123 5.21189 0.328938 5.37195 0.199053 5.57365C0.0691667 5.77535 6.64286e-05 6.01017 0 6.25007L0 33.7501C5.7905e-05 33.9592 0.0525929 34.165 0.152793 34.3486C0.252993 34.5322 0.397654 34.6877 0.573527 34.8009C0.7494 34.914 0.950861 34.9813 1.15946 34.9964C1.36806 35.0116 1.57712 34.9742 1.7675 34.8876C3.9725 33.8876 7.525 32.6851 10.9675 32.3376C14.49 31.9826 17.4425 32.5551 19.025 34.5301C19.1421 34.6761 19.2905 34.7939 19.4593 34.8748C19.628 34.9558 19.8128 34.9978 20 34.9978C20.1872 34.9978 20.372 34.9558 20.5407 34.8748C20.7095 34.7939 20.8579 34.6761 20.975 34.5301C22.5575 32.5551 25.51 31.9826 29.03 32.3376C32.475 32.6851 36.03 33.8876 38.2325 34.8876C38.4229 34.9742 38.6319 35.0116 38.8405 34.9964C39.0491 34.9813 39.2506 34.914 39.4265 34.8009C39.6023 34.6877 39.747 34.5322 39.8472 34.3486C39.9474 34.165 39.9999 33.9592 40 33.7501V6.25007C39.9999 6.01017 39.9308 5.77535 39.8009 5.57365C39.6711 5.37195 39.4859 5.21189 39.2675 5.11257C36.8875 4.03007 33.0675 2.73257 29.2825 2.35007C26.0325 2.02257 22.4625 2.34007 20 4.45757Z" fill="#F8F8F8"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_75_1498">
                        <rect width="40" height="40" fill="white"/>
                        </clipPath>
                        </defs>
                        </svg>
                    <p class="mx-2">{{ $lessonCount }} Lessons</p>
                </div>
                <div class="flex flex-row my-2">
                    <svg width="24" height="24" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.25 26.25C5.5625 26.25 4.97375 26.005 4.48375 25.515C3.99375 25.025 3.74917 24.4367 3.75 23.75V6.25C3.75 5.5625 3.995 4.97375 4.485 4.48375C4.975 3.99375 5.56334 3.74917 6.25 3.75H11.5C11.7708 3 12.2242 2.39583 12.86 1.9375C13.4958 1.47917 14.2092 1.25 15 1.25C15.7917 1.25 16.5054 1.47917 17.1413 1.9375C17.7771 2.39583 18.23 3 18.5 3.75H23.75C24.4375 3.75 25.0263 3.995 25.5163 4.485C26.0063 4.975 26.2508 5.56333 26.25 6.25V23.75C26.25 24.4375 26.005 25.0263 25.515 25.5163C25.025 26.0063 24.4367 26.2508 23.75 26.25H6.25ZM6.25 23.75H23.75V6.25H6.25V23.75ZM8.75 21.25H17.5V18.75H8.75V21.25ZM8.75 16.25H21.25V13.75H8.75V16.25ZM8.75 11.25H21.25V8.75H8.75V11.25ZM15 5.3125C15.2708 5.3125 15.4946 5.22375 15.6713 5.04625C15.8479 4.86875 15.9367 4.645 15.9375 4.375C15.9375 4.10417 15.8488 3.88042 15.6713 3.70375C15.4938 3.52708 15.27 3.43833 15 3.4375C14.7292 3.4375 14.5054 3.52625 14.3288 3.70375C14.1521 3.88125 14.0633 4.105 14.0625 4.375C14.0625 4.64583 14.1513 4.86958 14.3288 5.04625C14.5063 5.22292 14.73 5.31167 15 5.3125Z" fill="white"/>
                        </svg>
                    <p class="mx-2">{{ $activityCount }} Activities</p>
                </div>
                <div class="flex flex-row my-2">
                    <svg width="24" height="24" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26.6391 8.59801L21.402 3.36207C21.2278 3.18792 21.0211 3.04977 20.7936 2.95551C20.5661 2.86126 20.3223 2.81274 20.076 2.81274C19.8297 2.81274 19.5859 2.86126 19.3584 2.95551C19.1308 3.04977 18.9241 3.18792 18.75 3.36207L4.29962 17.8125C4.12475 17.9859 3.98611 18.1924 3.89176 18.42C3.79741 18.6475 3.74922 18.8915 3.75001 19.1379V24.375C3.75001 24.8722 3.94755 25.3492 4.29918 25.7008C4.65081 26.0524 5.12773 26.25 5.62501 26.25H10.8621C11.1084 26.2508 11.3525 26.2026 11.58 26.1082C11.8075 26.0139 12.014 25.8752 12.1875 25.7004L21.9926 15.8964L22.4004 17.5254L18.0879 21.8367C17.912 22.0124 17.8131 22.2509 17.813 22.4995C17.8129 22.7482 17.9116 22.9867 18.0873 23.1627C18.2631 23.3386 18.5015 23.4375 18.7502 23.4376C18.9988 23.4377 19.2374 23.339 19.4133 23.1632L24.1008 18.4757C24.2154 18.3613 24.2985 18.2191 24.3418 18.063C24.3851 17.9069 24.3873 17.7423 24.3481 17.5851L23.5395 14.3496L26.6391 11.25C26.8132 11.0758 26.9514 10.8691 27.0456 10.6416C27.1399 10.4141 27.1884 10.1702 27.1884 9.92398C27.1884 9.67772 27.1399 9.43387 27.0456 9.20635C26.9514 8.97884 26.8132 8.77212 26.6391 8.59801ZM5.62501 21.0129L8.98712 24.375H5.62501V21.0129ZM11.25 23.9871L6.0129 18.75L15.9375 8.82535L21.1746 14.0625L11.25 23.9871ZM22.5 12.7371L17.2641 7.49996L20.0766 4.68746L25.3125 9.92457L22.5 12.7371Z" fill="white"/>
                        </svg>
                    <p class="mx-2">{{ $quizCount }} Quizzes</p>
                </div>
            </div>
            
        </div>
    </div>

    <div class="px-2">
        <div class="mt-1 text-gray-600 text-l">
            <a href="{{ url('/admin/courseManages') }}" class="">course></a>
            <a href="{{ url("/admin/courseManage/$course->course_id") }}">{{$course->course_name}}></a>
            <a href="{{ url("/admin/courseManage/content/$course->course_id") }}">content</a>
        </div>
        {{-- overview --}}
        <div class="w-full mb-8">
            <div class="flex items-center justify-between my-4 border-b-2 border-seagreen">
                <div class="flex items-center my-3">
                    <svg xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="30 "><path d="m787-145 28-28-75-75v-112h-40v128l87 87Zm-587 25q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/></svg>
                
                    <h1 class="mx-3 text-2xl font-semibold">General Overview</h1>
                </div>
            </div>
            <p class="px-4 text-justify" style="white-space: pre-wrap">{{ $course->course_description }}</p>
        </div>
        
        {{-- views --}}
        <div class="flex flex-col text-mainwhitebg fill-mainwhitebg">
            <button id="showSyllabusBtn" data-course-id="{{ $course->course_id }}" style="background-color:{{$mainBackgroundCol}}"   onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
            onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="flex items-center justify-between px-2 py-4 my-2 rounded-lg shadow-lg bg-seagreen">
                <div class="flex items-center">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.94273 31.25H36.25V1.25H7.86461C6.60883 1.25404 5.40564 1.75468 4.51766 2.64266C3.62968 3.53064 3.12904 4.73383 3.125 5.98961V33.6133H3.12586C3.12586 33.6251 3.125 33.6367 3.125 33.6487C3.125 36.4143 5.29547 38.7502 7.86461 38.7502H36.25V36.25H7.86461C6.6925 36.25 5.625 35.0101 5.625 33.6484C5.625 32.3484 6.68633 31.25 7.94273 31.25ZM28.125 3.77602V17.2773L24.3438 13.9577L20.625 17.2578V3.77602H28.125ZM18.125 3.75V20.625H20.597L24.3528 17.2923L28.1488 20.625H30.625V3.75H33.75V28.75H10.6313L10.625 3.75H18.125ZM7.86461 3.75H8.125L8.13094 28.75H7.94242C7.12952 28.7502 6.33073 28.9624 5.625 29.3659V6.00914C5.62309 5.41277 5.85781 4.84 6.27767 4.41648C6.69753 3.99295 7.26825 3.75327 7.86461 3.75Z" fill="#F8F8F8"/>
                        </svg>
                        
                    <h1 class="mx-5 text-xl font-semibold">Syllabus</h1>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
            </button>
            <h1 class="px-3 my-5 text-2xl font-bold text-black border-b-2 border-black">Course Content</h1>
            @forelse ($syllabus as $topic)
                @if ($topic->category == 'LESSON') 
                {{-- /admin/courseManage/content/$course->course_id/lesson/?mainBackgroundCol=$mainBackgroundCol --}}
                <a href="{{url("/admin/courseManage/content/$course->course_id/$topic->syllabus_id/lesson/$topic->topic_id")}}" 
                    style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
            onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="flex items-center justify-between px-2 py-4 my-2 text-white rounded-lg shadow-lg bg-seagreen">
                    <div class="flex items-center">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_75_1498)">
                            <path d="M2.5 7.07007C4.7125 6.14507 7.885 5.14757 10.97 4.83757C14.295 4.50257 17.115 4.99507 18.75 6.71757V31.0826C16.4125 29.7576 13.45 29.5751 10.7175 29.8501C7.7675 30.1501 4.7925 31.0026 2.5 31.8776V7.07007ZM21.25 6.71757C22.885 4.99507 25.705 4.50257 29.03 4.83757C32.115 5.14757 35.2875 6.14507 37.5 7.07007V31.8776C35.205 31.0026 32.2325 30.1476 29.2825 29.8526C26.5475 29.5751 23.5875 29.7551 21.25 31.0826V6.71757ZM20 4.45757C17.5375 2.34007 13.9675 2.02507 10.7175 2.35007C6.9325 2.73257 3.1125 4.03007 0.7325 5.11257C0.514123 5.21189 0.328938 5.37195 0.199053 5.57365C0.0691667 5.77535 6.64286e-05 6.01017 0 6.25007L0 33.7501C5.7905e-05 33.9592 0.0525929 34.165 0.152793 34.3486C0.252993 34.5322 0.397654 34.6877 0.573527 34.8009C0.7494 34.914 0.950861 34.9813 1.15946 34.9964C1.36806 35.0116 1.57712 34.9742 1.7675 34.8876C3.9725 33.8876 7.525 32.6851 10.9675 32.3376C14.49 31.9826 17.4425 32.5551 19.025 34.5301C19.1421 34.6761 19.2905 34.7939 19.4593 34.8748C19.628 34.9558 19.8128 34.9978 20 34.9978C20.1872 34.9978 20.372 34.9558 20.5407 34.8748C20.7095 34.7939 20.8579 34.6761 20.975 34.5301C22.5575 32.5551 25.51 31.9826 29.03 32.3376C32.475 32.6851 36.03 33.8876 38.2325 34.8876C38.4229 34.9742 38.6319 35.0116 38.8405 34.9964C39.0491 34.9813 39.2506 34.914 39.4265 34.8009C39.6023 34.6877 39.747 34.5322 39.8472 34.3486C39.9474 34.165 39.9999 33.9592 40 33.7501V6.25007C39.9999 6.01017 39.9308 5.77535 39.8009 5.57365C39.6711 5.37195 39.4859 5.21189 39.2675 5.11257C36.8875 4.03007 33.0675 2.73257 29.2825 2.35007C26.0325 2.02257 22.4625 2.34007 20 4.45757Z" fill="#F8F8F8"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_75_1498">
                            <rect width="40" height="40" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>
                            
                        <h1 class="mx-5 font-medium text-l">{{ $topic->topic_title }}</h1>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
                </a>
                @elseif ($topic->category == 'ACTIVITY')
                <a href="{{url("/admin/courseManage/content/$course->course_id/$topic->syllabus_id/activity/$topic->topic_id")}}" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
            onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="flex items-center justify-between px-2 py-4 my-2 rounded-lg shadow-lg bg-seagreen">
                    <div class="flex items-center">
                        <svg width="40" height="40" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.25 26.25C5.5625 26.25 4.97375 26.005 4.48375 25.515C3.99375 25.025 3.74917 24.4367 3.75 23.75V6.25C3.75 5.5625 3.995 4.97375 4.485 4.48375C4.975 3.99375 5.56334 3.74917 6.25 3.75H11.5C11.7708 3 12.2242 2.39583 12.86 1.9375C13.4958 1.47917 14.2092 1.25 15 1.25C15.7917 1.25 16.5054 1.47917 17.1413 1.9375C17.7771 2.39583 18.23 3 18.5 3.75H23.75C24.4375 3.75 25.0263 3.995 25.5163 4.485C26.0063 4.975 26.2508 5.56333 26.25 6.25V23.75C26.25 24.4375 26.005 25.0263 25.515 25.5163C25.025 26.0063 24.4367 26.2508 23.75 26.25H6.25ZM6.25 23.75H23.75V6.25H6.25V23.75ZM8.75 21.25H17.5V18.75H8.75V21.25ZM8.75 16.25H21.25V13.75H8.75V16.25ZM8.75 11.25H21.25V8.75H8.75V11.25ZM15 5.3125C15.2708 5.3125 15.4946 5.22375 15.6713 5.04625C15.8479 4.86875 15.9367 4.645 15.9375 4.375C15.9375 4.10417 15.8488 3.88042 15.6713 3.70375C15.4938 3.52708 15.27 3.43833 15 3.4375C14.7292 3.4375 14.5054 3.52625 14.3288 3.70375C14.1521 3.88125 14.0633 4.105 14.0625 4.375C14.0625 4.64583 14.1513 4.86958 14.3288 5.04625C14.5063 5.22292 14.73 5.31167 15 5.3125Z" fill="white"/>
                            </svg>
                            
                            
                        <h1 class="mx-5 font-medium text-l">{{ $topic->topic_title }}</h1>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
                </a>
                @else
                <a href="{{url("/admin/courseManage/content/$course->course_id/$topic->syllabus_id/quiz/$topic->topic_id")}}" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
            onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="flex items-center justify-between px-2 py-4 my-2 rounded-lg shadow-lg bg-seagreen">
                    <div class="flex items-center">
                        <svg width="40" height="40" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26.6391 8.59801L21.402 3.36207C21.2278 3.18792 21.0211 3.04977 20.7936 2.95551C20.5661 2.86126 20.3223 2.81274 20.076 2.81274C19.8297 2.81274 19.5859 2.86126 19.3584 2.95551C19.1308 3.04977 18.9241 3.18792 18.75 3.36207L4.29962 17.8125C4.12475 17.9859 3.98611 18.1924 3.89176 18.42C3.79741 18.6475 3.74922 18.8915 3.75001 19.1379V24.375C3.75001 24.8722 3.94755 25.3492 4.29918 25.7008C4.65081 26.0524 5.12773 26.25 5.62501 26.25H10.8621C11.1084 26.2508 11.3525 26.2026 11.58 26.1082C11.8075 26.0139 12.014 25.8752 12.1875 25.7004L21.9926 15.8964L22.4004 17.5254L18.0879 21.8367C17.912 22.0124 17.8131 22.2509 17.813 22.4995C17.8129 22.7482 17.9116 22.9867 18.0873 23.1627C18.2631 23.3386 18.5015 23.4375 18.7502 23.4376C18.9988 23.4377 19.2374 23.339 19.4133 23.1632L24.1008 18.4757C24.2154 18.3613 24.2985 18.2191 24.3418 18.063C24.3851 17.9069 24.3873 17.7423 24.3481 17.5851L23.5395 14.3496L26.6391 11.25C26.8132 11.0758 26.9514 10.8691 27.0456 10.6416C27.1399 10.4141 27.1884 10.1702 27.1884 9.92398C27.1884 9.67772 27.1399 9.43387 27.0456 9.20635C26.9514 8.97884 26.8132 8.77212 26.6391 8.59801ZM5.62501 21.0129L8.98712 24.375H5.62501V21.0129ZM11.25 23.9871L6.0129 18.75L15.9375 8.82535L21.1746 14.0625L11.25 23.9871ZM22.5 12.7371L17.2641 7.49996L20.0766 4.68746L25.3125 9.92457L22.5 12.7371Z" fill="white"/>
                            </svg>
                            
                            
                        <h1 class="mx-5 font-medium text-l">{{ $topic->topic_title }}</h1>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
                </a>
                @endif
                
            @empty
                <p>No content added</p>
            @endforelse

        </div>
    </div>


</section>
<div id="syllabusModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[1000px] h-[700px] overflow-y-auto">
        <div class="flex justify-end w-full">
            <button id="removeModalBtn">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <span class="absolute top-0 right-0 mt-2 mr-4 text-2xl text-gray-600 cursor-pointer close">&times;</span>
        <h2 class="mb-2 text-2xl font-semibold">Course Syllabus</h2>
        <table class="w-full mt-5 border-collapse rounded-xl">
            <thead>
                <tr class="text-white bg-seagreen border-seagreen">
                    <th class="p-2 border">Topic ID</th>
                    <th class="p-2 border">Topic Title</th>
                    <th class="p-2 border">Category</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody id="syllabusTableBody" class="overflow-y-auto">
                <!-- You can populate this with your syllabus data dynamically -->
            </tbody>
        </table>
        <div class="flex justify-center w-full mt-5">
            <button id="editSyllabusBtn" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Edit</button>
            <button id="addChangesBtn"  class="hidden px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen">Add</button>
            <button id="saveChangesBtn" data-course-id="{{$course->course_id}}" class="hidden px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen">Save Now</button>
            <button id="cancelChangesBtn"  class="hidden px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded">Cancel</button>
        </div>
    </div>
</div>



<div id="addTopicModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 modal">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button id="closeAddTopicModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold">Add Topic</h2>
        <div class="mt-4">
            <label for="topicName" class="text-lg font-semibold">Enter Topic Title:</label>
            <input type="text" id="topicName" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
        </div>
        <div class="mt-4">
            <label for="topicType" class="text-lg font-semibold">Select Topic Type:</label>
            <select id="topicType" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
                <option value="LESSON">LESSON</option>
                <option value="ACTIVITY">ACTIVITY</option>
                <option value="QUIZ">QUIZ</option>
            </select>
        </div>
        <div class="mt-4">
            <label for="insertLocation" class="text-lg font-semibold">Insert Location:</label>
            <select id="insertLocation" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
                    <option value="START">At the Beginning</option>
                @forelse ($syllabus as $topic)
                    <option value="{{ $topic->topic_title }}">AFTER {{ $topic->topic_title }}</option>
                @empty
                    <option value="">At the Beginning</option>
                @endforelse
                    <option value="END">In the End</option>
            </select>
        </div>
        <div class="flex justify-center w-full mt-5">
            <button id="confirmAddTopicBtn" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
            <button id="cancelAddTopicBtn" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded">Cancel</button>
        </div>
    </div>
</div>

<div id="deleteCourseModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">

    <div class="p-5 text-center bg-white rounded-lg">
        <p>Are you sure you want to delete this course?</p>
        <button type="button" id="confirmDelete" data-course-id="{{$course->course_id}}" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">Confirm</button>
        <button type="button" id="cancelDelete" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">Cancel</button>
    </div>    
</div>
</section>

@include('partials.footer')
