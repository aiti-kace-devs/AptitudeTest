<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Protection Course Details</title>
    <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            align-items: center;
            height: 100vh;
            padding: 20px;
            gap: 20px;
        }

        .text-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-content img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            border-radius: 10px;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: bold;
            display: block;
        }

        /*  .register-button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 15px;
    }*/
    </style>
</head>

<body>
    <div class="container">
        <div class="text-content">
            <div class="detail-item">
                <h1> <span class="detail-label">Certified Data Protection Supervisor </span></h1>
                <!-- <span class="detail-value">Cybersecurity Specialist</span> -->
            </div>
            <div class="detail-item">
                <span class="detail-label">Job Responsibilities:</span>
                <span class="detail-value">Oversee and coordinate data protection activities within an organization,
                    Ensure data protection policies are implemented and followed, Conduct protection impact assessments
                    (DPIAs), Provide strategic guidance on data protection and privacy risks, Serve as a liaison between
                    the organization and regulatory authoritie.</span>
            </div>
            <!-- <div class="detail-item">
        <span class="detail-label">No. of People to Train:</span>
        <span class="detail-value">20-30 participants per session</span>
      </div> -->
            <!-- <div class="detail-item">
        <span class="detail-label">Training Program:</span>
        <span class="detail-value">Certified Cybersecurity Professional</span>
      </div> -->
            <div class="detail-item">
                <span class="detail-label">Training Modules:</span>
                <ul class="detail-value">
                    <li>1.Privacy by Design & Data Protection Impact Assessments (DPIA)

                    </li>
                    <li>Managing Data Breaches & Incident Response Plans</li>
                    <li>Data Protection Audits & Compliance Reporting</li>
                    <li>Role of the CDPS in Organizational Compliance</li>


                </ul>
            </div>
            <div class="detail-item">
                <span class="detail-label">Training Duration:</span>
                <span class="detail-value">60 hrs</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Training Prerequisite:</span>
                <span class="detail-value">A minimum of a diploma or bachelor's degree in law, IT, data management,
                    cybersecurity, business administration, or related fields.</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Available Certifications:</span>
                <span class="detail-value">
                    <li> Certified Information Privacy Professional (CIPP)</li>
                    <li> Certified Information Privacy Manager (CIPM)</li>
                </span>
            </div>
            <a href="{{ url('/forms/register') }}" class="register-button">Register</a>
        </div>
        <div class="image-content">
            <img src="{{ url('assets/home/images/data.jpg') }}" alt="Data Protection">
        </div>
    </div>
</body>

</html>
