@extends('layouts.learner_layout')

@section('content')
        {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-auto md:pt-0">
            <div class="py-4">
                <h1 class="text-2xl font-semibold md:text-3xl">DISCUSSION FORUMS</h1>                
            </div>

            <div class="w-full lg:px-24" id="mainContainer">
                <div class="w-full py-4 border-t-2 border-gray-300" id="createThread">
                    <a href="/learner/discussions/create">
                        <div class="flex items-center w-full p-5 border-2 border-opacity-75 rounded-lg border-darthmouthgreen" id="createThreadBtnContent">
                            <div class="rounded-full w-[50px] h-[50px]">
                                <img class="rounded-full w-[50px] h-[50px]" src="{{ asset('storage/' . $learner->profile_picture) }}" alt="">
                            </div>
                            <div class="mx-3 rounded-xl w-5/6 border-2 border-darthmouthgreen border-opacity-60 h-[50px]"></div>
                            <div class="px-5 py-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">POST</div>
                        </div>
                    </a>
                </div>

                <div class="py-4 border-t-2 border-gray-300" id="threadMainContainer">

                    {{-- <div class="flex w-full my-5 border-2 border-opacity-75 rounded-lg border-darthmouthgreen" id="thread">
                        <div class="w-1/12 border-r-2 border-opacity-50 border-darthmouthgreen" id="upvoteArea">
                            <div class="flex flex-col items-center mt-5">
                                <button class="my-3" id="upvoteBtn">
                                    <i class="text-4xl text-darthmouthgreen fa-regular fa-circle-up"></i>
                                </button>
                                <span class="text-darthmouthgreen " id="upvote">#</span>
                                <button class="my-3" id="downvoteBtn">
                                    <i class="text-4xl text-darthmouthgreen fa-regular fa-circle-down"></i>
                                </button>
                            </div>
                            
                        </div>
                        <div class="w-11/12 py-3 mx-5" id="threadMainContentArea">
                            <div class="flex items-center w-full" id="userInfoArea">
                                <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3"></div>
                                <h1 class="ml-1 text-lg font-semibold">user name</h1>
                                <h1 class="mx-5 text-lg font-normal">course name</h1>
                                <h1 class="font-normal text-md opacity-60">datetime</h1>
                            </div>
    
                            <a href="/instructor/discussion/view/">
                                <div class="w-full mx-5 mt-5" id="threadTitleArea">
                                    <h1 class="text-4xl font-bold">Title</h1>
                                </div>
        
                                <div class="w-full h-[150px] mx-5 mt-5" id="threadContentArea">
                                    <div class="" id="threadContent">
                                        <h1>test</h1>
                                    </div>
                                </div>
        
                                <div class="w-full mx-5 mt-5" id="commentsArea">
                                    <i class="fa-regular fa-comment text-darthmouthgreen"></i>
                                    <span class="">#</span>
                                    <span class="">comments</span>
                                </div>
                            </a>

                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

@include('partials.chatbot')
@endsection
