@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Admin Management</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">

        <div class="flex items-center justify-end space-x-3">
            @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN')
            <a href="/admin/admins/add_admin" class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700">Add New</a>
            @endif
            <form action="{{ url('/admin/admins') }}" method="GET" class="flex items-center space-x-3">

                <div class="flex items-center space-x-3">
                    <select name="searchBy" class="px-2 py-2 text-base border border-black w-60 rounded-xl">
                        <option value="" >Search By</option>
                        <option value="admin_id">Admin ID</option>
                        <option value="admin_username">Admin Username</option>
                        <option value="admin_codename">Codename</option>
                        <option value="role">Role</option>
                    </select>
                    
                    <input type="text" name="searchVal" class="px-2 py-2 text-base border border-black w-72 rounded-xl" placeholder="Type to search">
                    
                    <button class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700" type="submit">Search</button>
                </div>
            </form>
            
       
        </div>

        <div id="contenttable" class="mt-7">
            <table class="w-full text-center border-b border-black">
                <thead>
                    <th class="w-1/12 text-lg">Admin ID</th>
                    <th class="w-2/12 text-lg">Admin Username</th>
                    <th class="w-3/12 text-lg">Codename</th>
                    <th class="w-3/12 text-lg">Role</th>
                    <th class="w-1/12"></th>
                </thead>
                <tbody id="AD_learners" class="">
                    @forelse ($adminData as $admin)
                        <tr>
                            <td class="py-4">{{ $admin->admin_id }}</td>
                            <td>{{ $admin->admin_username }}</td>
                            <td class="py-1 text-base">{{ $admin->admin_codename }}</td>
                            <td class="py-1 text-base">{{ $admin->role }}</td>
                            <td>
                                @if($admin->role !== 'IT_DEPT' || $admin->role !== 'SUPER_ADMIN')
                                <a href="/admin/view_admin/{{ $admin->admin_id }}" class="px-4 py-2 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700">View</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-1 text-base" colspan="7">No admin found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $adminData->links() }}</div>
        </div>
    </div>
</section>

    
</section>

@include('partials.footer')