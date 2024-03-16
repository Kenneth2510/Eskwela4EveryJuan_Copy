@extends('layouts.admin_layout')

@section('content')
    <section class="w-full h-auto text-black md:h-screen lg:w-10/12">
        <div class="h-full px-2 py-4 pt-12 rounded-lg shadow-lg md:overflow-auto md:pt-0">
            <div class="flex items-center justify-between p-3 border-b-2 border-gray-300 md:py-8">
                <h1 class="text-2xl font-bold text-darthmouthgreen md:text-3xl lg:text-4xl">Learner Management</h1>
                <div class="">
                    <p class="font-semibold text-darthmouthgreen md:text-lg">{{$admin->admin_codename}}</p>
                </div>
            </div>

            <div class="w-full py-4 rounded-lg shadow-lg">
                
                <div class="flex flex-col items-center space-y-2 lg:space-y-0 lg:flex-row">
                    @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER')
                    <a href="/admin/add_learner" class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700">Add New</a>
                    @endif
                    <form action="{{ url('/admin/learners') }}" method="GET" class="flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0">
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
                                <option value="learner_id">Learner ID</option>
                                <option value="name">Name</option>
                                <option value="learner_email">Email</option>
                                <option value="learner_contactno">Contact No.</option>
                                <option value="business_name">Business Name</option>
                            </select>
                            
                            <input type="text" name="searchVal" class="w-1/3 p-2 text-base border border-black rounded-xl" placeholder="Type to search">
                            
                            <button class="w-1/3 p-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <div id="contenttable" class="overflow-auto mt-7">
                    <table class="table w-full table-fixed">
                        <thead class="border-b-2 border-black">
                            <th class="w-[150px] text-base">Learner ID</th>
                            <th class="w-[150px] text-base">Name</th>
                            <th class="w-[150px] text-base">Contact Info</th>
                            <th class="w-[150px] text-base">Business Name</th>
                            <th class="w-[150px] text-base">Date Registered</th>
                            <th class="w-[150px] text-base">Status</th>
                            <th class="w-[150px] text-base"></th>
                        </thead>
                        <tbody id="AD_learners" class="">
                            @forelse ($learners as $learner)
                                <tr>
                                    <td>{{ $learner->learner_id }}</td>
                                    <td>{{ $learner->learner_fname }} {{ $learner->learner_lname }}</td>
                                    <td class="py-1">{{ $learner->learner_email }}<br>{{ $learner->learner_contactno }}</td>
                                    <td class="py-1">{{ $learner->business_name }}</td>
                                    <td class="py-1">{{ $learner->created_at }}</td>
                                    <td class="py-1">{{ $learner->status }}</td>
                                    <td>
                                        @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER')
                                        <a href="/admin/view_learner/{{ $learner->learner_id }}" class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-1" colspan="7">No learners found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $learners->links() }}</div>
                </div>
            </div>
        </div>
    </section>    
@endsection
