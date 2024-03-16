<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\LearnerCourse;
use App\Models\LessonContents;
use App\Models\ActivityContents;
use App\Models\ActivityContentCriterias;
use App\Models\Syllabus;
use App\Models\Lessons;
use App\Models\Activities;
use App\Models\Quizzes;
use App\Models\LearnerCourseProgress;
use App\Models\LearnerSyllabusProgress;
use App\Models\LearnerLessonProgress;
use App\Models\LearnerActivityProgress;
use App\Models\LearnerQuizProgress;
use App\Models\LearnerActivityOutput;
use App\Models\LearnerActivityCriteriaScore;
use App\Models\QuizContents;
use App\Models\QuizReferences;
use App\Models\Questions;
use App\Models\QuestionAnswers;
use App\Models\LearnerQuizOutputs;
use App\Models\Thread;
use App\Models\ThreadContents;
use App\Models\ThreadUser;
use App\Models\ThreadUpvotes;
use App\Models\ThreadComments;
use App\Models\ThreadCommentUpvotes;
use App\Models\ThreadCommentReplies;
use App\Models\ThreadCommentReplyUpvotes;
use App\Models\ThreadReplyReplies;
use App\Models\ThreadReplyReplyUpvotes;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\URL;
use Dompdf\Dompdf;
use App\Http\Controllers\DateTime;
use Carbon\Carbon;


class InstructorDiscussionController extends Controller
{

    public function upvoteJuggling() {

        try {
            $now = Carbon::now();
            $timestampString = $now->toDateTimeString();

            $last_randomized_datetime_object = DB::table('thread_upvotes')
                ->select('last_randomized_datetime')
                ->first();

            if($last_randomized_datetime_object) {
                $last_randomized_datetime = $last_randomized_datetime_object->last_randomized_datetime;
    
                if (Carbon::parse($now)->diffInDays(Carbon::parse($last_randomized_datetime)) > 0) {
                    $threadUpvotesData = DB::table('thread_upvotes')
                    ->select(
                        'thread_upvote_id',
                        'thread_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime',
                    )
                    ->get();
                    
    
                    foreach ($threadUpvotesData as $threadUpvote) {
                        
                        $min = 100;
                        $max = 9999;
    
                        $randomNumber = rand($min, $max);
    
                        DB::table('thread_upvotes')
                        ->where('thread_upvote_id', $threadUpvote->thread_upvote_id)
                        ->update([
                            'randomized_display_upvote' => $randomNumber,
                            'last_randomized_datetime' => $timestampString
                        ]);
                    }

                    $threadCommentsUpvotesData = DB::table('thread_comment_upvotes')
                ->select(
                    'thread_comment_upvote_id',
                    'thread_id',
                    'thread_comment_id',
                    'base_upvote',
                    'randomized_display_upvote',
                    'last_randomized_datetime',
                )
                ->get();
                

                foreach ($threadCommentsUpvotesData as $threadCommentUpvote) {
                    
                    $min = 100;
                    $max = 9999;

                    $randomNumber = rand($min, $max);

                    DB::table('thread_comment_upvotes')
                    ->where('thread_comment_upvote_id', $threadCommentUpvote->thread_comment_upvote_id)
                    ->update([
                        'randomized_display_upvote' => $randomNumber,
                        'last_randomized_datetime' => $timestampString
                    ]);
                }


                $threadCommentReplyUpvotesData = DB::table('thread_comment_reply_upvotes')
                ->select(
                    'thread_comment_reply_upvote_id',
                    'thread_id',
                    'thread_comment_id',
                    'thread_comment_reply_id',
                    'base_upvote',
                    'randomized_display_upvote',
                    'last_randomized_datetime',
                )
                ->get();
                

                foreach ($threadCommentReplyUpvotesData as $threadCommentReplyUpvote) {
                    
                    $min = 100;
                    $max = 9999;

                    $randomNumber = rand($min, $max);

                    DB::table('thread_comment_reply_upvotes')
                    ->where('thread_comment_reply_upvote_id', $threadCommentReplyUpvote->thread_comment_reply_upvote_id)
                    ->update([
                        'randomized_display_upvote' => $randomNumber,
                        'last_randomized_datetime' => $timestampString
                    ]);
                }



                $threadReplyReplyUpvotesData = DB::table('thread_reply_reply_upvotes')
                ->select(
                    'thread_reply_reply_upvote_id',
                    'thread_id',
                    'thread_comment_id',
                    'thread_comment_reply_id',
                    'thread_reply_reply_id',
                    'base_upvote',
                    'randomized_display_upvote',
                    'last_randomized_datetime',
                )
                ->get();
                

                foreach ($threadReplyReplyUpvotesData as $threadReplyReplyUpvote) {
                    
                    $min = 100;
                    $max = 9999;

                    $randomNumber = rand($min, $max);

                    DB::table('thread_reply_reply_upvotes')
                    ->where('thread_reply_reply_upvote_id', $threadReplyReplyUpvote->thread_reply_reply_upvote_id)
                    ->update([
                        'randomized_display_upvote' => $randomNumber,
                        'last_randomized_datetime' => $timestampString
                    ]);
                }
            }
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function discussions() {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {

                $this->upvoteJuggling();
                
            $data = [
                'title' => 'Discussion',
                'scripts' => ['instructor_discussion.js'],

            ];

            // dd($data);
            return view('instructor_discussions.instructorDiscussion' , compact('instructor'))
            ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }

    }



    public function threadData()
    {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $threadsData = DB::table('thread')
                    ->select(
                        'thread.thread_id',
                        'thread.community_id',
                        'thread.user_id',
                        'thread.user_type',
                        'thread.created_at',
                        DB::raw('CASE WHEN thread.user_type = "INSTRUCTOR" THEN instructor.instructor_fname ELSE learner.learner_fname END AS first_name'),
                        DB::raw('CASE WHEN thread.user_type = "INSTRUCTOR" THEN instructor.instructor_lname ELSE learner.learner_lname END AS last_name'),
                        DB::raw('CASE WHEN thread.user_type = "INSTRUCTOR" THEN instructor.profile_picture ELSE learner.profile_picture END AS profile_picture'),
                        
                        DB::raw('CASE WHEN thread.community_id = 0 THEN "ALL" ELSE course.course_name END AS community_name'),
                        'thread_contents.thread_content_id',
                        'thread_contents.thread_type',
                        'thread_contents.thread_title',
                        'thread_contents.thread_content',
                        'thread_upvotes.thread_upvote_id',
                        'thread_upvotes.base_upvote',
                        'thread_upvotes.randomized_display_upvote',
                        'thread_upvotes.last_randomized_datetime',
                        DB::raw('(SELECT COUNT(*) FROM thread_comments WHERE thread_comments.thread_id = thread.thread_id) AS comment_count'),
                        DB::raw('(SELECT COUNT(*) FROM thread_comment_replies WHERE thread_comment_replies.thread_id = thread.thread_id) AS comment_reply_count'),
                        DB::raw('(SELECT COUNT(*) FROM thread_reply_replies WHERE thread_reply_replies.thread_id = thread.thread_id) AS reply_reply_count'),
                        DB::raw('(SELECT COUNT(*) FROM thread_comments WHERE thread_comments.thread_id = thread.thread_id) +
                                (SELECT COUNT(*) FROM thread_comment_replies WHERE thread_comment_replies.thread_id = thread.thread_id) +
                                (SELECT COUNT(*) FROM thread_reply_replies WHERE thread_reply_replies.thread_id = thread.thread_id) AS total_count')
                    )
                    ->leftJoin('learner', 'learner.learner_id', '=', 'thread.user_id')
                    ->leftJoin('instructor', 'instructor.instructor_id', '=', 'thread.user_id')
                    ->leftJoin('course', 'course.course_id', '=', 'thread.community_id')
                    ->leftJoin('thread_contents', 'thread_contents.thread_id', '=', 'thread.thread_id')
                    ->leftJoin('thread_upvotes', 'thread_upvotes.thread_id', '=', 'thread.thread_id')
                    ->orderBy('thread.created_at', 'DESC')
                    ->orderBy('thread_upvotes.base_upvote', 'DESC')
                    ->get();
    
                $data = [
                    'title' => 'Discussions',
                    'threads' => $threadsData,
                ];
                // dd($data);
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
    
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/instructor');
        }
    }

    public function createDiscussion() {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                $courseData = DB::table('course')
                ->select(
                    'course_id',
                    'course_name',
                    'course_code',
                )
                ->where('instructor_id', $instructor->instructor_id)
                ->get();

                
            $data = [
                'title' => 'Discussion',
                'scripts' => ['instructor_create_discussion.js'],
                'courses' => $courseData,
            ];

            // dd($data);
            return view('instructor_discussions.instructorCreateThread' , compact('instructor'))
            ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }
    }

    public function postDiscussion(Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            $thread_type = $request->input('thread_type');
            $thread_title = $request->input('thread_title');
            $thread_content = $request->input('thread_content');
            $community_id = $request->input('community_id');
            $user_type = 'INSTRUCTOR';

            try {
                $threadData = [
                    'community_id' => $community_id,
                    'user_id' => $instructor->instructor_id,
                    'user_type' => $user_type,
                ];

                $newThreadRow = Thread::create($threadData);

                $threadContentData = [
                    'thread_id' => $newThreadRow->thread_id,
                    'thread_type' => $thread_type,
                    'thread_title' => $thread_title,
                    'thread_content' => $thread_content,
                ];

                ThreadContents::create($threadContentData);

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                $min = 100;
                $max = 9999;

                $randomNumber = rand($min, $max);

                $threadUpvoteData = [
                    'thread_id' => $newThreadRow->thread_id,
                    'base_upvote' => 0,
                    'randomized_display_upvote' => $randomNumber,
                    'last_randomized_datetime' => $timestampString,
                ];

                ThreadUpvotes::create($threadUpvoteData);
    
                $data = [
                    'success' => true,
                    'redirect_url' => '/instructor/discussions'
                ];

                session()->flash('message', 'Thread successfully posted');
                return response()->json($data);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => 'An error occurred while emptying quiz content.']);
            }

        } else {
            return redirect('/instructor');
        }
    }
    
    
    public function postPhotoDiscussion(Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            $thread_type = $request->input('thread_type');
            $thread_title = $request->input('thread_title');
            $community_id = $request->input('community_id');
            $thread_content = $request->input('thread_content');
            $user_type = 'INSTRUCTOR';


            try {
                $threadData = [
                    'community_id' => $community_id,
                    'user_id' => $instructor->instructor_id,
                    'user_type' => $user_type,
                ];

                $newThreadRow = Thread::create($threadData);


                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $communityId = $community_id;
                
                    $folderPath = "discussions/{$newThreadRow->thread_id}";
                
                    if (!Storage::exists("public/{$folderPath}")) {
                        Storage::makeDirectory("public/{$folderPath}");
                    }
                
                    $fileName = time() . '_' . $photo->getClientOriginalName();
                    $filePath = $photo->storeAs($folderPath, $fileName, 'public');
                    $newThreadRow->update(['photo' => $filePath]);
                } else {
                    // If no file is uploaded, set the 'photo' field to null or handle it based on your database schema.
                    $newThreadRow->update(['photo' => null]);
                }

                
                $threadContentData = [
                    'thread_id' => $newThreadRow->thread_id,
                    'thread_type' => $thread_type,
                    'thread_title' => $thread_title,
                    'thread_content' => $filePath,
                ];

                ThreadContents::create($threadContentData);

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                $min = 100;
                $max = 9999;

                $randomNumber = rand($min, $max);

                $threadUpvoteData = [
                    'thread_id' => $newThreadRow->thread_id,
                    'base_upvote' => 0,
                    'randomized_display_upvote' => $randomNumber,
                    'last_randomized_datetime' => $timestampString,
                ];

                ThreadUpvotes::create($threadUpvoteData);

                $data = [
                    'success' => true,
                    'redirect_url' => '/instructor/discussions'
                ];

                session()->flash('message', 'Thread successfully posted');
                return response()->json($data);
            } catch (\Exception $e) {

                return response()->json(['error' => 'An error occurred while emptying quiz content.']);
            }

        } else {
            return redirect('/instructor');
        }
    }


    public function viewThread(Thread $thread) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                
                $threadData = DB::table('thread')
                ->select(
                    'thread.thread_id',
                    'thread.community_id',
                    'thread.user_id',
                    'thread.user_type',
                    'thread.created_at',
                    DB::raw('CASE WHEN thread.user_type = "INSTRUCTOR" THEN instructor.instructor_fname ELSE learner.learner_fname END AS first_name'),
                    DB::raw('CASE WHEN thread.user_type = "INSTRUCTOR" THEN instructor.instructor_lname ELSE learner.learner_lname END AS last_name'),
                    DB::raw('CASE WHEN thread.user_type = "INSTRUCTOR" THEN instructor.profile_picture ELSE learner.profile_picture END AS profile_picture'),
                    DB::raw('CASE WHEN thread.community_id = 0 THEN "ALL" ELSE course.course_name END AS community_name'),
                    'thread_contents.thread_content_id',
                    'thread_contents.thread_type',
                    'thread_contents.thread_title',
                    'thread_contents.thread_content',
                    'thread_upvotes.thread_upvote_id',
                    'thread_upvotes.base_upvote',
                    'thread_upvotes.randomized_display_upvote',
                    'thread_upvotes.last_randomized_datetime',
                    DB::raw('(SELECT COUNT(*) FROM thread_comments WHERE thread_comments.thread_id = thread.thread_id) AS comment_count'),
                    DB::raw('(SELECT COUNT(*) FROM thread_comment_replies WHERE thread_comment_replies.thread_id = thread.thread_id) AS comment_reply_count'),
                    DB::raw('(SELECT COUNT(*) FROM thread_reply_replies WHERE thread_reply_replies.thread_id = thread.thread_id) AS reply_reply_count'),
                    DB::raw('(SELECT COUNT(*) FROM thread_comments WHERE thread_comments.thread_id = thread.thread_id) +
                            (SELECT COUNT(*) FROM thread_comment_replies WHERE thread_comment_replies.thread_id = thread.thread_id) +
                            (SELECT COUNT(*) FROM thread_reply_replies WHERE thread_reply_replies.thread_id = thread.thread_id) AS total_count')
                )
                ->leftJoin('learner', 'learner.learner_id', '=', 'thread.user_id')
                ->leftJoin('instructor', 'instructor.instructor_id', '=', 'thread.user_id')
                ->leftJoin('course', 'course.course_id', '=', 'thread.community_id')
                ->leftJoin('thread_contents', 'thread_contents.thread_id', '=', 'thread.thread_id')
                ->leftJoin('thread_upvotes', 'thread_upvotes.thread_id', '=', 'thread.thread_id')
                ->orderBy('thread.created_at', 'DESC')
                ->orderBy('thread_upvotes.base_upvote', 'DESC')
                ->where('thread.thread_id', $thread->thread_id)
                ->first();


                $data = [
                    'title' => 'Discussion',
                    'scripts' => ['instructor_view_discussion.js'],
                    'thread' => $threadData,
                ];

            // dd($data);
            return view('instructor_discussions.instructorViewThread' , compact('instructor'))
            ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }
    }

    public function viewThreadComments(Thread $thread, Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $sortVal = $request->input('sortVal');
                // dd($sortVal);
                $threadData = DB::table('thread_comments')
                ->select(
                    'thread_comments.thread_comment_id',
                    'thread_comments.user_id',
                    'thread_comments.user_type',
                    'thread_comments.thread_comment',
                    'thread_comments.created_at',
                    'thread_comment_upvotes.base_upvote',
                    'thread_comment_upvotes.randomized_display_upvote',

                    DB::raw('CASE WHEN thread_comments.user_type = "INSTRUCTOR" THEN instructor.instructor_fname ELSE learner.learner_fname END AS first_name'),
                    DB::raw('CASE WHEN thread_comments.user_type = "INSTRUCTOR" THEN instructor.instructor_lname ELSE learner.learner_lname END AS last_name'),
                    DB::raw('CASE WHEN thread_comments.user_type = "INSTRUCTOR" THEN instructor.profile_picture ELSE learner.profile_picture END AS profile_picture'),
                
                    DB::raw('(SELECT COUNT(*) FROM thread_comment_replies WHERE thread_comment_replies.thread_comment_id = thread_comments.thread_comment_id) AS comment_reply_count'),
                    DB::raw('(SELECT COUNT(*) FROM thread_reply_replies WHERE thread_reply_replies.thread_id = thread_comments.thread_id) AS reply_reply_count'),
                    DB::raw('(SELECT SUM(comment_reply_count) + SUM(reply_reply_count)) AS total_replies_count'),
                
                    )
                ->leftJoin('thread_comment_upvotes', 'thread_comment_upvotes.thread_comment_id', '=', 'thread_comments.thread_comment_id')
                ->leftJoin('learner', 'learner.learner_id', '=', 'thread_comments.user_id')
                ->leftJoin('instructor', 'instructor.instructor_id', '=', 'thread_comments.user_id')
                ->where('thread_comments.thread_id', $thread->thread_id);

                if ($sortVal === 'TOP') {
                    $threadData->orderBy('thread_comment_upvotes.base_upvote', 'DESC');
                } elseif ($sortVal === 'NEW') {
                    $threadData->orderBy('thread_comments.created_at', 'DESC');
                } elseif ($sortVal === 'OLD') {
                    $threadData->orderBy('thread_comments.created_at', 'ASC');
                }

                $threadComments = $threadData->get();
            
            foreach ($threadComments as $comment) {
                $comment->replies = DB::table('thread_comment_replies')
                    ->select(
                        'thread_comment_replies.thread_comment_reply_id',
                        'thread_comment_replies.user_id',
                        'thread_comment_replies.user_type',
                        'thread_comment_replies.thread_comment_reply',
                        'thread_comment_replies.created_at',
                        'thread_comment_reply_upvotes.base_upvote',
                        'thread_comment_reply_upvotes.randomized_display_upvote',

                        DB::raw('CASE WHEN thread_comment_replies.user_type = "INSTRUCTOR" THEN instructor.instructor_fname ELSE learner.learner_fname END AS first_name'),
                        DB::raw('CASE WHEN thread_comment_replies.user_type = "INSTRUCTOR" THEN instructor.instructor_lname ELSE learner.learner_lname END AS last_name'),
                        DB::raw('CASE WHEN thread_comment_replies.user_type = "INSTRUCTOR" THEN instructor.profile_picture ELSE learner.profile_picture END AS profile_picture'),
                    
                        DB::raw('(SELECT COUNT(*) FROM thread_reply_replies WHERE thread_reply_replies.thread_id = thread_comment_replies.thread_id) AS reply_reply_count'),
                    )
                    ->leftJoin('thread_comment_reply_upvotes', 'thread_comment_reply_upvotes.thread_comment_reply_id', '=', 'thread_comment_replies.thread_comment_reply_id')
                    ->leftJoin('learner', 'learner.learner_id', '=', 'thread_comment_replies.user_id')
                    ->leftJoin('instructor', 'instructor.instructor_id', '=', 'thread_comment_replies.user_id')
                    ->where('thread_comment_replies.thread_id', $thread->thread_id)
                    ->where('thread_comment_replies.thread_comment_id', $comment->thread_comment_id)
                    ->orderBy('thread_comment_reply_upvotes.base_upvote', 'DESC')
                    ->get();
            
                    foreach ($comment->replies as $reply) {
                        $reply->nestedReplies = DB::table('thread_reply_replies')
                            ->select(
                                'thread_reply_replies.thread_reply_reply_id',
                                'thread_reply_replies.user_id',
                                'thread_reply_replies.user_type',
                                'thread_reply_replies.thread_reply_reply',
                                'thread_reply_replies.created_at',
                                'thread_reply_reply_upvotes.base_upvote',
                                'thread_reply_reply_upvotes.randomized_display_upvote',
                    
                                DB::raw('CASE WHEN thread_reply_replies.user_type = "INSTRUCTOR" THEN instructor.instructor_fname ELSE learner.learner_fname END AS first_name'),
                                DB::raw('CASE WHEN thread_reply_replies.user_type = "INSTRUCTOR" THEN instructor.instructor_lname ELSE learner.learner_lname END AS last_name'),
                                DB::raw('CASE WHEN thread_reply_replies.user_type = "INSTRUCTOR" THEN instructor.profile_picture ELSE learner.profile_picture END AS profile_picture'),
                                
                            )
                            ->leftJoin('thread_reply_reply_upvotes', 'thread_reply_reply_upvotes.thread_reply_reply_id', '=', 'thread_reply_replies.thread_reply_reply_id')
                            ->leftJoin('learner', 'learner.learner_id', '=', 'thread_reply_replies.user_id')
                            ->leftJoin('instructor', 'instructor.instructor_id', '=', 'thread_reply_replies.user_id')
                            ->where('thread_reply_replies.thread_comment_reply_id', $reply->thread_comment_reply_id)
                            ->orderBy('thread_reply_reply_upvotes.base_upvote', 'DESC')
                            ->get();
                    }
            }
            
            $data = [
                'title' => 'Discussions',
                'threadData' => $threadComments,
            ];
            
            return response()->json($data);
            
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/instructor');
        }
    }
    

    public function upvoteThread(Thread $thread) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $threadUpvoteData = DB::table('thread_upvotes')
                    ->select(
                        'thread_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->first();

    
                $updated_base_upvote = $threadUpvoteData->base_upvote + 1;
                $updated_randomized_display_upvote = $threadUpvoteData->randomized_display_upvote + 1;


                DB::table('thread_upvotes')
                    ->where('thread_upvote_id', $threadUpvoteData->thread_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }

    public function downvoteThread(Thread $thread) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $threadUpvoteData = DB::table('thread_upvotes')
                    ->select(
                        'thread_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->first();

    
                $updated_base_upvote = $threadUpvoteData->base_upvote - 1;
                $updated_randomized_display_upvote = $threadUpvoteData->randomized_display_upvote - 1;


                DB::table('thread_upvotes')
                    ->where('thread_upvote_id', $threadUpvoteData->thread_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }
    

    public function postComment(Thread $thread, Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $thread_comment = $request->input('thread_comment');
                $rowData = [
                    'thread_id' => $thread->thread_id,
                    'user_id' => $instructor->instructor_id,
                    'user_type' => 'INSTRUCTOR',
                    'thread_comment' => $thread_comment,
                ];

                $newThreadCommentRow = ThreadComments::create($rowData);

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                $min = 100;
                $max = 9999;

                $randomNumber = rand($min, $max);

                $upvoteRowData = [
                    'thread_id' => $thread->thread_id,
                    'thread_comment_id' => $newThreadCommentRow->thread_comment_id,
                    'base_upvote' => 0,
                    'randomized_display_upvote' => $randomNumber,
                    'last_randomized_datetime' => $timestampString,
                ];

                $newThreadCommentUpvoteRow = ThreadCommentUpvotes::create($upvoteRowData);

                $data = [
                    'message' => 'Comment successfully posted',
                ];
    
                session()->flash('message', 'Thread Comment successfully posted');
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while posting comment.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }


    public function postCommentReply(Thread $thread, Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $thread_comment_id = $request->input('thread_comment_id');
                $thread_comment_reply = $request->input('thread_comment_reply');

                $rowData = [
                    'thread_id' => $thread->thread_id,
                    'thread_comment_id' => $thread_comment_id,
                    'user_id' => $instructor->instructor_id,
                    'user_type' => 'INSTRUCTOR',
                    'thread_comment_reply' => $thread_comment_reply,
                ];

                $newThreadCommentRow = ThreadCommentReplies::create($rowData);

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                $min = 100;
                $max = 9999;

                $randomNumber = rand($min, $max);

                $upvoteRowData = [
                    'thread_id' => $thread->thread_id,
                    'thread_comment_id' => $thread_comment_id,
                    'thread_comment_reply_id' => $newThreadCommentRow->thread_comment_reply_id,
                    'base_upvote' => 0,
                    'randomized_display_upvote' => $randomNumber,
                    'last_randomized_datetime' => $timestampString,
                ];

                $newThreadCommentUpvoteRow = ThreadCommentReplyUpvotes::create($upvoteRowData);

                $data = [
                    'message' => 'Comment Reply successfully posted',
                ];
    
                session()->flash('message', 'Thread Comment Reply successfully posted');
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while posting comment reply.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }


    public function postReplyReply(Thread $thread, Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $thread_comment_id = $request->input('thread_comment_id');
                $thread_comment_reply_id = $request->input('thread_comment_reply_id');
                $thread_reply_reply = $request->input('thread_reply_reply');

                $rowData = [
                    'thread_id' => $thread->thread_id,
                    'thread_comment_id' => $thread_comment_id,
                    'thread_comment_reply_id' => $thread_comment_reply_id,
                    'user_id' => $instructor->instructor_id,
                    'user_type' => 'INSTRUCTOR',
                    'thread_reply_reply' => $thread_reply_reply,
                ];

                $newThreadReplyRow = ThreadReplyReplies::create($rowData);

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                $min = 100;
                $max = 9999;

                $randomNumber = rand($min, $max);

                $upvoteRowData = [
                    'thread_id' => $thread->thread_id,
                    'thread_comment_id' => $thread_comment_id,
                    'thread_comment_reply_id' => $thread_comment_reply_id,
                    'thread_reply_reply_id' => $newThreadReplyRow->thread_reply_reply_id,
                    'base_upvote' => 0,
                    'randomized_display_upvote' => $randomNumber,
                    'last_randomized_datetime' => $timestampString,
                ];

                $newThreadReplyUpvoteRow = ThreadReplyReplyUpvotes::create($upvoteRowData);

                $data = [
                    'message' => 'Comment Reply successfully posted',
                ];
    
                session()->flash('message', 'Thread Comment Reply successfully posted');
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while posting comment reply.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }



    public function upvoteThreadComment(Thread $thread, ThreadComments $thread_comment) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {

                $threadCommentUpvoteData = DB::table('thread_comment_upvotes')
                    ->select(
                        'thread_comment_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->first();

    
                $updated_base_upvote = $threadCommentUpvoteData->base_upvote + 1;
                $updated_randomized_display_upvote = $threadCommentUpvoteData->randomized_display_upvote + 1;


                DB::table('thread_comment_upvotes')
                    ->where('thread_comment_upvote_id', $threadCommentUpvoteData->thread_comment_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }

    public function downvoteThreadComment(Thread $thread, ThreadComments $thread_comment) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $threadCommentUpvoteData = DB::table('thread_comment_upvotes')
                    ->select(
                        'thread_comment_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->first();

    
                $updated_base_upvote = $threadCommentUpvoteData->base_upvote - 1;
                $updated_randomized_display_upvote = $threadCommentUpvoteData->randomized_display_upvote - 1;


                DB::table('thread_comment_upvotes')
                    ->where('thread_comment_upvote_id', $threadCommentUpvoteData->thread_comment_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }

    public function upvoteThreadCommentReply(Thread $thread, ThreadComments $thread_comment, ThreadCommentReplies $thread_comment_reply) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {

                $threadCommentReplyUpvoteData = DB::table('thread_comment_reply_upvotes')
                    ->select(
                        'thread_comment_reply_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->first();

    
                $updated_base_upvote = $threadCommentReplyUpvoteData->base_upvote + 1;
                $updated_randomized_display_upvote = $threadCommentReplyUpvoteData->randomized_display_upvote + 1;


                DB::table('thread_comment_reply_upvotes')
                    ->where('thread_comment_reply_upvote_id', $threadCommentReplyUpvoteData->thread_comment_reply_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }

    public function downvoteThreadCommentReply(Thread $thread, ThreadComments $thread_comment, ThreadCommentReplies $thread_comment_reply) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {

                $threadCommentReplyUpvoteData = DB::table('thread_comment_reply_upvotes')
                    ->select(
                        'thread_comment_reply_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->first();

    
                $updated_base_upvote = $threadCommentReplyUpvoteData->base_upvote - 1;
                $updated_randomized_display_upvote = $threadCommentReplyUpvoteData->randomized_display_upvote - 1;


                DB::table('thread_comment_reply_upvotes')
                    ->where('thread_comment_reply_upvote_id', $threadCommentReplyUpvoteData->thread_comment_reply_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }


    public function upvoteThreadReplyReply(Thread $thread, ThreadComments $thread_comment, ThreadCommentReplies $thread_comment_reply, ThreadReplyReplies $thread_reply_reply) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {

                $threadReplyReplyUpvoteData = DB::table('thread_reply_reply_upvotes')
                    ->select(
                        'thread_reply_reply_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->where('thread_reply_reply_id', $thread_reply_reply->thread_reply_reply_id)
                    ->first();

    
                $updated_base_upvote = $threadReplyReplyUpvoteData->base_upvote + 1;
                $updated_randomized_display_upvote = $threadReplyReplyUpvoteData->randomized_display_upvote + 1;


                DB::table('thread_reply_reply_upvotes')
                    ->where('thread_reply_reply_upvote_id', $threadReplyReplyUpvoteData->thread_reply_reply_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->where('thread_reply_reply_id', $thread_reply_reply->thread_reply_reply_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }

    public function downvoteThreadReplyReply(Thread $thread, ThreadComments $thread_comment, ThreadCommentReplies $thread_comment_reply, ThreadReplyReplies $thread_reply_reply) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {

                $threadReplyReplyUpvoteData = DB::table('thread_reply_reply_upvotes')
                    ->select(
                        'thread_reply_reply_upvote_id',
                        'base_upvote',
                        'randomized_display_upvote',
                        'last_randomized_datetime'
                    )
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->where('thread_reply_reply_id', $thread_reply_reply->thread_reply_reply_id)
                    ->first();

    
                $updated_base_upvote = $threadReplyReplyUpvoteData->base_upvote - 1;
                $updated_randomized_display_upvote = $threadReplyReplyUpvoteData->randomized_display_upvote - 1;


                DB::table('thread_reply_reply_upvotes')
                    ->where('thread_reply_reply_upvote_id', $threadReplyReplyUpvoteData->thread_reply_reply_upvote_id)
                    ->where('thread_id', $thread->thread_id)
                    ->where('thread_comment_id', $thread_comment->thread_comment_id)
                    ->where('thread_comment_reply_id', $thread_comment_reply->thread_comment_reply_id)
                    ->where('thread_reply_reply_id', $thread_reply_reply->thread_reply_reply_id)
                    ->update([
                        'base_upvote' => $updated_base_upvote,
                        'randomized_display_upvote' => $updated_randomized_display_upvote,
                    ]);
    
                $data = [
                    'message' => 'upvoted successfully',
                ];
    
                return response()->json($data);
    
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while upvoting thread.']);
            }
    
        } else {
            return redirect('/instructor');
        }
    }


}
