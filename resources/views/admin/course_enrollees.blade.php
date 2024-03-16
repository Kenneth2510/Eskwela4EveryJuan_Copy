@extends('layouts.admin_layout')

@section('content')
    <section id="view_learner_container" class="relative w-full h-screen px-4 overflow-auto pt-28 md:w-3/4 lg:w-10/12 md:pt-16">

        <div id="title" class="relative flex items-center justify-between px-3 mx-auto my-3 text-black">
            <h1 class="py-4 text-2xl font-semibold">View Learner Details</h1>
            <div id="adminuser" class="items-center hidden lg:flex">
                <h3 class="text-lg">{{ $adminCodeName }}</h3>
                <div id="icon" class="w-10 h-10 mx-3 rounded-full bg-slate-400"></div>
            </div>
        </div>

        <div id="maincontainer" class="relative w-full text-black shadow-lg rounded-2xl">
            <div>
                <a href="/admin/learners" class="">
                    <i class="text-xl fa-solid fa-arrow-left" style="color: #000000;"></i>
                </a>
            </div>


            <div class="flex flex-col w-full text-black lg:flex-row lg:text-white">
                <div id="courseSidebar" class="text-center lg:bg-seagreen lg:rounded-s-md">
                    <ul class="flex flex-row justify-around p-5 text-base font-medium lg:flex-col">
                        <a href="/admin/manage_course/course_overview/{{ $course->course_id }}">
                            <li id="courseOverviewBtn" class="w-full px-2 py-5 mt-2 rounded-xl hover:bg-green-900">
                                <i class="pr-2 text-3xl fa-solid fa-book-open"></i>
                                <p>Course Overview</p>    
                            </li>
                        </a>
                        <a href="/admin/manage_course/enrollees/{{ $course->course_id }}">
                            <li id="enrolledLearnersBtn" class="w-full px-2 py-5 mt-2 selected rounded-xl hover:bg-green-900">
                                <i class="pr-2 text-3xl fa-solid fa-users"></i>
                                <p>Enrolled Learners</p> 
                            </li>
                        </a>
                        <a href="/admin/manage_course/content/{{ $course->course_id }}">
                            <li id="courseContentBtn" class="w-full px-2 py-5 mt-2 rounded-xl hover:bg-green-900">
                                <i class="pr-2 text-3xl fa-solid fa-book"></i>
                                <p>Course Content</p>
                            </li>
                        </a>
                        
                        {{-- <li class="w-full px-2 py-3 mt-2 rounded-xl">
                        
                        </li>
                        <li class="w-full px-2 py-3 mt-2 rounded-xl">
                        
                        </li> --}}
                    </ul>
                </div>

                <div id="contentArea" class="relative w-full px-2 text-black shadow-lg rounded-2xl">

                    
                    <div id="enrolled_learners" class="">
                        <h1 class="text-2xl font-semibold border-b-2 border-black">Enrolled Learner</h1>

                        <form id="enrolleeForm" data-course-id="{{$course->course_id}}" action="/admin/manage_course/enrollees/{{$course->course_id}}" method="GET">
                            <div class="flex flex-col items-center w-full my-2 md:flex-row md:items-end lg:my-0">
                                <div class="flex flex-col w-full my-2 md:px-1 lg:flex-row lg:justify-center lg:my-0 lg:items-end">
                                    <div class="flex flex-row items-center justify-around w-full md:items-end lg:justify-center">
                                        <div class="w-2/4 mx-1">
                                            <label for="filterDate" class="">Filter by Date</label><br>
                                            <input type="date" name="filterDate" class="w-full p-2 text-sm border-2 border-black rounded" value="{{ request('filterDate') }}">
                                        </div>
                                        <div class="w-2/4 mx-1">
                                            <label for="filterStatus" class="">Filter by Status</label><br>
                                            <select name="filterStatus" id="filterStatus" class="w-full p-2 text-sm border-2 border-black rounded">
                                                <option value="" {{ request('filterDate') == '' ? 'selected': ''}}>Select Status</option>
                                                <option value="Pending" {{ request('filterStatus') == 'Pending' ? 'selected': ''}}>Pending</option>
                                                <option value="Approved" {{ request('filterStatus') == 'Approved' ? 'selected': ''}}>Approved</option>
                                                <option value="Rejected" {{ request('filterStatus') == 'Rejected' ? 'selected': ''}}>Rejected</option>
                                            </select>
                                        </div>    
                                    </div>
                                    
                                    <button class="py-4 my-2 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-900 lg:py-2 lg:w-32 lg:my-0" type="submit">Filter</button>
                                </div>
                                <div class="flex flex-col w-full my-2 md:px-1 lg:flex-row lg:justify-center lg:my-0 lg:items-end">
                                    <div class="flex flex-row items-center justify-around w-full md:items-end lg:items-center lg:justify-center">
                                        <div class="w-2/4 mx-1">
                                            <select name="searchBy" id="" class="w-full p-2 text-sm border-2 border-black rounded">
                                                <option value="" {{request('searchBy') == '' ? 'selected' : ''}}class="">Search By</option>
                                                <option value="learner_course_id" {{request('searchBy') == 'learner_course_id' ? 'selected' : ''}}>Enrollee ID</option>
                                                <option value="learner_id" {{request('searchBy') == 'learner_id' ? 'selected' : ''}}>Learner ID</option>
                                                <option value="name" {{request('searchBy') == 'name' ? 'selected' : ''}}>Name</option>
                                                <option value="learner_email" {{request('searchBy') == 'learner_email' ? 'selected' : ''}}>Email</option>
                                                <option value="learner_contactno" {{request('searchBy') == 'learner_contactno' ? 'selected' : ''}}>Contact No.</option>
                                                {{-- <option value="created_at">Date Registered</option> --}}
                                                {{-- <option value="status">Status</option> --}}
                                            </select>
                                        </div>
                                        <div class="w-2/4 mx-1">
                                            <input type="text" name="searchVal" class="w-full p-2 text-sm border-2 border-black rounded" value="{{ request('searchVal') }}" placeholder="Type to search">   
                                        </div>                                    
                                    </div>

                                    
                                    
                                    <button class="py-4 my-2 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-900 lg:py-2 lg:w-32 lg:my-0" type="submit">Search</button>          
                                </div>
                            </div>
                        </form>

                        <div id="learner_table" class="mt-5">
                            <table>
                                <thead class="text-left">
                                    <th class="w-1/5">Enrollee ID</th>
                                    <th class="w-1/5">Learner ID</th>
                                    <th class="w-1/5">Enrollee Info</th>
                                    <th class="w-1/5">Date</th>
                                    <th class="w-1/5">Status</th>
                                    <th class="w-1/5"></th>
                                </thead>
                                <tbody>
                                    @forelse ($enrollees as $enrollee)
                                    <tr>
                                        <td>{{$enrollee->learner_course_id}}</td>
                                        <td>{{$enrollee->learner_id}}</td>
                                        <td>
                                            <h1>{{$enrollee->learner_fname}} {{$enrollee->learner_lname}} </h1>
                                            <p>{{$enrollee->learner_email}}</p>
                                        </td>
                                        <td>{{$enrollee->created_at}}</td>
                                        <td>{{$enrollee->status}}</td>
                                        <td class="flex">
                                            {{-- <button class="px-5 py-2 bg-green-500 rounded-2xl hover:bg-green-700">
                                                view
                                            </button> --}}
                                            @if ($enrollee->status == 'Pending')
                                            <form action="/admin/manage_course/enrollee/approve/{{$enrollee->learner_course_id}}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <button class="px-3 py-1 mx-2 bg-green-500 rounded-xl hover:bg-green-700 hover:text-white">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="/admin/manage_course/enrollee/reject/{{$enrollee->learner_course_id}}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <button class="px-3 py-1 mx-2 bg-red-500 rounded-xl hover:bg-red-700 hover:text-white">
                                                    Reject
                                                </button>
                                            </form>
                                            
                                            @elseif ($enrollee->status == 'Rejected')
                                            <form action="/admin/manage_course/enrollee/pending/{{$enrollee->learner_course_id}}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <button class="px-3 py-1 mx-2 bg-yellow-500 rounded-xl hover:bg-yellow-700 hover:text-white">
                                                    Pending
                                                </button>
                                            </form>
                                            @else
                                            <form action="/admin/manage_course/enrollee/pending/{{$enrollee->learner_course_id}}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <button class="px-3 py-1 mx-2 bg-yellow-500 rounded-xl hover:bg-yellow-700 hover:text-white">
                                                    Pending
                                                </button>
                                            </form>
                                            <form action="/admin/manage_course/enrollee/reject/{{$enrollee->learner_course_id}}" method="POST">
                                                <button class="px-3 py-1 mx-2 bg-red-500 rounded-xl hover:bg-red-700 hover:text-white">
                                                    Reject
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="py-1 text-lg font-normal" colspan="7">No enrollees found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>    
@endsection
