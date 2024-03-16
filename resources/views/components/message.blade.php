@if(session()->has('message'))

<div x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-[100px] right-0 z-20 px-4 py-6 my-2 text-white bg-[#00693e] border-t-4 border-[#004c2d] rounded-b shadow-md" role="alert">
    <div class="flex">
      <div class="py-1"><svg class="w-6 h-6 mb-5 mr-4 text-white fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
      <div>
        <p class="text-lg font-bold">Alert Message</p>
        <p class="text-base">{{session('message')}}</p>
      </div>
    </div>
</div>


@endif

{{-- <script>
    // JavaScript to handle JSON response message
    var jsonResponse = @json(session('message'));

    if (jsonResponse) {
        // Display the message and handle redirection
        var alertElement = document.querySelector(".alert-message");
        if (alertElement) {
            alertElement.style.display = "block";
            setTimeout(function() {
                alertElement.style.display = "none";
            }, 5000);
        }
    }
</script> --}}
