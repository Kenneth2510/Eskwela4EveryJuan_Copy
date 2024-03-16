<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <style>
        /* Reset default margin and padding */
        body, h1, h2, h3, h4, h5, h6, p, table, th, td {
            margin: 0;
            padding: 0;
        }

        /* Set font family */
        body {
            font-family: Arial, sans-serif;
        }

        /* Set basic styling for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Set styling for the headings */
        h1, h2, h3, h4, h5, h6 {
            margin-bottom: 10px;
        }

        /* Set styling for the container */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Set styling for the header */
        .header {
            background-color: #f7f7f7;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Set styling for the title */
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #025c26;
        }

        /* Set styling for the table headings */
        .table-head {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        /* Set styling for the table rows */
        .table-row {
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Eskwela4EveryJuan</h1>
            <h2>Course Data</h2>
        </div>

        <div class="container">
            <div class="header">
                <h1 class="title">{{ $learnerData->learner_fname }} {{ $learnerData->learner_lname }}</h1>
                <h2>Learner Details</h2>
            </div>
    
            <div>
                <h3>Personal Information</h3>
                <p><strong>Status:</strong> {{ $learnerData->status }}</p>
                <p><strong>Birthday:</strong> {{ $learnerData->learner_bday }}</p>
                <p><strong>Gender:</strong> {{ $learnerData->learner_gender }}</p>
            </div>
    
            <div>
                <h3>Business Information</h3>
                <p><strong>Name:</strong> {{ $businessData->business_name }}</p>
                <p><strong>Address:</strong> {{ $businessData->business_address }}</p>
                <p><strong>Owner Name:</strong> {{ $businessData->business_owner_name }}</p>
                <p><strong>Category:</strong> {{ $businessData->business_category }}</p>
                <p><strong>Classification:</strong> {{ $businessData->business_classification }}</p>
                <p><strong>Description:</strong> {{ $businessData->business_description }}</p>
            </div>
        </div>

    </div>
</body>
</html>