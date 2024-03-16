@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Performance Overview</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="mt-10">
        <div class="mb-5">
            <a href="/admin/performance" class="">
                <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
            </a>
        </div>
        <h1 class="mx-5 text-2xl font-semibold">Instructor Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>

    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">
      
        <div class="flex items-center justify-end space-x-3">
            <form action="{{ url('/admin/instructors') }}" method="GET" class="flex items-center space-x-3">
                <label for="filterDate" class="text-lg">Filter by Date</label>
                <input type="date" name="filterDate" class="w-40 px-2 py-2 text-base border border-black rounded-xl">
    
                <label for="filterStatus" class="text-lg">Filter by Status</label>
                <select name="filterStatus" id="filterStatus" class="w-32 px-2 py-2 text-base border border-black rounded-xl">
                    <option value="">Select Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
    
                <button class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700" type="submit">Filter</button>
    
                <div class="flex items-center space-x-3">
                    <select name="searchBy" class="w-32 px-2 py-2 text-base border border-black rounded-xl">
                        <option value="">Search By</option>
                        <option value="instructor_id">Instructor ID</option>
                        <option value="name">Name</option>
                        <option value="instructor_email">Email</option>
                        <option value="instructor_contactno">Contact No.</option>
                    </select>
    
                    <input type="text" name="searchVal" class="w-32 px-2 py-2 text-base border border-black rounded-xl" placeholder="Type to search">
    
                    <button class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700" type="submit">Search</button>
                </div>
            </form>
        </div>

    

        <div id="AD002_I_contenttable" class="mt-7">
            <table class="">
                <thead class="border-b-2 border-black">
                    <th class="w-2/12 text-xl text-left">Instructor ID</th>
                    <th class="w-3/12 text-xl text-left">Name</th>
                    <th class="w-3/12 text-xl text-left">Contact Info</th>
                    <th class="w-2/12 text-xl text-left">Date Registered</th>
                    <th class="w-1/12 text-xl text-left">Status</th>
                    <th class="w-1/12"></th>
                </thead>
                <tbody class="">
                    @forelse ($instructors as $instructor)
                    <tr class="">
                        <td class="w-2/12 py-1 text-lg font-normal">{{ $instructor->instructor_id }}</td>
                        <td class="w-3/12 py-1 text-lg font-normal">{{ $instructor->instructor_fname }} {{ $instructor->instructor_lname }}</td>
                        <td class="w-3/12 py-1 text-lg font-normal">{{ $instructor->instructor_email }}<br>{{$instructor->instructor_contactno}}</td>
                        <td class="w-1/12 py-1 text-lg font-normal">{{ $instructor->created_at }}</td>
                        <td class="w-2/12 py-1 text-lg font-normal">{{ $instructor->status }}</td>
                        <td class="w-1/12">
                            @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER')
                            <a href="/admin/performance/instructor/view/{{$instructor->instructor_id}}" class="px-3 py-2 mx-3 text-lg font-medium bg-green-600 rounded-xl hover:bg-green-900 hover:text-white">view</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-1 text-lg font-normal" colspan="7">No learners found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="">{{$instructors->links()}}</div>
        </div>
</div>
    
</section>
</section>

@include('partials.footer')