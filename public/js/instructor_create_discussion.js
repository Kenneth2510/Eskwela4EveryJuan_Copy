$(document).ready(function() {
    
    var baseUrl = window.location.href;
    var selectedThreadContent = 'POST';

    tinymce.init({
        selector: '#threadContent_text',
        width: '100%', // Set TinyMCE width to 100%
        height: $('#threadContent_text').css('height'), // Set TinyMCE height dynamically
        setup: function (editor) {
            editor.on('ResizeEditor', function (e) {
                // Adjust the width dynamically when the editor is resized
                editor.theme.resizeTo($('#threadContent_text').width(), null);
            });
        },
    });


    $("#uploadPhoto").change(function () {
        previewPhoto(this);
    });

    $("#removePhotoBtn").click(function () {
        removePhoto();
    });

    $("#changePhotoBtn").click(function () {
        changePhoto();
    });

    $("#threadContent_url").on("input", function () {
        previewUrl(this.value);
    });

    $("#removeUrlBtn").click(function () {
        removeUrl();
    });

    $("#changeUrlBtn").click(function () {
        changeUrl();
    });

    function previewPhoto(input) {
        const previewContainer = $("#photoPreviewContainer");
        const photoPreview = $("#photoPreview");
        const uploadLabel = $("#uploadLabel");

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                photoPreview.attr("src", e.target.result);
                previewContainer.show();
                uploadLabel.hide();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePhoto() {
        const uploadPhotoInput = $("#uploadPhoto");
        const previewContainer = $("#photoPreviewContainer");
        const uploadLabel = $("#uploadLabel");

        uploadPhotoInput.val(''); // Clear the file input
        previewContainer.hide(); // Hide the preview container
        uploadLabel.show(); // Show the upload label
    }

    function changePhoto() {
        const uploadPhotoInput = $("#uploadPhoto");
        uploadPhotoInput.val(''); // Clear the file input
        uploadPhotoInput.click(); // Trigger the file input click to open the file dialog
    }


    function previewUrl(url) {
        const urlPreviewContainer = $("#urlPreviewContainer");
        const urlLink = $("#urlLink");

        // Clear previous preview
        urlPreviewContainer.hide();
        urlLink.attr('href', '#').hide();

        // Check if the input is a valid URL
        const urlRegex = /^(http|https):\/\/[^ "]+$/;
        if (url.match(urlRegex)) {
            urlLink.attr('href', url).show();

            // Show the preview container
            urlPreviewContainer.show();
        }
    }

    function removeUrl() {
        const urlTextArea = $("#threadContent_url");
        const urlPreviewContainer = $("#urlPreviewContainer");

        urlTextArea.val(''); // Clear the URL textarea
        urlPreviewContainer.hide(); // Hide the preview container
    }

    function changeUrl() {
        const urlTextArea = $("#threadContent_url");
        urlTextArea.val(''); // Clear the URL textarea
        urlTextArea.focus(); // Set focus to the URL textarea
    }

    var textContentTemplate = `
    <div class="" id="textContent">
        <textarea name="" class="p-3 w-full h-[300px] min-h-[300px] border-2 border-darthmouthgreen border-opacity-60 rounded-lg text-lg" id="threadContent_text" placeholder="text"></textarea>
    </div>
    `;

    var photoContentTemplate = `
    <div class="mt-3 w-full h-[500px] border-2 p-10 border-darthmouthgreen border-opacity-60 rounded-lg text-center flex flex-col items-center justify-center" id="photoContent" style="position: relative;">
        <label for="uploadPhoto" class="text-lg px-5 py-3 m-10 border-2 border-darthmouthgreen border-opacity-60 rounded-2xl hover:bg-darthmouthgreen hover:text-white text-darthmouthgreen" id="uploadLabel">Upload Photo</label>
        <input type="file" id="uploadPhoto" accept="image/*" style="display: none;">
        
        <div id="photoPreviewContainer" class="mt-2" style="display: none;">
            <img id="photoPreview" class="max-w-11/12 h-auto max-h-[400px] rounded-lg" alt="Photo Preview">
            <div class="mt-2">
                <button id="removePhotoBtn" class="bg-red-500 hover:bg-red-900 text-white px-3 py-2 rounded-md mr-2">Remove</button>
                <button id="changePhotoBtn" class="bg-darthmouthgreen hover:bg-green-950 text-white px-3 py-2 rounded-md">Change</button>
            </div>
        </div>
    </div>
    `;

    var urlContentTemplate = `
    <div class="mt-5 w-full h-[300px] min-h-[300px] rounded-lg text-center border-2 border-darthmouthgreen border-opacity-60 flex flex-col items-center" id="urlContent" style="position: relative;">
        <textarea name="" class="mt-5 p-3 w-4/5 min-h-[100px] h-[100px]  rounded-lg text-lg" id="threadContent_url" placeholder="Url"></textarea>
        
        <div id="urlPreviewContainer" class="mt-10" style="display: none;">
            <div class="max-w-full h-auto rounded-lg">
                <a id="urlLink" href="#" class="my-3 px-5 py-3 rounded-xl border-2 border-darthmouthgreen hover:bg-darthmouthgreen hover:text-white text-darthmouthgreen" target="_blank">Visit link</a>
                <div class="mt-5">
                    <button id="removeUrlBtn" class="bg-red-500 hover:bg-red-900 text-white px-3 py-2 rounded-md mr-2">Remove</button>
                    <button id="changeUrlBtn" class="bg-darthmouthgreen hover:bg-green-950 text-white px-3 py-2 rounded-md">Change</button>
                </div>
            </div>
        </div>
    </div>
    `;

    $('#textCategoryBtn').on('click', function(e) {
        e.preventDefault();
        $('.errorMsg').remove();
        $('#photoCategoryBtn').removeClass('discussionBtn_selected');
        $('#urlCategoryBtn').removeClass('discussionBtn_selected');
        $('#textCategoryBtn').addClass('discussionBtn_selected');
    
        $('#threadContent').empty();
        $('#threadContent').append(textContentTemplate);
    
        selectedThreadContent = 'POST';
        // Destroy existing TinyMCE instance
        tinymce.get('threadContent_text').remove();
    
        // Reinitialize TinyMCE for the text area
        tinymce.init({
            selector: '#threadContent_text',
            width: '100%',
            height: $('#threadContent_text').css('height'),
            setup: function (editor) {
                editor.on('ResizeEditor', function (e) {
                    editor.theme.resizeTo($('#threadContent_text').width(), null);
                });
            },
        });
    });
    
    


    $('#photoCategoryBtn').on('click', function(e) {
        e.preventDefault();
        $('.errorMsg').remove();
        $('#textCategoryBtn').removeClass('discussionBtn_selected')
        $('#urlCategoryBtn').removeClass('discussionBtn_selected')
        $('#photoCategoryBtn').addClass('discussionBtn_selected')

        $('#threadContent').empty();
        $('#threadContent').append(photoContentTemplate);
            
        selectedThreadContent = 'PHOTO';

        $("#uploadPhoto").change(function () {
            previewPhoto(this);
        });

        $("#removePhotoBtn").click(function () {
            removePhoto();
        });

        $("#changePhotoBtn").click(function () {
            changePhoto();
        });
    })

    $('#urlCategoryBtn').on('click', function(e) {
        e.preventDefault();
        $('.errorMsg').remove();
        $('#textCategoryBtn').removeClass('discussionBtn_selected')
        $('#photoCategoryBtn').removeClass('discussionBtn_selected')
        $('#urlCategoryBtn').addClass('discussionBtn_selected')

        $('#threadContent').empty();
        $('#threadContent').append(urlContentTemplate);
           
        selectedThreadContent = 'URL';

        $("#threadContent_url").on("input", function () {
            previewUrl(this.value);
        });

        $("#removeUrlBtn").click(function () {
            removeUrl();
        });

        $("#changeUrlBtn").click(function () {
            changeUrl();
        });
    })


    $('#postBtn').on('click', function (e) {
        var thread_title = $('#threadTitle_text').val();
        var thread_content = '';
        var content = '';
        var isValid = true;
        
        $('.errorMsg').remove();

        if (selectedThreadContent === 'POST') {
            content = tinyMCE.get("threadContent_text").getContent();
            // Additional validation for the post content if needed
            if (!content.trim()) {
                isValid = false;

                var errorThreadContent = `
                <span class="errorMsg text-lg px-3 text-red-500">*Please enter a thread content*</span>
                `;
    
                $('#threadContent').prepend(errorThreadContent)
            }
        } else if (selectedThreadContent === 'PHOTO') {
            var input = $('#uploadPhoto')[0];
            var file = input.files[0];
            if (!file) {
                isValid = false;
                var errorThreadContent = `
                <span class="errorMsg text-lg px-3 text-red-500">*Please enter a thread content*</span>
                `;
    
                $('#threadContent').prepend(errorThreadContent)
            } else {
                content = file.name;
            }
        } else {
            content = $('#threadContent_url').val();
            // Additional validation for the URL if needed
            if (!content.trim()) {
                isValid = false;
                var errorThreadContent = `
                <span class="errorMsg text-lg px-3 text-red-500">*Please enter a thread content*</span>
                `;
    
                $('#threadContent').prepend(errorThreadContent)
            }
        }
    
        if (!thread_title.trim()) {
            isValid = false;

            const errorThreadTitle = `
            <span class="errorMsg text-lg px-3 text-red-500">*Please enter a thread title*</span>
            `;

            $('#threadTitle_lbl').after(errorThreadTitle)
        }
    
        if (isValid) {
            thread_content = content;
            var community_id = $('#selectCommunity').val();

            $('#loaderModal').removeClass('hidden');

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log(`type: ${selectedThreadContent}`);
            console.log(`thread_title: ${thread_title}`);
            console.log(`thread_content: ${thread_content}`);

            if(selectedThreadContent === 'PHOTO') {
                var formData = new FormData();
                var input = $('#uploadPhoto')[0];
                var file = input.files[0];
                
                
                    formData.append('photo', file);
                    formData.append('thread_type', selectedThreadContent);
                    formData.append('thread_title', thread_title);
                    formData.append('community_id', community_id);
                    formData.append('thread_content', thread_content);
            
                    var url = `${baseUrl}/post-photo`;
            
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            // Hide loader on success
                        $('#loaderModal').addClass('hidden');

                            // Handle success if needed
                            if (response && response.redirect_url) {
                                // Show success indicator (you can customize this based on your needs)
                                $('#successModal').removeClass('hidden');

                                // Redirect to the specified URL after a brief delay
                                setTimeout(function () {
                                    window.location.href = response.redirect_url;
                                }, 1000); // Delay for 2 seconds (adjust as needed)
                            }
                        },
                        error: function(error) {
                            // Hide loader on error
                            $('#loaderModal').addClass('hidden');
                            $('#errorModal').removeClass('hidden');
                            setTimeout(function () {
                                $('#errorModal').addClass('hidden');
                            }, 1000);
                            
                            console.log(error);
                        }
                    });
            
            } else {

                var url = `${baseUrl}/post`;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        thread_type: selectedThreadContent,
                        thread_title: thread_title,
                        thread_content: thread_content,
                        community_id: community_id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Hide loader on success
                        $('#loaderModal').addClass('hidden');

                        // Handle success if needed
                        if (response && response.redirect_url) {
                            // Show success indicator (you can customize this based on your needs)
                            $('#successModal').removeClass('hidden');

                            // Redirect to the specified URL after a brief delay
                            setTimeout(function () {
                                window.location.href = response.redirect_url;
                            }, 1000); // Delay for 2 seconds (adjust as needed)
                        }
                    },
                    error: function(error) {
                        // Hide loader on error
                        $('#loaderModal').addClass('hidden');
                        $('#errorModal').removeClass('hidden');
                        console.log(error);
                    }
                });
            }

        }
    });
    

});