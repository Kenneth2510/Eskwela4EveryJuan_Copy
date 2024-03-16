$(document).ready(() => {
    $("#hamburgerSettings").on("click", (event) => {
        event.preventDefault();

        $("li h1").toggleClass("md:block");
        $("button h1").toggleClass("hidden");

        $("#insSideCont").toggleClass("w-full md:w-1/3 lg:w-2/12 w-1/4");
        // $("#insDashCont").toggleClass("md:w-3/4 lg:w-9/12");
    });


        $('#editBtn').on('click', function(e) {
            e.preventDefault();
            // alert('test');
            $('#instructor_fname').prop("disabled", false).focus();
            $('#instructor_lname').prop("disabled", false);
            $('#instructor_bday').prop("disabled", false);
            $('#instructor_gender').prop("disabled", false);
            // $('#instructor_email').prop("disabled", false);        
            $('#instructor_contactno').prop("disabled", false);          
            $('#password').prop("disabled", false);
            $('#password').prop("readonly", true);

            
            $('#instructor_fname').prop("required", true);
            $('#instructor_lname').prop("required", true);
            $('#instructor_bday').prop("required", true);
            $('#instructor_gender').prop("required", true);
            $('#instructor_contactno').prop("required", true);
            $('#password_confirmation').prop("required", true);
                    
            // $('#instructor_credentials').prop("disabled", false);

            $('#pass_confirm').removeClass('hidden');

            $('#cancelBtn').removeClass('hidden');
            $('#updateBtn').removeClass('hidden');
            $('#editBtn').addClass('hidden');
        })

        $('#updatePictureBtn').click(function () {
            $('#profilePicturePopup').removeClass('hidden');
        });

        $('#closePopup').click(function () {
            $('#profilePicturePopup').addClass('hidden');
        });

});
