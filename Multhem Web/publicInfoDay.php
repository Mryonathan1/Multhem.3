<?php
    include "connect.php";
    include "email.php";
    
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $email = $_POST['email'];
        $job = $_POST['job_title'];
        $orgSize = $_POST['org_size'];
        $orgPlace = $_POST['org_place'];
        $orgDesc = $_POST['org_desc'];

        $msg1 = "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                </head>
                <body>
                    <p>
                        Dear $fname,
                        <br><br>
                        Thank you for registering! 
                        <br><br>
                        Your registration has been successfully submitted. 
                        We appreciate your interest and look forward to seeing you at the event.
                        If you have any questions or need assistance, please don't hesitate to contact us at info@multhem.eu. 
                        <br><br>
                        Best regards,
                        <br><br>
                        Multhem Team.
                    </p>
                </body>
                </html>";

        $msg2 = "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                </head>
                <body>
                    <p>
                        Dear $fname,
                        <br><br>
                        We have detected an attempt to register using this email address, 
                        which appears to have been previously registered.If you have any questions or need assistance, 
                        please don't hesitate to contact us at info@multhem.eu. 
                        <br><br>
                        Best regards,
                        <br><br>
                        Multhem Team.
                    </p>
                </body>
                </html>";


        $query = "SELECT email FROM publicInfoDay2024 WHERE email ='$email'"; 
        $result = mysqli_query($dbconnect, $query); 

        $id_query = "SELECT MAX(publicInfoDay24_ID) AS max_id FROM publicInfoDay2024;";
        $id_result = mysqli_query($dbconnect, $id_query);
        $row = mysqli_fetch_assoc($id_result);
        $max_id = $row['max_id'];
        $id_new = $max_id + 1;

        
        if(mysqli_num_rows($result) > 0){
          
          sendEmail($email,$msg2);
          
          header('location:confirmation2.html');

        } else {
          $sql = "INSERT INTO `publicInfoDay2024` (`publicInfoDay24_ID`, `firstName`, `lastName`, `email`, `jobTitle`, `OrgSize`, `OrgLocation`, `OrgCategory`) 
          VALUES ('$id_new', '$fname', '$lname', '$email', '$job', '$orgSize', '$orgPlace', '$orgDesc');";

          if (mysqli_query($dbconnect, $sql)) {

            sendEmail($email, $msg1);

            header('location:confirmation.html');
            
          } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($dbconnect);
          }
        }
        
    }
    mysqli_close($dbconnect);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MULTHEM - Public Info Day</title>
    <link rel="stylesheet" href="publicInfoDay.css">
    <link rel="icon" type="image/x-icon" href="images/favicon-16x16.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  </head>



  <body>
    <!-- HEADER AND NAVBAR -->
  <section class="Navigation Bar">
    <nav class="navbar navbar-expand-lg bg-dark bg-transparent ">
        <div class="container-fluid">
          <div class="logo">
            <a class="navbar-brand" href="index.html">
            <img src="images/Multhem_Logo_1_transbg.png" alt="Logo" style="width: 200px;"></a>
          </div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarText">
            <div class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3 collapse-text ">
                  <a class="nav-link" href="index.html">HOME</a>
                  <a class="nav-link" href="about.html">ABOUT US</a>
                  <a class="nav-link" href="partners.html">PARTNERS</a>
                  <a class="nav-link" href="resources.html">RESOURCES</a>
                  <a class="nav-link" href="report.html">PUBLISHED REPORTS</a>
                  <a class="nav-link" href="news.html">NEWS</a>
                  <a class="nav-link" href="contact.html">CONTACT US</a>
                  <a class="nav-link" href="publicInfoDay.php">PUBLIC INFO DAY</a>
            </div>
        </div>
      </nav>
</section>


  <!-- Public Info Day -->
  <section class="container" style="padding-top: 40px;padding-bottom: 40px;">          
    <img src="images/PublicInfoDay.jpg" class="img-rounded mx-auto d-block" alt="Public Info Day Poster" width="90%" height="90%"> 
    <p style="width: 90%; margin-top: 20px;" class="mx-auto d-block text-center">
    The MULTHEM Public Info Day on 22 June 2024 from 10:00 to 11:30 am CET provides an opportunity for members of the public, 
    including researchers and those from the industry to find out more about the MULTHEM project. As a Horizon Europe funded project, 
    MULTHEM aims to develop new metal-polymer multimaterials with the use of Additive Manufacturing to produce lightweight components with good thermal conductivity. 
    Join us to know more about the project and applications for metal-polymer multimaterials.
    This will be held online via Teams and will be recorded. Please get in touch with us at info@multhem.eu.
    </p>
  </section>

  <hr size="4" style="width: 80%;" class="rounded mx-auto d-block">



  <!-- Registration form -->
  <section class="container">
    <div class="pb-4">
      <h1 class="text-center">Registration</h1>
    </div>
    <form id="registration" name="registration" method="POST" class="mx-auto" action="publicInfoDay.php" onsubmit="return isValid()">
      <div class="mb-4 col mx-auto d-block">
        <input type="text" maxlength="50" class="form-control form-control-lg" id="first_name" name="first_name" placeholder="First Name"  >
        <span id="first_name_error" class="error"></span>
      </div>
      <div class="mb-4 col mx-auto d-block">
        <input type="text" maxlength="50" class="form-control form-control-lg" id="last_name" name="last_name" placeholder="Last Name"  >
        <span id="last_name_error" class="error"></span>
      </div>
      <div class="mb-4 col mx-auto d-block">
        <input type="email" maxlength="100" class="form-control form-control-lg" id="email" name="email" placeholder="Email"  >
        <span id="email_error" class="error"></span>  
      </div>
      <div class="mb-4 col mx-auto d-block">
        <input type="text" maxlength="50" class="form-control form-control-lg" id="job_title" name="job_title" placeholder="Job Title"  >
        <span id="job_title_error" class="error"></span>
      </div>
      <div class="mb-4 col mx-auto d-block">
        <select id="org_size" name="org_size" class="form-select form-select-lg">
          <option value="" selected>Organisation Size – Number of Employees</option>
          <option value="1 to 10">1 to 10</option>
          <option value="11 to 50">11 to 50</option>
          <option value="51 to 250">51 to 250</option>
          <option value="250+">250+</option>
        </select>
        <span id="org_size_error" class="error"></span>
      </div>
      <div class="mb-4 col mx-auto d-block">
        <select id="org_place" name="org_place" class="form-select form-select-lg">
          <option value="" selected>Where is your organisation based?</option>
        </select>
        <span id="org_place_error" class="error"></span>
      </div>
      <div class="mb-4 col mx-auto d-block">
        <select id="org_desc" name="org_desc" class="form-select form-select-lg">
          <option value="" selected>What category best describes your organisation?</option>
          <option value="Industry">Industry</option>
          <option value="Academia">Academia</option>
          <option value="Research Technology Organization">Research Technology Organization</option>
          <option value="Government/NGO">Government/NGO</option>
          <option value="Other">Other</option>
        </select>
        <span id="org_desc_error" class="error"></span>
      </div>
      <div class="mb-4 col mx-auto d-block">
        <input type="checkbox" id="agreement" name="agreement" value="agreement" required >
        <label for="agreement"> * I understand that this event will be recorded</label>
      </div>
      <div class="d-grid col mx-auto">
        <button type="submit" value="submit" class="btn btn-lg">Register</button>
      </div>
    </form>
  </section>
  
  <!-- Loading Section -->
  <section class="confirmation-off" id="confirmation">
      <div class="popup-background">
        <div class="popup">
          <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
              <div class="card text-center">
                <div class="card-body m-3">
                  <img src="images/LoadingGIF.gif" alt="Tick icon" style="width: 100px;" class="mb-2">
                  <h3 class="card-title">Submitting...</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
  
  <!-- FOOTER -->

  <footer class="footer">
    <div class="container">
      <div class="row mt-4 ">
        <div class="col-md-3 col-lg-2 mx-auto mt-3 mb-4 text-left">
          <h5 class="text-uppercase mb-4 font-weight-bold">MULTHEM</h5>
          <p> MULTHEM's aim is to develop reliable, cost-effective AM CFC components 
              with enhanced thermal conductivity for lighter, stronger battery and motor housings.
          </p>
        </div>
  
        <div class="col-md-3 col-lg-1 mx-auto mt-3 mb-4 Links text-left ">
          <h5 class="text-uppercase mb-4 font-weight-bold">Links</h5>
          <p>
            <a href="index.html">Home</a>
          </p>
          <p>
            <a href="about.html">About us</a>
          </p>
          <p>
            <a href="partners.html">Partners</a>
          </p>
          <p>
            <a href="resources.html">Resources</a>
          </p>
          <p>
            <a href="report.html">Reports</a>
          </p>
          <p>
            <a href="news.html">News</a>
          </p>
          <p>
            <a href="contact.html"> Contact us</a>
          </p>
        </div>
  
        <div class="col-md-3 col-lg-2 mx-auto mt-3 mb-4 text-left Contact">
          <h5 class="text-uppercase mb-4 font-weight-bold">Contact</h5>
          <p>
            <i class="bi bi-geo-alt-fill"></i>
            Centro Tecnológico Metalmecánco y del Transporte, Parque Empresarial Santana. Avda. Primero de Mayo, s/n, 23700 Linares, Jaén, España.
          </p>
          <p>
            <i class="bi bi-envelope-fill"></i>
            <a href="mailto:info@multhem.eu">info@multhem.eu</a>
          </p>
        </div>
  
        <div class="col-md-3 col-lg-2 mx-auto mt-3 mb-4 Social">
          <h5 class="text-uppercase mb-4 font-weight-bold"> Social Networks</h5>
          <p>
            <a href= "https://www.linkedin.com/company/multhem/" target="blank" title="Linkedin">
            <i class="bi bi-linkedin"></i></a>
          </p>
         
        </div>
      </div>
    </div>
  
    <div class="text-center p-4 footer-image" style="background-color: #014d5b;">
      <img src="images/eu_flag.jpg"style="width: 120px; height: auto;" alt="Eu Logo"></img>
      <p style="color: white; margin: 20px;">
        This project has received funding from the European Union’s Horizon Europe Research & Innovation programme
        <br> 2021 -2027 under grant agreement number:101091495
      </p>
    </div>
    
      
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script>
  function validateForm() {
    var fname1 = document.getElementById('first_name').value;
    var lname1 = document.getElementById('last_name').value;
    var email1 = document.getElementById('email').value;
    var job1 = document.getElementById('job_title').value;
    var orgSize = document.getElementById('org_size').value;
    var orgPlace = document.getElementById('org_place').value;
    var orgDesc = document.getElementById('org_desc').value;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var regex = /^[a-zA-Z ]{2,30}$/;
    
    var fname = fname1.trim();
    var lname = lname1.trim();
    var email = email1.trim();
    var job   = job1.trim();

    document.getElementById('first_name_error').innerText = '';
    document.getElementById('last_name_error').innerText = '';
    document.getElementById('email_error').innerText = '';
    document.getElementById('job_title_error').innerText = '';
    document.getElementById('org_size_error').innerText = '';
    document.getElementById('org_place_error').innerText = '';
    document.getElementById('org_desc_error').innerText = '';

    var isValid = true;

    
    if (fname === '') {
      document.getElementById('first_name_error').innerText = 'Please enter your first name.';
      isValid = false;
    } else if (!regex.test(fname)) {
      document.getElementById('first_name_error').innerText = 'Please enter a valid name.';
      isValid = false;
    }
    
    if (lname === '') {
      document.getElementById('last_name_error').innerText = 'Please enter your last name.';
      isValid = false;
    } else if (!regex.test(lname)) {
      document.getElementById('last_name_error').innerText = 'Please enter a valid last name.';
      isValid = false;
    }

    if (email === '') {
      document.getElementById('email_error').innerText = 'Please enter an email.';
      isValid = false;
    } else if (!emailRegex.test(email)) {
      document.getElementById('email_error').innerText = 'Please enter a valid email address.';
      isValid = false;
    }

    if (job === '') {
      document.getElementById('job_title_error').innerText = 'Please enter your job title.';
      isValid = false;
    } else if (!regex.test(job)) {
      document.getElementById('job_title_error').innerText = 'Please enter a valid job title.';
      isValid = false;
    }

    if (orgSize === '') {
      document.getElementById('org_size_error').innerText = 'Please choose an option.';
      isValid = false;
    }

    if (orgPlace === '') {
      document.getElementById('org_place_error').innerText = 'Please choose an option.';
      isValid = false;
    }

    if (orgDesc === '') {
      document.getElementById('org_desc_error').innerText = 'Please choose an option.';
      isValid = false;
    }

    if (!agreement) {
      document.getElementById('agreement_error').innerText = 'You must agree to the terms and conditions.';
      isValid = false;
    }
    
    return isValid;
  }
  
   function isValid() {
    if (validateForm()){
      const timeout = setTimeout(confirmation,500);
      return true;
    }
    return false;
  }

  function confirmation(){
    var x = document.getElementById("confirmation");
    x.className = "confirmation-on";
  }
  
  
  // List of countries
  var countries = [
      "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo, Democratic Republic of the", "Congo, Republic of the", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor (Timor-Leste)", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar (Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia (formerly Macedonia)", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "South Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe", "Other"
  ];
  
  function countryDropdown() {
      var select = document.getElementById("org_place");
      for (var i = 0; i < countries.length; i++) {
          var option = document.createElement("option");
          option.text = countries[i];
          option.value = countries[i];
          select.appendChild(option);
      }
  }
  
  countryDropdown();


</script>
</body>
</html>
