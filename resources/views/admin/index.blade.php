@extends('layouts.admin_login')

@section('content')
    <section class="w-full h-screen bg-cover" style="background-image: url('{{ asset('assets/CityHall.jpg') }}')">
        <section class="h-full bg-white bg-opacity-70">
            <div id="adminlogin" class="flex items-center justify-center h-screen transition-opacity duration-100">
                <div class="w-full h-screen p-3 space-y-6 bg-white border-gray-200 shadow-xl md:w-4/6 lg:w-4/12 bg-opacity-70 rounded-xl md:h-auto">
                    <div class="text-center " id="logoArea">
                        <h1 class="text-xl font-bold text-darthmouthgreen">Eskwela4EveryJuan</h1>
                    </div>
                    <div class="text-center " id="welcomeArea">
                        <p class="text-5xl font-bold text-darthmouthgreen">Welcome Back</p>
                        <p class="text-gray-600 text-md">Welcome Back!  Please login to your account.</p>
                    </div>
                    <div class="w-full py-12 text-black md:py-0" id="formArea">
                        <form action="/admin/login" method="POST">
                            @csrf
                            
                            <div class="">
                                <div class="w-full my-3">
                                    <label for="admin_username" class="font-semibold text-darthmouthgreen">Username:</label>
                                    <input type="text" name="admin_username" class="w-full px-5 py-3 border rounded-md border-darthmouthgreen" placeholder="Username" value="{{ old('admin_username') }}" required>
                                </div>
                                <div class="w-full my-3">
                                    <label for="password" class="font-semibold text-darthmouthgreen">Password:</label>
                                    <div class="relative items-center">
                                        <input type="password" class="w-full px-5 py-3 border rounded-md border-darthmouthgreen" name="password" id="password" placeholder="Password" required>
                                        <button type="button" id="showPasswordBtn" class="absolute top-0 translate-y-1/2 rounded-md right-4">
                                            <i id="eyeIcon" class="fa-regular fa-eye" style="color: #025c26;"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('admin_username')
                                <p class="p-1 mt-2 text-lg text-red-500">
                                    {{$message}}
                                </p>
                                @enderror
                                <div class="flex justify-between mt-10 ">
                                    <p></p>
                                    <button type="submit" class="w-full py-3 text-xl font-semibold text-white rounded-lg bg-seagreen hover:bg-green-900">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section>    
    <script>
        $(document).ready(function() {
            $('#showPasswordBtn').click(function() {
                var passwordField = $('#password');
                var fieldType = passwordField.attr('type');
                if (fieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $('#eyeIcon').removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    $('#eyeIcon').removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endsection




