@include('partials.header')
    <section class="absolute w-full h-screen bg-bottom bg-no-repeat bg-cover bg-homeImg -z-10"></section>
    
    <nav class="w-full px-4 py-4 text-white">
        <a href="#">
            <span class="self-center font-bold font-semibbold whitespace-nowrap md:text-2xl">
                Eskwela4EveryJuan
            </span>
        </a>
    </nav>  
    
    <div class="max-w-lg mx-auto mt-8 md:mx-10 md:max-w-xl lg:max-w-2xl">
        <h1 class="text-3xl font-bold text-center text-darthmouthgreen md:leading-relaxed md:text-left md:text-5xl lg:text-6xl lg:leading-relaxed">
            Learn anything, anytime, anywhere
        </h1>
        <p class="mx-4 my-6 text-sm text-center md:text-left md:text-xl lg:text-2xl md:max-w-lg lg:max-w-2xl">
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iusto tempore aliquam aperiam iste et dolor, iure debitis! Adipisci ad libero eveniet molestias explicabo sunt eligendi. Autem similique suscipit amet neque.
        </p>
    </div>
    
    <section class="flex flex-col text-sm md:flex-row md:text-xl lg:text-2xl">
        <a href="{{ url('/learner') }}" class="IND-BTN IND-BTN-L">
            Sign in as Learner    
        </a>
        
        <a href="{{ url('/instructor') }}" class="IND-BTN IND-BTN-IN">
            Sign in as Instructor
        </a>
    </section>
    
@include('partials.footer')