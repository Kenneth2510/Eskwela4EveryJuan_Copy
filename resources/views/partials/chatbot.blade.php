<section class="relative hidden h-screen text-sm md:w-1/3 lg:w-3/12 lg:block" id="AIHelper">
    
    <div class="flex flex-col justify-between hidden h-full p-3 overflow-hidden rounded-lg shadow-lg mainchatbotarea">
      {{-- head --}}
      <div>
          <div class="relative py-3 text-center border-b-2 border-gray-300">
              {{-- <i class="absolute top-0 right-0 px-3 fa-solid fa-xmark" id="AIClose"></i> --}}
              <h1 class="text-2xl font-bold">Eskwela Bot</h1>
          </div>        
      </div>
  
      {{-- body --}}
      <div class="h-full overflow-y-scroll">
          <div class="flex flex-col chatContainer">
              
              {{-- chat area --}}
  
              
          </div>      
      </div>
  
      {{-- foot --}}
      <div class="py-3 border-t-2 border-gray-300">
        
        <p class="bottom-0 hidden text-lg text-gray-700 botloader">the bot is typing...</p>
          <div class="flex items-center justify-between">
              <textarea type="text" placeholder="Type here" class="w-4/5 max-w-xs question_input input input-bordered input-primary"></textarea>
              <button class="w-1/5 mx-1 submitQuestion btn btn-primary"><i class="rotate-90 fa-solid fa-arrow-turn-down"></i></button>
          </div>  
      </div>
  
    </div>

    <div style="height: 80%;" class="absolute inset-0 flex items-center justify-center w-full z-100 loaderArea">
        <div class="chatbotloader"></div><br>
        <p class="mt-3 text-darthmouthgreen">preparing your bot</p>
    </div>
  </section>
  
  <script>
      $(document).ready(function() {
          $('.AIHelper').on('click', (e)=> {
              e.preventDefault();
              $('.sideProfile').toggleClass('md:block')
              $('#AIHelper').toggleClass('hidden')
          })
  
          $('#AIClose').on('click', ()=> {
              $('#AIHelper').toggleClass('hidden')
              $('.sideProfile').toggleClass('md:block')
          })
      })
  </script>