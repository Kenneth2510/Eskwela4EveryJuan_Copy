$(document).ready(function() {
    var baseUrl = window.location.href;

    tinymce.init({  
     selector: '#insertLessonContent',  
     width: 730
    }); 

    tinymce.init({  
        selector: '#insertEditLessonContent',  
        width: 730
    }); 
    
    // tinymce.init({  
    //     selector: 'textarea#lesson_content_id',  
    //     width: 500  
    //    }); 
    
    var lessonData = {};
    // console.log(lessonData)
    // Select all <p> elements with the class .lesson_content_input_disp
    // var pElements = $('.lesson_content_input_disp');
    var iElements = $('.lesson_content_input');
        
    // Use the filter function to select only those with newline characters
    // var pElementsWithNewlines = pElements.filter(function() {
    //     return $(this).text().includes('\n');
    // });

    var iElementsWithNewlines = iElements.filter(function() {
        return $(this).text().includes('\n');
    });
    
    // Apply white-space: pre to the selected elements
    // pElementsWithNewlines.css('white-space', 'pre');
    iElementsWithNewlines.css('white-space', 'pre');





    // overall edit button
    $('#editLessonBtn').on('click', function(e) {
        e.preventDefault();
        $('#editBtns').removeClass('hidden');
        $('#editLessonBtn').addClass('hidden');

        $('#edit_lesson_title').removeClass('hidden');

        $('.edit_lesson_content').removeClass('hidden');

        $('#lessonAddContent').removeClass('hidden');
    
        // Scroll to the target element after all the changes have been made
        const targetOffset = $("#lesson_title_area").offset().top;

        // Debugging: Output the target offset to the console
        console.log("Target Offset:", targetOffset);
    
        $('html, body').animate({
            scrollTop: targetOffset
        }, 1000);

        const courseID = $(this).data('course-id');
        const syllabusID = $(this).data('syllabus-id');
        const topicID = $(this).data('topic_id');

        $('#lesson_content_pictureUploadForm').data('course-id', courseID)

        var url = "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/lesson/"+ topicID +"/json";

        console.log(url)

        $.ajax ({
            type: "GET",
            url: "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/lesson/"+ topicID +"/json",
            dataType: 'json',
            success: function (response){

                // console.log(response)
                lessonData = response['lessonContent']
                console.log(lessonData)
                reDisplayLesson(lessonData);
            },
            error: function(error) {
                console.log(error);
            }
      })




    });

    function reDisplayLesson(lessonData) {


        var displayLesson = ``;

        const baseUrl = window.location.protocol + '//' + window.location.host;


        for (let i = 0; i < lessonData.length; i++) {
            
            lessonData[i]['lesson_content_order'] = i + 1;

            const lesson_content_id = lessonData[i]['lesson_content_id'];
            const lesson_id = lessonData[i]['lesson_id'];
            const lesson_content_title = lessonData[i]['lesson_content_title'];
            const lesson_content = lessonData[i]['lesson_content'];
            const lesson_content_order = lessonData[i]['lesson_content_order'];
            const picture = lessonData[i]['picture'];
            const video_url = lessonData[i]['video_url'];
            
            const pic_url = baseUrl + '/storage/' + picture;

            displayLesson += `
    <div data-content-order="${lesson_content_order}" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class="px-10 lesson_content_area  my-2 mb-8 w-full">
        <button class="edit_lesson_content hidden">
            <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/>
            </svg>
        </button>
        
        <input type="text" class="lesson_content_title_input text-2xl font-bold border-none w-10/12" disabled name="lesson_content_title_input" id="" value="${lesson_content_title}">
        
        ${picture !== null ? `
        <div id="lesson_content_img" class="flex justify-center w-full h-[400px] my-4 rounded-lg shadow">
            <div class="w-full h-[400px] overflow-hidden rounded-lg">
                <img src="${pic_url}" class="object-contain w-full h-full" alt="">
            </div>
        </div>
        
        <div id="" style="position: relative; top: 75%;" class="my-2 edit_lesson_content_picture_btns hidden flex justify-end">
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class=" add_lesson_content_picture_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-green-600 hover:bg-green-900">
                Change Photo
            </button>
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class=" delete_lesson_content_picture_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-red-600 hover:bg-red-900">
                Delete Photo
            </button>
        </div>
        ` 
        : `
        <div id="" style="position: relative; top: 75%;" class="my-2 edit_lesson_content_picture_btns hidden flex justify-end">
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class=" add_lesson_content_picture_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-green-600 hover:bg-green-900">
                Add Photo
            </button>
        </div>
        `}

        <div class="contentArea text-xl font-normal lesson_content_input_disp mt-5 px-5" style="white-space: pre-wrap">${lesson_content}</div>

        <div id="" style="position: relative; top: 75%;" class="edit_content_btnArea my-2  hidden flex justify-end">
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class="edit_content_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-green-600 hover:bg-green-900">
                Edit Content
            </button>
        </div>
        

        ${video_url !== null ? `
        <div id="lesson_content_url" class="flex justify-center w-full h-[400px] my-4 rounded-lg shadow">
            <div class="url_embed_area w-full h-[400px] flex justify-center overflow-hidden rounded-lg">
                ${video_url}
            </div>
        </div>
        
        <div id="" style="position: relative; top: 75%;" class="my-2 edit_lesson_content_url_btns hidden flex justify-end">
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class=" add_lesson_content_url_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-green-600 hover:bg-green-900">
                Change url
            </button>
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class=" delete_lesson_content_url_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-red-600 hover:bg-red-900">
                Delete url
            </button>
        </div>
        ` 
        : `
        <div id="" style="position: relative; top: 75%;" class="my-2 edit_lesson_content_url_btns hidden flex justify-end">
            <button id="" data-lesson-content-id="${lesson_content_id}" data-lesson-id="${lesson_id}" class=" add_lesson_content_url_btn mr-3 flex text-white rounded-xl py-3 px-5 bg-green-600 hover:bg-green-900">
                Add Video from url
            </button>
        </div>
        `}

        
        <div class="edit_lesson_content_btns hidden flex w-full justify-end">
            <button data-content-order="${lesson_content_order}" data-lesson-id="${lesson_id}" data-lesson-content-id="${lesson_content_id}" id="" class="save_lesson_content_btn mx-1 text-white rounded-xl py-3 px-5 bg-green-600 hover:bg-green-900">
                Save
            </button>
            <button data-content-order="${lesson_content_order}" data-lesson-id="${lesson_id}" data-lesson-content-id="${lesson_content_id}" id="" class="delete_lesson_content_btn mx-1 text-white rounded-xl py-3 px-5 bg-red-600 hover:bg-red-800">
                Delete
            </button>
            <button id="" class="cancel_lesson_content_btn mx-1 text-white rounded-xl py-3 px-5 bg-red-600 hover:bg-red-900">
                Cancel
            </button>
        </div>
    </div>
`;
        }
            $('#main_content_area').empty();
            $('#main_content_area').append(displayLesson);

            $('#editBtns').removeClass('hidden');
            $('#editLessonBtn').addClass('hidden');

            $('#edit_lesson_title').removeClass('hidden');

            $('.edit_lesson_content').removeClass('hidden');

            $('#lessonAddContent').removeClass('hidden');


            // $('#edit_lesson_content_picture_btns').removeClass('hidden');

        console.log(lessonData)



        $('.add_lesson_content_picture_btn').on('click', function(e) {
            e.preventDefault()

            const lesson_contentID = $(this).data('lesson-content-id')
            const lessonID = $(this).data('lesson-id');



            // alert(lesson_contentID)
            $('#lesson_content_pictureUploadForm').data('lesson-id', lessonID)
            $('#lesson_content_pictureUploadForm').data('lesson-content-id', lesson_contentID)
            $('#lesson_content_pictureModal').removeClass('hidden');
        })

        $('#closeModal_lesson_content_picture').on('click', function(e) {
            e.preventDefault();

            $('#lesson_content_pictureModal').addClass('hidden');
        })

        $('#cancelUpload_lesson_content_picture').on('click', function(e) {
            e.preventDefault();
            // alert(lesson_contentID)
            $('#lesson_content_pictureModal').addClass('hidden');
        })

      
        $('#lesson_content_pictureUploadForm').on('submit', function(e) {
            e.preventDefault();

            const lesson_contentID = $(this).data('lesson-content-id');
            const lessonID = $(this).data('lesson-id');
            const courseID = $(this).data('course-id');
            const syllabusID = $(this).data('syllabus-id');
            const topicID = $(this).data('topic_id');

            console.log(lesson_contentID, lessonID)


            var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var formData = new FormData(this);
        const url = "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/lesson/"+ topicID +"/title/"+ lessonID +"/store_file/"+ lesson_contentID;

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: false,
            processData: false,
            success: function(response) {
                // alert('Upload successful!');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('error uploading file')
                console.log('Error:', error);
            }
        });
        })

        
        $('.delete_lesson_content_picture_btn').on('click', function(e) {   
            e.preventDefault();

            const lessonID = $(this).data('lesson-id');
            const lesson_contentID = $(this).data('lesson-content-id');

            $('#confirmDelete_lessonContentPicture').data('lesson-id', lessonID);
            $('#confirmDelete_lessonContentPicture').data('lesson-content-id', lesson_contentID);            

            $('#deleteLessonContentPictureModal').removeClass('hidden');
        })

        $('#cancelDelete_lessonContentPicture').on('click', function(e) {

            $('#deleteLessonContentPictureModal').addClass('hidden');
        })

        $('#confirmDelete_lessonContentPicture').on('click', function(e) {
            e.preventDefault();

            const lessonID = $(this).data('lesson-id');
            const lesson_contentID = $(this).data('lesson-content-id');
            const courseID = $(this).data('course-id');
            const syllabusID = $(this).data('syllabus-id');
            const topicID = $(this).data('topic_id');

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            const url = "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/lesson/"+ topicID +"/title/"+ lessonID +"/delete_file/"+ lesson_contentID;
            // console.log(url)
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Handle success if needed
                    // console.log(response);
                    // $('#deleteLessonContentModal').addClass('hidden');
                    // reDisplayLesson(lessonData)
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })

        // edit existing lesson content
    $('.edit_lesson_content').on('click', function(e) {
        e.preventDefault();
        const lesson_content_area = $(this).closest('.lesson_content_area')
        const lesson_content_title = lesson_content_area.find('.lesson_content_title_input');        
        const lesson_content_btn = lesson_content_area.find('.edit_content_btnArea');
        const lesson_content = lesson_content_area.find('.contentArea'); 
        const lesson_content_disp = lesson_content_area.find('.lesson_content_input_disp');
        const lesson_content_picture = lesson_content_area.find('.edit_lesson_content_picture_btns')


        // var content = tinyMCE.get("lesson_content_input").getContent();

        // const lessonContent = content;

        lesson_content_disp.addClass('hidden');
        lesson_content.removeClass('hidden');
        lesson_content_picture.removeClass('hidden');
        lesson_content_btn.removeClass('hidden');

        lesson_content_area.find('.edit_lesson_content_btns').removeClass('hidden');
        lesson_content_area.find('.edit_lesson_content').removeClass('hidden');
        lesson_content_area.find('.edit_lesson_content_url_btns').removeClass('hidden');

      
    })

    $('.cancel_lesson_content_btn').on('click', function(e) {
        e.preventDefault();
        const lesson_content_area = $(this).closest('.lesson_content_area')
        const lesson_content_title = lesson_content_area.find('.lesson_content_title_input');
        const lesson_content = lesson_content_area.find('.lesson_content_input');
        const lesson_content_picture = lesson_content_area.find('.edit_lesson_content_picture_btns')
        const lesson_content_btn = lesson_content_area.find('.edit_content_btnArea');



        lesson_content_area.find('.edit_lesson_content_btns').addClass('hidden');
        lesson_content_area.find('.edit_lesson_content').removeClass('hidden');
        lesson_content_picture.addClass('hidden');
        lesson_content_btn.addClass('hidden');
    })

    $('.edit_content_btn').on('click', function(e) {
        e.preventDefault();
    
        const lesson_content_area = $(this).closest('.lesson_content_area');
        const lesson_content_title = lesson_content_area.find('.lesson_content_title_input');
        const lesson_content = lesson_content_area.find('.contentArea');
    
        const contentOrder = lesson_content_area.data('content-order');
        $('#confirmEditLessonContentBtn').data('content-order', contentOrder);
    
        const lesson_content_title_val = lesson_content_title.val();
        const html_lesson_content = lesson_content.html();
    
        // Assuming you have initialized TinyMCE on your textarea with id "insertEditLessonContent"
        const editor = tinymce.get('insertEditLessonContent');
    
        // Set the content in the TinyMCE editor
        editor.setContent(html_lesson_content);
        $('#insertEditLessonContentTitle').val(lesson_content_title_val);
        // Show the modal
        $('#editLessonContentModal').removeClass('hidden');
    });
    
    
    $('.closeEditLessonContentModal').on('click' , function(e) {
        e.preventDefault();
    
        // Assuming you have initialized TinyMCE on your textarea with id "insertEditLessonContent"
        const editor = tinymce.get('insertEditLessonContent');
    
        // Clear the content in the TinyMCE editor
        editor.setContent('');
    
        // Hide the modal
        $('#editLessonContentModal').addClass('hidden');
    });

    $('#confirmEditLessonContentBtn').on('click', function(e) {
        e.preventDefault();
    
        // Get the content order from the button's data attribute
        let contentOrder = $(this).data('content-order');
    
        // Get the updated content from the TinyMCE editor
        var content = tinyMCE.get("insertEditLessonContent").getContent();
    
        // Find the corresponding lesson_content_area based on the content order
        var lessonContentArea = $(`.lesson_content_area[data-content-order="${contentOrder}"]`);
    
        // Update the contentArea div inside the found lesson_content_area
        lessonContentArea.find('.contentArea').html(content);
        console.log
        // Close the modal or perform other actions as needed
        $('#editLessonContentModal').addClass('hidden');
    });
    

    $('.add_lesson_content_url_btn').on('click', function (e) {
        e.preventDefault();
    
        const lesson_content_area = $(this).closest('.lesson_content_area');
        const urlEmbedArea = lesson_content_area.find('.url_embed_area');
        const contentOrder = lesson_content_area.data('content-order');
        const lessonContentId = lesson_content_area.data('lesson-content-id');
        $('#confirmAddLessonContentUrlBtn').attr('data-content-order', contentOrder);
        $('#confirmAddLessonContentUrlBtn').attr('data-lesson-content-id', lessonContentId);
    
        const html_urlEmbedArea = urlEmbedArea.html();
    
        // Check if there is HTML content in url_embed_area
        if (html_urlEmbedArea !== '') {
            // If there is content, set it in the insertAddLessonContentUrl input
            $('#insertAddLessonContentUrl').val(html_urlEmbedArea);
        } else {
            // If no content, clear the input
            $('#insertAddLessonContentUrl').val('');
        }
    
        $('#addLessonContentUrlModal').removeClass('hidden');
    });
    
    
    $('#cancelAddLessonContentUrlBtn').on('click', function (e) {
        e.preventDefault();
        $('#addLessonContentUrlModal').addClass('hidden');
    });
    
    $('#confirmAddLessonContentUrlBtn').on('click', function (e) {
        e.preventDefault();
    
        const contentOrder = parseInt($(this).data('content-order'));
        const lessonContentId = parseInt($(this).data('lesson-content-id'));
        const lessonID = lessonData[0]['lesson_id'];

        const embed_url = $('#insertAddLessonContentUrl').val();
    
        const rowData = {
            video_url: embed_url
        }

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        const baseUrl = window.location.href;
        const url = baseUrl +"/title/"+ lessonID +"/store_video_url/"+ lessonContentId;

        $.ajax({
            type: "POST",
            url: url,
            data: rowData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // alert('Upload successful!');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('error uploading file')
                console.log('Error:', error);
            }
        });

        // $('#addLessonContentUrlModal').addClass('hidden');
    });


    $('.delete_lesson_content_url_btn').on('click', function(e) {
        e.preventDefault();

        const lesson_content_area = $(this).closest('.lesson_content_area');

        const contentOrder = lesson_content_area.data('content-order');
        const lessonContentId = lesson_content_area.data('lesson-content-id');
        const lessonId = lesson_content_area.data('lesson-id');
        $('#confirmDelete_lessonContentUrl').attr('data-content-order', contentOrder);
        $('#confirmDelete_lessonContentUrl').attr('data-lesson-content-id', lessonContentId);
        $('#confirmDelete_lessonContentUrl').attr('data-lesson-id', lessonId);

        $('#deleteLessonContentUrlModal').removeClass('hidden');
    })

    $('#cancelDelete_lessonContentUrl').on('click', function(e) {
        e.preventDefault();

        $('#deleteLessonContentUrlModal').addClass('hidden');
    })
    
    $('#confirmDelete_lessonContentUrl').on('click', function(e) {
            e.preventDefault();

            const contentOrder = parseInt($(this).data('content-order'));
            const lessonContentId = parseInt($(this).data('lesson-content-id'));
            const lessonId = parseInt($(this).data('lesson-id'));

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            const baseUrl = window.location.href;
            const url = baseUrl +"/title/"+ lessonId +"/delete_url/"+ lessonContentId;
    
            // console.log(url)
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Handle success if needed
                    // console.log(response);
                    // $('#deleteLessonContentModal').addClass('hidden');
                    // reDisplayLesson(lessonData)
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })
    












    $('.save_lesson_content_btn').on('click',function(e){
        e.preventDefault();

        const lesson_content_area = $(this).closest('.lesson_content_area')
        const lesson_content_title = lesson_content_area.find('.lesson_content_title_input');
        const lesson_content = lesson_content_area.find('.contentArea'); 

        const lesson_content_title_val = lesson_content_title.val();

        const html_lesson_content = lesson_content.html();

        const lessonID = $(this).data('lesson-id');
        const lessonContentID = $(this).data('lesson-content-id');


        const updatedValues = {
            'lesson_content_title': lesson_content_title_val,
            'lesson_content': html_lesson_content,
        }

        // console.log(updatedValues);
        const lessonContentIndex = lessonData.findIndex(content => content.lesson_content_id === lessonContentID);

        if (lessonContentIndex !== -1) {
            lessonData[lessonContentIndex].lesson_content_title = updatedValues.lesson_content_title;
            lessonData[lessonContentIndex].lesson_content = updatedValues.lesson_content;
        }

        console.log(lessonData);
        if(!/^none\d+$/.test(lessonContentID)) {
            const url = "/instructor/course/content/lesson/"+ lessonID +"/title/"+lessonContentID;

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
            $.ajax({
                type: "POST",
                url: url,
                data: updatedValues,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Handle success if needed
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            
        }

        

        // lesson_content_title.prop('disabled', true);
        // lesson_content.prop('disabled', true);

        lesson_content_area.find('.edit_lesson_content_btns').addClass('hidden');
        lesson_content_area.find('.edit_content_btnArea').addClass('hidden');
        lesson_content_area.find('.edit_lesson_content_picture_btns').addClass('hidden');
        lesson_content_area.find('.edit_lesson_content').removeClass('hidden');

    })

    $('.delete_lesson_content_btn').on('click', function(e) {
        e.preventDefault();

        const lessonID = $(this).data('lesson-id');
        const lessonContentID = $(this).data('lesson-content-id');

        $('#confirmDelete').data('lesson-id', lessonID);
        $('#confirmDelete').data('lesson-content-id', lessonContentID);
        $('#deleteLessonContentModal').removeClass('hidden');
    })

    }

    $('#confirmDelete').on('click', function(e) {
        const lessonID = $(this).data('lesson-id');
        const lessonContentID = $(this).data('lesson-content-id');

        const url = "/instructor/course/content/lesson/"+ lessonID +"/title/"+lessonContentID+"/delete";
        

        if(!/^none\d+$/.test(lessonContentID)) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Handle success if needed
                    // console.log(response);
                    // $('#deleteLessonContentModal').addClass('hidden');
                    // reDisplayLesson(lessonData)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {

        
    }

  // Find the index of the corresponding syllabusData item
               const index = lessonData.findIndex(item => item.lesson_content_id === lessonContentID);
        
               if (index !== -1) {
                   // Remove the item from the syllabusData array
                   lessonData.splice(index, 1);
               }
      
               $('#deleteLessonContentModal').addClass('hidden');
               reDisplayLesson(lessonData);

    })

    $('#cancelDelete').on('click', function(e) {
        
        $('#deleteLessonContentModal').addClass('hidden');
    })

    var originalLessonTitle;

    $('#edit_lesson_title').on('click', function(e) {
        e.preventDefault();
    
        // Find the contenteditable div
        var lessonTitleDiv = $('#lesson_title_area').find('[contenteditable="false"]');
    
        // Store the original lesson title content
        originalLessonTitle = lessonTitleDiv.text();
    
        // Enable editing by setting contenteditable to true
        lessonTitleDiv.prop('contenteditable', true).focus();
    
        // Show the edit buttons and hide the original edit button
        $('#edit_lesson_btns').removeClass('hidden');
        $('#edit_lesson_picture_btns').removeClass('hidden');
        $(this).addClass('hidden');
    });
    
    // Cancel editing the lesson title
    $('#cancel_lesson_btn').on('click', function(e) {
        e.preventDefault();
        // console.log(originalLessonTitle);
        // Find the contenteditable div
        var lessonTitleDiv = $('#lesson_title_area').find('[contenteditable="true"]');
    
        // Revert to the original lesson title content
        lessonTitleDiv.text(originalLessonTitle);
    
        // Disable editing by setting contenteditable to false
        lessonTitleDiv.prop('contenteditable', false);
    
        // Hide the edit buttons and show the original edit button
        $('#edit_lesson_btns').addClass('hidden');
        $('#edit_lesson_picture_btns').addClass('hidden');
        $('#edit_lesson_title').removeClass('hidden');
    });

// Save changes in the lesson title
$('#save_lesson_btn').on('click', function(e) {
    e.preventDefault();

    // Find the contenteditable div
    var lessonTitleDiv = $('#lesson_title_area [contenteditable="true"]');

    // Save changes in lesson title
    const updatedLessonTitle = lessonTitleDiv.text();
    console.log(updatedLessonTitle);

    // Rest of your code remains the same
    var syllabusID = $(this).data('syllabus-id');
    var lessonID = $(this).data('lesson-id');
    
    var courseID = $(this).data('course-id');
    var topicID = $(this).data('topic_id');

    var url = "/instructor/course/content/" + courseID + "/" + syllabusID + "/lesson/" + topicID + "/title/" + lessonID;

    var updated_value = {
        'lesson_title': updatedLessonTitle,
        'topic_title': updatedLessonTitle,
    }

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type: "POST",
        url: url,
        data: updated_value,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // Handle success if needed
            console.log("success");
            location.reload();
            console.log(response);
        },
        error: function(error) {
            console.log(error);
        }
    });
});


    

    // to add new content
    $('#lessonAddContent').on('click', function(e) {
        e.preventDefault();

        $('#addLessonContentModal').removeClass('hidden');
    })

    $('#cancelAddLessonContentBtn').on('click', function(e) {
        e.preventDefault();

        $('#addLessonContentModal').addClass('hidden');
    })

    $('#closeAddLessonContentModal').on('click', function(e) {
        e.preventDefault();

        $('#addLessonContentModal').addClass('hidden');
    })

    var none_count = 0;
    $('#confirmAddLessonContentBtn').on('click', function(e) {

        const chosenLocation = $('#insertLocation').val();
        const lessonContentTitle = $('#insertLessonContentTitle').val();

        var content = tinyMCE.get("insertLessonContent").getContent();

        const lessonContent = content;

        const lessonID = $(this).data('lesson-id');

        const newLessonContent = {
            lesson_content_id: 'none' + none_count++,
            lesson_id: lessonID,
            lesson_content_title: lessonContentTitle,
            lesson_content: lessonContent,
            picture: null
        }

        if(lessonData.length > 0) {
            if(chosenLocation == 'START') {
                lessonData.unshift(newLessonContent);
            } else if(chosenLocation == 'END') {
                lessonData.push(newLessonContent);
            } else {
                const insertIndex = lessonData.findIndex(topic => topic.lesson_content_title === chosenLocation);

                // Insert the new topic at the specified index
                if (insertIndex !== -1) {
                    lessonData.splice(insertIndex + 1, 0, newLessonContent);
                } else {
                    // Handle the case where the insertLocation is not found, you may choose to append it at the end.
                    lessonData.push(newLessonContent);
                }
            }
        } else {
            lessonData.push(newLessonContent);
        }

        $('#addLessonContentModal').addClass('hidden');
        reDisplayLesson(lessonData)

    })

    $('#insertLessonContent').keydown(function(event) {
        if (event.key === 'Enter') {
          event.preventDefault(); // Prevent the default behavior (line break)

          var textarea = $(this);
          var start = textarea[0].selectionStart;
          var end = textarea[0].selectionEnd;
          var value = textarea.val();

          // Insert a newline character at the cursor position
          var updatedValue = value.substring(0, start) + '\n' + value.substring(end);

          // Update the textarea's value and cursor position
          textarea.val(updatedValue);
          textarea[0].setSelectionRange(start + 1, start + 1);
        }
      });

      
    $('#cancelEditBtn').on('click', function(e) {
        e.preventDefault();
        $('#editBtns').addClass('hidden');
        $('#editLessonBtn').removeClass('hidden');

        $('#edit_lesson_title').addClass('hidden');

        $('.edit_lesson_content').addClass('hidden');
        $('.edit_lesson_content_btns').addClass('hidden');
        $('.lesson_content_input_disp').removeClass('hidden');
        $('.lesson_content_input').addClass('hidden');
        $('.lesson_content_title_input').attr('disabled', true)
        $('.lesson_content_input').prop('disabled', true)

        $('#lessonAddContent').addClass('hidden');
    });

    // save all
    $('#saveEditBtn').on('click', function(e){

        $('#loaderModal').removeClass('hidden');
            // Check if the request is already in progress
            if ($(this).data('request-in-progress')) {
                return;
            }

            // Set the flag to indicate that the request is in progress
            $(this).data('request-in-progress', true);

        // save all data
        const lessonID = $(this).data('lesson-id')
        const courseID = $(this).data('course-id')
        const syllabusID = $(this).data('syllabus-id')
        const topicID = $(this).data('topic_id')

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        var loopCounter = 0
        for (let i = 0; i < lessonData.length; i++) {
            // console.log(lessonData[i])
            loopCounter++;
            const row_lesson_content_data = {
                'lesson_content_id': lessonData[i]['lesson_content_id'],
                'lesson_id': lessonData[i]['lesson_id'],
                'lesson_content_title': lessonData[i]['lesson_content_title'],
                'lesson_content': lessonData[i]['lesson_content'],
                'lesson_content_order': lessonData[i]['lesson_content_order'],
                'picture': lessonData[i]['picture'],
            }

            if (!/^none\d+$/.test(row_lesson_content_data['lesson_content_id'])) {
                // AJAX for updating the values

                const url = "/instructor/course/content/"+courseID+"/"+syllabusID+"/lesson/"+topicID+"/title/"+ lessonID +"/save"
                // console.log(url)

                $.ajax({
                    type: "POST",
                    url: url,
                    data: row_lesson_content_data,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    async: false,
                    success: function(response) {
                        // Handle success if needed
                        if(i + 1 == lessonData.length){
                            if (response && response.redirect_url ) {
                                //window.location.href = response.redirect_url;
                            }
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                // AJAX for creating new syllabus
                const url = "/instructor/course/content/"+courseID+"/"+syllabusID+"/lesson/"+topicID+"/title/"+ lessonID +"/save_add";
                row_lesson_content_data['lesson_content_id'] = '';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: row_lesson_content_data,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    async: false,
                    success: function(response) {
                        // Handle success if needed
                        if(i + 1 == lessonData.length){
                            if (response && response.redirect_url ) {
                                //window.location.href = response.redirect_url;
                            }
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }

        console.log(loopCounter);
        if(loopCounter == lessonData.length) {
            const url = "/instructor/course/content/"+courseID+"/"+syllabusID+"/lesson/"+topicID+"/title/"+ lessonID +"/generate_pdf";

                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    async: false,
                    success: function(response) {
                        // Handle success if needed
                        
        $('#loaderModal').addClass('hidden');
                        location.reload();
                        
                        console.log(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
        }


    })


    $('.add_lesson_picture_btn').on('click', function(e) {
        e.preventDefault();

        $('#pictureModal').removeClass('hidden');
    })

    $('#closeModal').on('click', function(e) {
        e.preventDefault();

        $('#pictureModal').addClass('hidden');
    })

    $('#cancelUpload').on('click', function(e) {
        e.preventDefault();

        $('#pictureModal').addClass('hidden');
    })

    $('#pictureUploadForm').on('submit', function(e){
        e.preventDefault();

        const courseID = $(this).data('course-id');
        const syllabusID = $(this).data('syllabus-id');
        const topicID = $(this).data('topic_id');
        const lessonID = $(this).data('lesson-id');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var formData = new FormData(this);
        const url = "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/lesson/"+ topicID +"/title/"+ lessonID +"/picture"

        $('#loaderModal').removeClass('hidden');
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: false,
            processData: false,
            success: function(response) {
                // alert('Upload successful!');
                
        $('#loaderModal').addClass('hidden');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('error uploading file')
                console.log('Error:', error);
            }
        });
    })


    $('#saveEstTimeCompletion').on('click', function() {
        var hours = parseInt($('#hours').val()) || 0;
        var minutes = parseInt($('#minutes').val()) || 0; 

        var totalSeconds = (hours * 60 * 60) + (minutes * 60);
    
        var url = baseUrl + '/addCompletionTime'

        var timeCompletion = {
            'secondsTimeCompletion': totalSeconds,

        }

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $('#loaderModal').removeClass('hidden');
        $.ajax({
            type: "POST",
            url: url,
            data: timeCompletion,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log("success");
                
        $('#loaderModal').addClass('hidden');
                location.reload();
                // console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    
    })
});