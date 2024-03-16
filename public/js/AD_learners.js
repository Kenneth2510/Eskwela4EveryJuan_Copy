$(document).ready(function() {
    // showLearnerTable();    

    // function showLearnerTable() {
    //     // e.preventDefault;
    //     $.ajax({
    //         type: "GET",
    //         url: "/admin/learners",
    //         async: true,
    //         timeout: 60000,

    //         beforeSend: function() {
    
    //         },
    //         success: function(response) {
    //             var learnersResponse = JSON.parse(response);

    //             createLearnerTable(learnersResponse);
    //             // console.log(learnersResponse);
    //         },
    //         error: function(xhr, status, error) {
    //             console.log(status + ':' + error);
    //             console.log("error");
    //         },
    //         complete: function() {

    //         }
    //     });
    // }


    // function createLearnerTable(learnersResponse) {
    //     var learnerTable = ``;

    //     for(var i = 0; i < learnersResponse.length; i++) {
    //         var  learner_id = learnersResponse[i]["learner_id"];
    //         var  learner_fname = learnersResponse[i]["learner_fname"];
    //         var  learner_lname = learnersResponse[i]["learner_lname"];
    //         var  learner_contactno = learnersResponse[i]["learner_contactno"];
    //         var  learner_email = learnersResponse[i]["learner_email"];
    //         var  created_at = learnersResponse[i]["created_at"];
    //         var  business_name = learnersResponse[i]["business_name"];
    //         var  status = learnersResponse[i]["status"];
    
    
    //         if(i !== 0) {
    //             learnerTable += `
    //             <tr class="">
    //                 <td>`+ learner_id +`</td>
    //                 <td>`+ learner_fname +` `+ learner_lname +`</td>
    //                 <td class="w-3/12 py-1 text-lg font-normal">`+ learner_email +`<br>`+ learner_contactno +`</td>
    //                 <td class="w-3/12 py-1 text-lg font-normal">`+ business_name +`</td>
    //                 <td class="w-1/12 py-1 text-lg font-normal">`+ created_at +`</td>
    //                 <td class="w-2/12 py-1 text-lg font-normal">`+ status +`</td>
    //                 <td class="w-1/12"><a href="/admin/view_learner/`+ learner_id +`" class="px-3 py-2 mx-3 text-lg font-medium bg-green-600 rounded-xl hover:bg-green-900 hover:text-white">view</a></td>
    //             </tr>
    //             `
    //         } else {
    //             learnerTable += `
    //             <tr class="">
    //                 <td class="py-1 text-lg font-normal" colspan="7">No learners found.</td>
    //             </tr>
    //             `
    //         }

    //         $('#AD_learners').children().remove();
    //         $('#AD_learners').append(learnerTable);
         
    //     }
    // }


    // $('input[name="filterDate"]').on('change', function(e) {
    //     e.preventDefault;
    //     updateData();
    //   });
    
    //   // Event listener for filterStatus change
    //   $('select[name="filterStatus"]').on('change', function(e) {
    //     e.preventDefault;
    //     updateData();
    //   });
    
    //   // Function to send the AJAX request
    //   function updateData() {
    //     var filterDate = $('input[name="filterDate"]').val();
    //     var filterStatus = $('select[name="filterStatus"]').val();
    
    //     // Perform AJAX request to your server with the selected filterDate and filterStatus
    //     $.ajax({
    //       url: '/admin/learners', // Replace with your API endpoint
    //       method: 'GET',
    //       data: {
    //         filterDate: filterDate,
    //         filterStatus: filterStatus
    //       },
    //       success: function(data) {
    //         // Handle the response data as needed (e.g., update the UI)
    //         console.log(data);
    //       },
    //       error: function(error) {
    //         console.error(error);
    //       }
    //     });
    //   }
})