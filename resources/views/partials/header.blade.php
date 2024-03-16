<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css')}}">
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css')}}">
    <link rel="stylesheet" href="{{ asset('css/discussion.css')}}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    


<title>{{ $title !== "" ? $title : 'Eskwela4EveryJuan'}}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://kit.fontawesome.com/fd323b0f11.js" crossorigin="anonymous"></script> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{asset('js/sidebar.js')}}" defer></script>
    <script src="{{ asset('js/script.js')}}" defer></script>
    <script src="{{ asset('js/modal.js')}}" defer></script>

    <script src="https://cdn.tiny.cloud/1/tu1cijjt9ribhv45aa09w924q0rsgb4nrij1ixwt0tnjt0ql/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/tu1cijjt9ribhv45aa09w924q0rsgb4nrij1ixwt0tnjt0ql/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

    
    @if (isset($scripts))
        @forelse ($scripts as $script)
            
            <script src="{{asset('js/' .  $script)}}" defer></script>
        @empty
            <script src"" defer></script>
        @endforelse
    @endif


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

        .chatbotloader {
        width: 50px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: 
            radial-gradient(farthest-side,#025C26 94%,#0000) top/8px 8px no-repeat,
            conic-gradient(#0000 30%,#025C26);
        -webkit-mask: radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);
        animation: l13 1s infinite linear;
        }
        @keyframes l13{ 
        100%{transform: rotate(1turn)}
        }
            </style>
</head>
<body class="min-h-full bg-mainwhitebg font-poppins">
    <x-message />
