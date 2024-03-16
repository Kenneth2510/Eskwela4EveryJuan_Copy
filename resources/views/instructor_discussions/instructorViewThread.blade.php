@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-0">
            <div class="py-4 border-b-2 border-gray-300">
                <h1 class="text-2xl font-semibold md:text-3xl">DISCUSSION FORUMS</h1>
            </div>

            <div class="w-full" id="mainContainer">
            
                <div data-thread-id="{{ $thread->thread_id }}" class="flex w-full my-4 bg-white border-2 border-opacity-75 rounded-lg border-darthmouthgreen thread" id="thread_{{ $thread->thread_id }}">
                    <div class="w-2/12 border-r-2 border-opacity-50 md:w-1/12 border-darthmouthgreen" id="upvoteArea">
                        <div class="flex flex-col items-center mt-5">
                            <button class="my-3 upvote_button">
                                <i class="text-4xl text-darthmouthgreen fa-regular fa-circle-up"></i>
                            </button>
                            <span class="text-darthmouthgreen upvote_count" id="">{{ $thread->randomized_display_upvote }}</span>
                            <button class="my-3 downvote_button">
                                <i class="text-4xl text-darthmouthgreen fa-regular fa-circle-down"></i>
                            </button>
                        </div>
                    </div>
                    <div class="w-10/12 py-3 mx-5 md:w-11/12" id="threadMainContentArea">
                        <div class="flex items-center w-full" id="userInfoArea">
                            <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3">
                                <img class="rounded-full w-[35px] h-[35px]" src="{{ asset('storage/' . $thread->profile_picture) }}" alt="">
                            </div>
                            <h1 class="ml-1 text-lg font-semibold">{{ $thread->first_name }} {{ $thread->last_name }}</h1>
                            <h1 class="mx-5 text-lg font-normal">{{ $thread->community_name }}</h1>
                            <h1 class="font-normal text-md opacity-60">{{ $thread->created_at }}</h1>
                        </div>
    

                            <div class="w-full mx-5 mt-5" id="threadTitleArea">
                                <h1 class="text-4xl font-bold">{{ $thread->thread_title }}</h1>
                            </div>
    
                            <div class="w-full px-3 mx-5 mt-5" id="threadContentArea">

                                @if ($thread->thread_type === 'POST')
                                <div class="h-[150px]" id="threadContent">
                                    {!! $thread->thread_content !!}
                                </div>
        
                                @elseif ($thread->thread_type === 'PHOTO')
                                <div class="h-[350px] flex justify-center" id="threadContent">
                                    <img class="h-[350px]" src="{{ asset('storage/' . $thread->thread_content) }}" alt="">
                                </div>
                                @else 
                                <div class="h-[150px]" id="threadContent">
                                    <a href="{{ $thread->thread_content }}">{{ $thread->thread_content }}</a>
                                </div>
                                @endif

                            </div>
    
                            <div class="w-full mx-5 mt-5" id="commentsArea">
                                <i class="fa-regular fa-comment text-darthmouthgreen"></i>
                                <span class="">{{ $thread->total_count }}</span>
                                <span class="">comments</span>
                            </div>

    
                            <div class="w-full mx-5 mt-5" id="commentInputArea">
                                <label for="commentInput" class="text-lg">Your Comment:</label>
                                <textarea name="" class="w-11/12 h-[250px] p-3 rounded-lg" id="commentInput" placeholder="comment"></textarea>
                                <div class="w-11/12 text-right">
                                    <button class="px-5 py-3 mt-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl" id="submitCommentBtn">Submit</button>
                                </div>
                            </div>

                            <div class="" id="commentArea">
                                <h1 class="text-xl">Comments</h1>
                                <div class="mt-5" id="sortByArea">
                                    <select name="" class="px-5 py-3 border-2 rounded-xl border-darthmouthgreen" id="sortComments">
                                        <option value="NEW">Most Recent</option>
                                        <option value="TOP" selected>Most Upvoted</option>
                                        <option value="OLD">Oldest</option>
                                    </select>
                                </div>
                                <hr class="mt-2 mb-4 border-t-2 border-gray-300">

                                {{-- comments --}}
                                <div class="" id="commentMainContainer">
    
                                    {{-- <div class="my-10 commentContainer">
                                        <div class="flex items-center w-full" id="userInfoArea">
                                            <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3"></div>
                                            <h1 class="ml-1 text-lg font-semibold">user name</h1>
                                            <h1 class="mx-5 font-normal text-md opacity-60">datetime</h1>
                                        </div>
                                        <div class="w-full px-10 border-l-2 mx-7 border-darthmouthgreen border-opacity-60" id="commentContentArea">
                                            <div class="" id="comment">sample comment</div>
                                            <div class="flex items-center mt-5" id="commentUpvoteArea">
                                                <div class="flex items-center">
                                                    <button class="mr-3 upvote_button">
                                                        <i class="text-2xl text-darthmouthgreen fa-regular fa-circle-up"></i>
                                                    </button>
                                                    <span class="text-darthmouthgreen upvote_count" id="">{{ $thread->randomized_display_upvote }}</span>
                                                    <button class="ml-3 downvote_button">
                                                        <i class="text-2xl text-darthmouthgreen fa-regular fa-circle-down"></i>
                                                    </button>
                                                </div>
                                                <div class="flex items-center mx-10" id="replyCount">
                                                    <i class="text-2xl fa-regular fa-comment text-darthmouthgreen"></i>
                                                    <span class="mx-3 text-darthmouthgreen">#</span>
                                                    <button class="px-3 py-1 text-white rounded-lg bg-darthmouthgreen hover:bg-green-950">reply</button>
                                                </div>
                                            </div>
    
                                            <div class="mt-3" id="replyArea">
                                                <!-- replies -->
                                                <div class="" id="replyContainer">
                                                    <div class="flex items-center w-full" id="userInfoArea">
                                                        <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3"></div>
                                                        <h1 class="ml-1 text-lg font-semibold">user name</h1>
                                                        <h1 class="mx-5 font-normal text-md opacity-60">datetime</h1>
                                                    </div>
                                                    <div class="w-full px-10 border-l-2 mx-7 border-darthmouthgreen border-opacity-60" id="commentContentArea">
                                                        <div class="" id="comment">sample comment</div>
                                                        <div class="flex items-center mt-5" id="commentUpvoteArea">
                                                            <div class="flex items-center">
                                                                <button class="mr-3 upvote_button">
                                                                    <i class="text-2xl text-darthmouthgreen fa-regular fa-circle-up"></i>
                                                                </button>
                                                                <span class="text-darthmouthgreen upvote_count" id="">{{ $thread->randomized_display_upvote }}</span>
                                                                <button class="ml-3 downvote_button">
                                                                    <i class="text-2xl text-darthmouthgreen fa-regular fa-circle-down"></i>
                                                                </button>
                                                            </div>
                                                            <div class="flex items-center mx-10" id="replyCount">
                                                                <i class="text-2xl fa-regular fa-comment text-darthmouthgreen"></i>
                                                                <span class="mx-3 text-darthmouthgreen">#</span>
                                                                <button class="px-3 py-1 text-white rounded-lg bg-darthmouthgreen hover:bg-green-950">reply</button>
                                                            </div>
                                                        </div>
                
                                                        <div class="mt-5" id="replyReplyArea">
                                                            <!-- reply to replies -->
                                                            <div class="" id="replyReplyContainer">
                                                                <div class="flex items-center w-full" id="userInfoArea">
                                                                    <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3"></div>
                                                                    <h1 class="ml-1 text-lg font-semibold">user name</h1>
                                                                    <h1 class="mx-5 font-normal text-md opacity-60">datetime</h1>
                                                                </div>
                                                                <div class="w-full px-10 border-l-2 mx-7 border-darthmouthgreen border-opacity-60" id="commentContentArea">
                                                                    <div class="" id="comment">sample comment</div>
                                                                    <div class="flex items-center mt-5" id="commentUpvoteArea">
                                                                        <div class="flex items-center">
                                                                            <button class="mr-3 upvote_button">
                                                                                <i class="text-2xl text-darthmouthgreen fa-regular fa-circle-up"></i>
                                                                            </button>
                                                                            <span class="text-darthmouthgreen upvote_count" id="">{{ $thread->randomized_display_upvote }}</span>
                                                                            <button class="ml-3 downvote_button">
                                                                                <i class="text-2xl text-darthmouthgreen fa-regular fa-circle-down"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="w-full mt-5" id="replyReplyInputArea">
                                                            <label for="replyReplyInput" class="text-lg">Your Reply:</label>
                                                            <textarea name="" class="w-full h-[100px] p-3 rounded-lg" id="replyReplyInput" placeholder="reply"></textarea>
                                                            <div class="text-right">
                                                                <button class="px-5 py-3 mt-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl" id="replyReplyInputBtn">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    
                                            </div>
                                            <div class="w-full mt-5" id="commentReplyInputArea">
                                                <label for="commentReplyInput" class="text-lg">Your Reply:</label>
                                                <textarea name="" class="w-full h-[100px] p-3 rounded-lg" id="commentReplyInput" placeholder="comment"></textarea>
                                                <div class="text-right">
                                                    <button class="px-5 py-3 mt-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl" id="commentReplyInputBtn">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  {{--  @include('partials.chatbot') --}}

    


<div id="loaderModal" class="fixed top-0 left-0 z-[99] flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
    <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
        <span class="loading loading-spinner text-primary loading-lg"></span> 
        <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
    </div>
</div>


    <div id="successModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
        <div class="modal-content flex flex-col justify-center items-center p-20 bg-white p-4 rounded-lg shadow-lg w-[500px]">
            <i class="fa-regular fa-circle-check text-[75px] text-darthmouthgreen"></i>
            <p class="mt-5 text-xl text-darthmouthgreen">Successful</p>  
        </div>
    </div>

    <div id="errorModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
        <div class="modal-content flex flex-col justify-center items-center p-20 bg-white p-4 rounded-lg shadow-lg w-[500px]">
            <i class="fa-regular fa-circle-xmark text-[75px] text-red-500"></i>
            <p class="mt-5 text-xl text-darthmouthgreen">Error</p>  
        </div>
    </div>
@endsection
