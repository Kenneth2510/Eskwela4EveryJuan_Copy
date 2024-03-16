<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <style>
        /* Your converted Tailwind CSS styles here */
    </style>
</head>
<body>

    <section class="main-container">
 
        <section class="main-section">
            <div class="rounded-lg shadow-lg b" style="padding: 3px; margin-bottom: 30px;">
                <div id="upper_container" style="display: flex;">
                    <div id="upper_left_container" style="flex: 0 0 25%; height: 100%; padding: 10px; margin: 5px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);">
                        <div style="position: relative; display: flex; flex-direction: column; align-items: center;">
                            <img id="profile_picture" src="{{ asset('storage/' . $learner->profile_picture) }}" alt="Profile Picture">
                        </div>

                        <div id="name_area" style="margin-top: 20px;">
                            <h1 style="font-size: 1.5rem; font-weight: bold; text-align: center;">{{$learner->learner_fname}} {{$learner->learner_lname}}</h1>
                        </div>

                        <div id="account_status_area" style="margin-top: 10px; text-align: center;">
                            <h1 style="font-size: 1.25rem;">LEARNER</h1>
                            @if ($learner->status == 'Approved')
                                <div style="padding: 5px 10px; background-color: #00693e; color: #fff; border-radius: 0.5rem;">Approved</div>
                            @elseif ($learner->status == 'Pending')
                                <div style="padding: 5px 10px; background-color: #ffbb00; color: #fff; border-radius: 0.5rem;">Pending</div>
                            @else
                                <div style="padding: 5px 10px; background-color: #ff0000; color: #fff; border-radius: 0.5rem;">Rejected</div>
                            @endif
                        </div>

                        <div id="email_area" style="margin-top: 20px; text-align: center;">
                            <h1 style="font-size: 1.25rem;">Email</h1>
                            <h2 style="font-size: 1rem;">{{$learner->learner_email}}</h2>
                        </div>
                    </div>

                    <div id="upper_right_container" style="flex: 0 0 75%; height: 100%; padding: 10px; margin: 5px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);">
                        <div style="padding: 10px 20px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);">
                            <h1 style="font-size: 2rem; font-weight: bold; color: #00693e;">User Details</h1>
                            <hr style="margin: 20px 0; border-top: 2px solid #ccc;">
                            <div id="userInfo" style="display: flex; margin-top: 20px;">
                                <div id="userInfo_left" style="flex: 0 0 50%; margin-right: 10px;">
                                    <div id="firstNameArea" style="margin-top: 10px;">
                                        <label for="learner_fname">First Name</label><br>
                                        <input type="text" name="learner_fname" id="learner_fname" value="{{$learner->learner_fname}}" disabled>
                                        <span id="firstNameError" style="color: #ff0000;"></span>
                                    </div>
                                    <div id="bdayArea" style="margin-top: 10px;">
                                        <label for="learner_bday ">Birthday</label><br>
                                        <input type="date" name="learner_bday" id="learner_bday" value="{{$learner->learner_bday}}" disabled>
                                        <span id="bdayError" style="color: #ff0000;"></span>
                                    </div>
                                    <div id="contactArea" style="margin-top: 10px;">
                                        <label for="learner_contactno">Contact Number</label><br>
                                        <input type="text" maxlength="11" name="learner_contactno" id="learner_contactno" value="{{$learner->learner_contactno}}" disabled>
                                    </div>
                                </div>
                                <div id="userInfo_right" style="flex: 0 0 50%; margin-left: 10px;">
                                    <div id="lastNameArea" style="margin-top: 10px;">
                                        <label for="learner_lname">Last Name</label><br>
                                        <input type="text" name="learner_lname" id="learner_lname" value="{{$learner->learner_lname}}" disabled>
                                        <span id="lastNameError" style="color: #ff0000;"></span>
                                    </div>
                                    <div id="genderArea" style="margin-top: 10px;">
                                        <label for="learner_gender">Gender</label><br>
                                        <select name="learner_gender" id="learner_gender" disabled>
                                            <option value="" {{$learner->learner_gender == "" ? 'selected' : ''}}>-- select an option --</option>
                                            <option value="Male" {{$learner->learner_gender == "Male" ? 'selected' : ''}}>Male</option>
                                            <option value="Female" {{$learner->learner_gender == "Female" ? 'selected' : ''}}>Female</option>
                                            <option value="Others" {{$learner->learner_gender == "Others" ? 'selected' : ''}}>Preferred not to say</option>
                                        </select>
                                        <span id="genderError" style="color: #ff0000;"></span>
                                    </div>
                                    <div id="emailArea" style="margin-top: 10px;">
                                        <label for="learner_email">Email Address</label><br>
                                        <input type="email" name="learner_email" id="learner_email" value="{{$learner->learner_email}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 10px 20px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08); margin-top: 30px;">
                            <h1 style="font-size: 2rem; font-weight: bold; color: #00693e;">Business Details</h1>
                            <hr style="margin: 20px 0; border-top: 2px solid #ccc;">
                            <div style="margin-top: 20px;">
                                <label for="business_name">Business Name</label><br>
                                <input type="text" name="business_name" id="business_name" value="{{$business->business_name}}" disabled>
                                <span style="color: #ff0000;"></span>
                            </div>
                            <div style="margin-top: 20px;">
                                <label for="business_address">Business Address</label><br>
                                <input type="text" name="business_address" id="business_address" value="{{$business->business_address}}" disabled>
                                <span style="color: #ff0000;"></span>
                            </div>
                            <div style="margin-top: 20px;">
                                <label for="business_owner_name">Business Owner Name</label><br>
                                <input type="text" name="business_owner_name" id="business_owner_name" value="{{$business->business_owner_name}}" disabled>
                                <span style="color: #ff0000;"></span>
                            </div>
                            <div style="margin-top: 20px;">
                                <label for="bplo_account_number">BPLO Account Number</label><br>
                                <input type="text" maxlength="13" name="bplo_account_number" id="bplo_account_number" value="{{$business->bplo_account_number}}" disabled>
                            </div>
                            <div style="margin-top: 20px;">
                                <label for="business_category">Business Category</label><br>
                                <select name="business_category" id="business_category" disabled>
                                    <option value="" {{$business->business_category == "" ? 'selected' : ''}}>-- select an option --</option>
                                    <option value="Micro" {{$business->business_category == "Micro" ? 'selected' : ''}}>Micro</option>
                                    <option value="Small" {{$business->business_category == "Small" ? 'selected' : ''}}>Small</option>
                                    <option value="Medium" {{$business->business_category == "Medium" ? 'selected' : ''}}>Medium</option>
                                </select>
                                <span style="color: #ff0000;"></span>
                            </div>
                            <div style="margin-top: 20px;">
                                <label for="business_classification">Business Classification</label><br>
                                <select name="business_classification" id="business_classification" disabled>
                                    <option value="" {{$business->business_classification == "" ? 'selected' : ''}}>-- select an option --</option>
                                    <option value="Retail" {{$business->business_classification == "Retail" ? 'selected' : ''}}>Retail</option>
                                    <option value="Wholesale" {{$business->business_classification == "Wholesale" ? 'selected' : ''}}>Wholesale</option>
                                    <option value="Financial Services" {{$business->business_classification == "Financial Services" ? 'selected' : ''}}>Financial Services</option>
                                    <option value="Real Estate" {{$business->business_classification == "Real Estate" ? 'selected' : ''}}>Real Estate</option>
                                    <option value="Transportation and Logistics" {{$business->business_classification == "Transportation and Logistics" ? 'selected' : ''}}>Transportation and Logistics</option>
                                    <option value="Technology" {{$business->business_classification == "Technology" ? 'selected' : ''}}>Technology</option>
                                    <option value="Healthcare" {{$business->business_classification == "Healthcare" ? 'selected' : ''}}>Healthcare</option>
                                    <option value="Education and Training" {{$business->business_classification == "Education and Training" ? 'selected' : ''}}>Education and Training</option>
                                    <option value="Entertainment and Media" {{$business->business_classification == "Entertainment and Media" ? 'selected' : ''}}>Entertainment and Media</option>
                                    <option value="Hospitality and Tourism" {{$business->business_classification == "Hospitality and Tourism" ? 'selected' : ''}}>Hospitality and Tourism</option>
                                </select>
                                <span style="color: #ff0000;"></span>
                            </div>
                            <div style="margin-top: 20px;">
                                <label for="business_description">Business Description</label><br>
                                <textarea name="business_description" id="business_description" rows="5" disabled>{{$business->business_description}}</textarea>
                                <span style="color: #ff0000;"></span>
                            </div>
                        </div>

                        <div style="padding: 10px 20px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08); margin-top: 30px;">
                            <h1 style="font-size: 2rem; font-weight: bold; color: #00693e;">Courses Progress</h1>
                            <hr style="margin: 20px 0; border-top: 2px solid #ccc;">
                            <table style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Status</th>
                                        <th>Start Period</th>
                                        <th>Finish Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 10px;">HTML</td>
                                        <td style="padding: 10px;">IN PROGRESS</td>
                                        <td style="padding: 10px;">02/14/2024</td>
                                        <td style="padding: 10px;">02/14/2024</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </section>

</body>
</html>
