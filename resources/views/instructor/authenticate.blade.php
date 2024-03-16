@include('partials.header')

{{-- SECURITY CODE --}}
<section class="items-center justify-center w-full h-screen md:bg-darthmouthgreen md:flex">

    <div class="w-full p-2 py-8 mt-16 rounded-lg md:bg-mainwhitebg text-darthmouthgreen md:w-3/4 md:shadow-lg lg:w-1/4 md:h-3/4 lg:h-96" id="securityForm">
        
        <div class="relative flex justify-between text-xl font-semibold tracking-wide md:text-2xl">
            <form class="" action="{{ url('/instructor/logout') }}" method="POST"> 
                @csrf
                <button type="submit"><svg class="absolute cursor-pointer" id="backBtn" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></button>
            </form>

            <h1>Security Code</h1>
            <h1> </h1>

        </div>
        
        <form action="{{ url('/instructor/authenticate') }}" method="POST">
            @csrf
            <h1 class="text-lg font-medium text-center text-black">Enter Security Code</h1>
            <div class="flex flex-col items-center py-10">
                <div class="my-6">
                    <input class="mx-1 h-16 text-center shadow outline-none focus:ring-black focus:ring-[1px]" type="password" name="security_code_1" id="" maxlength="1" size="1" min="0" max="9" pattern="{0-9}{1}" autofocus>
                    <input class="h-16 mx-1 text-center shadow outline-none focus:ring-black focus:ring-[1px]" type="password" name="security_code_2" id="" maxlength="1" size="1" min="0" max="9" pattern="{0-9}{1}">
                    <input class="h-16 mx-1 text-center shadow outline-none focus:ring-black focus:ring-[1px]" type="password" name="security_code_3" id="" maxlength="1" size="1" min="0" max="9" pattern="{0-9}{1}">
                    <input class="h-16 mx-1 text-center shadow outline-none focus:ring-black focus:ring-[1px]" type="password" name="security_code_4" id="" maxlength="1" size="1" min="0" max="9" pattern="{0-9}{1}">
                    <input class="h-16 mx-1 text-center shadow outline-none focus:ring-black focus:ring-[1px]" type="password" name="security_code_5" id="" maxlength="1" size="1" min="0" max="9" pattern="{0-9}{1}">
                    <input class="h-16 mx-1 text-center shadow outline-none focus:ring-black focus:ring-[1px]" type="password" name="security_code_6" id="" maxlength="1" size="1" min="0" max="9" pattern="{0-9}{1}">
                </div>
                @error('security_code')
                            <p class="p-1 mt-2 text-xs text-red-500">
                                {{$message}}
                            </p>
                            @enderror
                <button type="submit" class="w-64 h-12 my-4 font-medium tracking-wide text-white rounded bg-seagreen hover:bg-darthmouthgreen focus:bg-darthmouthgreen">Verify</button>
            </div>
        </form>
        {{-- <div class="text-center text-black">
            <h1>We just sent you a verification code</>
            <p class="font-semibold text-darthmouthgreen">Resend Code?</p>
        </div> --}}
    </div>

</section>

@include('partials.footer')

    <script>
        // Add event listeners to the input fields
        const inputFields = document.querySelectorAll('input[type="password"]');
        inputFields.forEach((input, index) => {
            input.addEventListener('input', (event) => {
                if (event.target.value !== '' && index < inputFields.length - 1) {
                    inputFields[index + 1].focus();
                }
            });
        });
    </script>