@extends('layouts.admin_layout')

@section('content')

<section class="w-full h-auto text-black md:h-screen lg:w-10/12">
    <div class="h-full px-2 py-4 pt-12 rounded-lg shadow-lg md:overflow-auto md:pt-0">
        <div class="flex items-center justify-between p-3 border-b-2 border-gray-300 md:py-8">
            <h1 class="text-2xl font-bold text-darthmouthgreen md:text-3xl lg:text-4xl">Course Management</h1>
            <div class="">
                <p class="font-semibold text-darthmouthgreen md:text-lg">{{$admin->admin_codename}}</p>
            </div>
        </div>

        <div class="w-full py-4 rounded-lg shadow-lg">

                <div class="flex flex-col items-center space-y-2 lg:space-y-0 lg:flex-row">
                    {{-- @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' ) --}}
                    @if(in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR']))
                    <a href="{{ url('/admin/add_course') }}" class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700">Add New</a>
                    @endif
                    <form action="{{ url('/admin/courses') }}" method="GET" class="flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0">
                        <div class="flex items-center space-x-2">
                            <label for="filterDate" class="text-lg sr-only">Filter by Date</label>
                            <input type="date" name="filterDate" class="w-1/3 p-2 text-base border border-black rounded-xl">
                
                            <label for="filterStatus" class="text-lg sr-only">Filter by Status</label>
                            <select name="filterStatus" id="filterStatus" class="w-1/3 p-2 text-base border border-black rounded-xl">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                
                            <button class="w-1/3 p-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700" type="submit">Filter</button>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <select name="searchBy" class="w-1/3 p-2 text-base border border-black rounded-xl">
                                <option value="">Search By</option>
                                <option value="course_id">Course ID</option>
                                <option value="course_name">Course Name</option>
                                <option value="course_code">Course Code</option>
                                <option value="instructor">Instructor</option>
                            </select>
            
                            <input type="text" name="searchVal" class="w-1/3 p-2 text-base border border-black rounded-xl" placeholder="Type to search">
            
                            <button class="w-1/3 p-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700" type="submit">Search</button>
                        </div>
                </div>

                <div id="contenttable" class="overflow-auto mt-7">
                <table class="table w-full table-fixed">
                    <thead class="border-b-2 border-black">
                        <th class="text-base w-[150px]">Course ID</th>
                        <th class="text-base w-[150px]">Course Code</th>
                        <th class="text-base w-[150px]">Course Name</th>
                        <th class="text-base w-[150px]">Course Instructor</th>
                        <th class="text-base w-[150px]">Date Registered</th>
                        <th class="text-base w-[150px]">Status</th>
                        <th class="w-[150px]"></th>
                    </thead>
                    <tbody class="">
                        @forelse ($courses as $course)
                        <tr class="">
                            <td>{{ $course->course_id }}</td>
                            <td>{{ $course->course_code }}</td>
                            <td class="py-1 text-lg font-normal">{{ $course->course_name }}</td>
                            <td class="py-1 text-lg font-normal">{{ $course->instructor_lname }}  {{ $course->instructor_fname }}</td>
                            <td class="py-1 text-lg font-normal">{{ $course->created_at }}</td>
                            <td class="py-1 text-lg font-normal">{{$course->course_status}}</td>
                            <td class="py-1">
                                
                                @if(in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR']))
                                <a href="/admin/view_course/{{$course->course_id}}" class="px-3 py-2 mx-3 text-lg font-medium bg-green-600 rounded-xl hover:bg-green-900 hover:text-white">view</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-1 text-lg font-normal" colspan="7">No courses found.</td>
                        </tr>
                        @endforelse            
                        
                    </tbody>
                </table>
                <div class="">{{$courses->links()}}</div>
                </div>
        </div>
    </div>
</section>
@endsection
