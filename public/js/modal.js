$(document).ready(() => {
    function handleModal() {
        $("#selectTypeParent").on("click", (e) => {
            if (!$(e.target).is("#selectTypeChild")) {
                $("#selectTypeParent").toggleClass("hidden");
            }
        });

        $("#selectTypeChild").on("click", (e) => {
            e.stopPropagation();
        });
    }

    // Now you can call the function directly within your HTML or other JavaScript files
    window.handleModal = handleModal;
});
