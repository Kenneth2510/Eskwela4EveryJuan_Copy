<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css')}}">
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css')}}">
    <link rel="stylesheet" href="{{ asset('css/discussion.css')}}">

    <script src="https://kit.fontawesome.com/fd323b0f11.js" crossorigin="anonymous"></script> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script src="https://cdn.tiny.cloud/1/tu1cijjt9ribhv45aa09w924q0rsgb4nrij1ixwt0tnjt0ql/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

    <style>
        /* width */
        ::-webkit-scrollbar {
          width: 15px;
        }
      
        /* Track */
        ::-webkit-scrollbar-track {
          box-shadow: inset 0 0 5px grey;
          border-radius: 10px;
        }
      
        /* Handle */
        ::-webkit-scrollbar-thumb {
          background: #00693e; /* Dartmouth Green */
          border-radius: 10px;
        }
      
        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
          background: #004026; /* Darker shade for hover */
        }


                /* Set initial styles for sidebar */
        #sidebar_full {
            width: 23%;
        }

        #sidebar_half {
            width: 8%;
        }

        /* Add transition for smooth width change */
        /* #sidebar_full, #sidebar_half {
            transition: width 2s ease;
        } */

        .selectedMessage {
            border-left: 4px solid #025C26; /* Green left border */
            background-color: #f3f4f6; /* Optional: background color */
        }

        .notRead {
            background-color: #f8d7da; /* Light red background color */
        }


      </style>
</head>
<body class="min-h-full bg-mainwhitebg font-poppins">
    <x-message />



<section class="flex flex-row w-full h-screen text-sm main-container bg-mainwhitebg md:text-base">

    <div style="font-size: 24px; margin:auto; color: #025c26; text-align: center;">
        <i class="fa-regular fa-circle-xmark text-6xl"></i>
        <p class="text-lg mt-5">Page Not Found</p>
        <button onclick="goBack()" class="mt-5 py-3 px-5 bg-darthmouthgreen text-white rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">Go Back</button>
    </div>
    
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    

</section>

</body>

</html>
