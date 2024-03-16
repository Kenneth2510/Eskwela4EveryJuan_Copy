<section class="hidden w-auto h-screen md:block lg:w-auto sideProfile">
    <div class="h-full px-2 py-4">
        <div class="flex flex-col space-y-4">
            <button class="learner_profile">
                <img class="w-12 h-12 rounded-full lg:w-16 lg:h-16 bg-primary" src="" alt="">
            </button>
            <button class="learner_AIHelper">
                <img class="w-12 h-12 rounded-full lg:w-16 lg:h-16 bg-secondary" src="" alt="">
            </button>
            <button class="">
                <img class="w-12 h-12 rounded-full lg:w-16 lg:h-16 bg-accent" src="" alt="">
            </button>
        </div>
    </div>
</section>

<section class="fixed z-50 hidden w-full h-screen bg-white bg-opacity-30" id="sm-sidebar">
    <div class="float-right w-3/4 h-screen py-4 bg-white">
        <div class="relative p-3 text-center">
            <button class="absolute top-0 right-0 px-3" id="close-sideprofile">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h1 class="text-xl font-bold">Side Profie</h1>
        </div>

        {{-- contents --}}
        <div class="w-full py-4">
            <ul class="flex flex-col justify-center divide-y-2">
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white" id="learner_profile"><a href="">Profile Setting</a></li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white" id="learner_chatbot"><a href="">Chatbot Helper</a></li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white" id="learner_sidebar1"><a href="">Sidebar 1</a></li>
            </ul>

            <form class="mx-4 mt-10 rounded-lg bg-darthmouthgreen md:block group hover:bg-white hover:border-2 hover:border-darthmouthgreen" action="{{ url('/instructor/logout') }}" method="POST"> 
                @csrf
                <button type="submit" class="flex flex-row items-center justify-center w-full h-12 group-hover:cursor-pointer" >
                    <svg class="fill-white group-hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <h1 class="px-5 text-white group-hover:text-black">Logout</h1>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#close-sideprofile').on('click', (e)=> {
            e.preventDefault();
            $('#sm-sidebar').toggleClass('hidden')
        })
    })
</script>