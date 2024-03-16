@include('partials.header')


<section class="items-center justify-center w-screen h-screen bg-mainwhitebg md:bg-mainwhitebg md:flex">
    <div class="w-2/5 p-2 py-8 mt-16 rounded-lg shadow-lg md:bg-mainwhitebg text-darthmouthgreen" id="securityForm">
        
        <div class="w-full h-full m-auto">
            
            <div class="flex flex-col items-center justify-center h-full p-20">
                <h1 class="text-6xl font-bold text-darthmouthgreen">Change Password</h1>
                <p class="mt-5 text-xs text-darthmouthgreen md:text-base">
                    <span class="font-bold">Hello {{ $learner->learner_fname }} {{ $learner->learner_lname }}!</span> You're now on the page to change your password. Please enter a new password below to secure your account.
                    If you did not initiate this password change request, you can ignore this page.
                </p>
                <div class="w-full mt-16">
                    <form class="" action="{{ url("/learner/reset_password_process/$token->token") }}" method="POST"> 
                        @csrf
                    
                        <!-- New Password -->
                        <label for="password" class="sr-only">New Password</label>
                        <input type="password" id="password" name="password" class="w-full p-3 border-b-2 border-darthmouthgreen focus:outline-none focus:border-darthmouthgreen" placeholder="Enter your new password" required>
                        <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> Show Password
                    
                        <!-- Confirm New Password -->
                        <label for="password_confirmation" class="sr-only">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 border-b-2 border-darthmouthgreen focus:outline-none focus:border-darthmouthgreen" placeholder="Confirm your new password" required>
                    
                        <button class="px-5 py-3 mt-4 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen" type="submit">Change Password</button>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        }

        function toggleConfirmPasswordVisibility() {
            var confirmPasswordField = document.getElementById("password_confirmation");
            confirmPasswordField.type = confirmPasswordField.type === "password" ? "text" : "password";
        }
    </script>
</section>



@include('partials.footer')
