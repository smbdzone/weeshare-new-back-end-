<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('email/bootstrap5.2/css/bootstrap.min.css') }}"> --}}
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Welcome to WeeShare</title>
</head>
<style>
    .header {
        color: white;
        background-image: url('https://weeshare.smbdigitalzone.online/public/email/images/Maskgroup1.png');
        height: 210px;
        z-index: 0;
    }

    .header-2 {
        background-image: url('https://weeshare.smbdigitalzone.online/public/email/images/Group228.png');
        margin-top: 140px;
        z-index: -1;
        font-family: 'Poppins';
    }

    .bodyreg {
        position: absolute;
        width: 400px;
        height: 400px;
        overflow: hidden;

    }

    #regs {
        margin: 50px;
        position: absolute;
        left: 0;
        transition: 1s;

    }

    #log {
        margin: 50px;
        position: absolute;
        left: -800px;
        transition: 1s;

    }

    .imgg {
        width: 1500px;
        margin-top: 70px;
    }

    .cli {
        display: flex;
        margin-left: 15px;
        height: 29px;
        flex-direction: column;
        justify-content: center;
        flex-shrink: 0;
        color: #000;
        font-family: 'Poppins';
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 80px;
        /* 400% */
        text-decoration-line: underline;
    }

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }


    .line {
        width: 3px;
        height: 35px;
        background: #24A3E0;
        margin-left: 20px;
    }

    .btnn {
        flex-shrink: 0;
        border-radius: 4px;
        background: #24A3E0;
        color: #FFF;
        font-family: 'Poppins';
        font-size: 25px;
        font-style: normal;
        font-weight: 400;
    }

    .textt {
        display: flex;
        max-width: 1087;
        min-width: 400px;
        height: 62px;
        flex-direction: column;
        text-align: center;
        flex-shrink: 0;
        color: #000;
        font-family: 'Poppins';
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: 34px;
    }

    h1 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-shrink: 0;
        color: #000;
        font-family: 'Poppins';
        font-size: 50px;
        font-style: normal;
        font-weight: 600;
        line-height: 80px;
        /* 160% */
    }

    a {
        color: black;
    }
</style>

<body>


    <div class="row mt-5" style="position: relative;">
        <div style="color: white;
        background-image: url('https://weeshare.smbdigitalzone.online/public/email/images/Maskgroup1.png'); background-repeat: no-repeat;
        height: 210px; z-index: 0;">
            <div class="row">
                <div class="col-md-12">
                    <img src="{{ asset('email/images/LOGO1.png') }}" class="img-fluid mx-auto d-block">
                    <img src="{{ asset('email/images/Line.png') }}" class="img-fluid w-100 img">
                </div>

            </div>


        </div>

        <div class="col-md-6 offset3 offsetmd-0 offset-md-3 header-2" style="position: absolute; background-image: url('https://weeshare.smbdigitalzone.online/public/email/images/Group228.png');
        background-repeat:no-repeat; margin-top: 140px;
        z-index: -1;
        font-family: 'Poppins'; ">
            <div class="row">
                <div class="col-md-12 text-center">
                    <br><br>

                    <h1 class=" mx-auto " style="font-family: 'Poppins'; font-size: 25px; margin-bottom: -20px;">
                        Welcome to Weeshare
                    </h1>
                    <div class="mx-auto d-block col-md-5 " style=" max-width: 576px; margin-bottom: 12px; min-width: 200px;
               height: 2px;background: #24A3E0;"></div>

                    <p class="mx-auto d-block textt col-md-11 text-start"
                        style="font-family: 'Poppins'; font-size: 11px; line-height: 2em ">Thank you for signing up and
                        taking the first step toward an enhanced
                        social media experience. To ensure the security of your account, please click the link below to
                        verify your
                        email address: {{ $emailAddress }}</p>

                    <a href="{{ $verification_link }}" class="mx-auto d-block btnn btn"
                        style="height: 40px; margin-top: -20px; font-size: large; font-size: 15px;"> Verification
                        Link</a>

                    <p class="mx-auto d-block textt col-md-11 text-start"
                        style="font-family: 'Poppins'; font-size: 11px; margin-top: 12px; line-height: 2em ">
                        Once your email is verified, you'll gain full access to our platform, where you can start
                        creating,
                        scheduling, and sharing your content with your target audience. Get ready to elevate your
                        social media presence!
                    </p>
                    <p class="mx-auto d-block textt col-md-11 text-start"
                        style="font-family: 'Poppins'; font-size: 11px; margin-top: -22px; line-height: 2em ">
                        If you have any questions or need assistance, feel free to reach out to our support team at
                        <a href="mailto:help@weeshare.co">help@weeshare.co</a>
                    </p>
                    <p class="mx-auto d-block textt col-md-11 text-start"
                        style="font-family: 'Poppins'; font-size: 11px; margin-top: -42px; line-height: 2em ">
                        Best regards, <br>
                        The <a href="weeshare.co">WeeShare</a> Platform Team
                    </p>

                    <div class="  mx-auto d-block col-md-8 " style=" max-width: 576px; margin-top: -20px; min-width: 200px;
               height: 2px;background: #24A3E0;"></div>

                    <br>
                    <a href="https://www.facebook.com/weeshare2" target="_blank" style="text-decoration: none;">
                        <img src="{{ asset('email/images/Facebook.png') }}" alt="this is facbook link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="https://www.instagram.com/weeshare2/" target="_blank" style="text-decoration: none;">
                        <img src="{{ asset('email/images/instagram.png') }}" alt="this is instagram link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="https://www.tiktok.com/@weeshare2?lang=en" target="_blank" style="text-decoration: none;">
                        <img src="{{ asset('email/images/tiktok.png') }}" alt="this is tiktok link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="https://twitter.com/Weeshare1" target="_blank" style="text-decoration: none;">
                        <img src="{{ asset('email/images/Twitter.png') }}" alt="this is Twitter link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="https://www.youtube.com/channel/UCsymeJsM14kad4RodkT5w8Q" target="_blank"
                        style="text-decoration: none;">
                        <img src="{{ asset('email/images/Youtube.png') }}" alt="this is Youtube link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="https://www.pinterest.com/weeshare2" target="_blank" style="text-decoration: none;">
                        <img src="{{ asset('email/images/pinterest.png') }}" alt="this is pinterest link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="https://www.linkedin.com/company/weeshare/?viewAsMember=true" target="_blank"
                        style="text-decoration: none;">
                        <img src="{{ asset('email/images/Linkedin.png') }}" alt="this is Linkedin link" style="width: 30px;">
                    </a>&nbsp; &nbsp;
                    <a href="#" target="_blank" style="text-decoration: none;">
                        <img src="{{ asset('email/images/Google.png') }}" alt="this is Google link" style="width: 30px;">
                    </a><br><br>

                    <ul style="list-style-type:none; display: inline-flex;">
                        <li class="cli"><a href="https://weeshare.co/about-us">About Us</a></li>
                        <li class="cli"><a href="https://weeshare.co/how-it-works">How it Works</a></li>
                        <li class="cli"><a href="https://weeshare.co/advertise-with-us">Advertise With Us</a></li>
                        <li class="cli"><a href="https://weeshare.co/terms-and-conditions">Terms and Conditions</a></li>
                        <li class="cli"><a href="https://weeshare.co/privacy-policy">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <br>

</body>


<source src="{{ asset('email/bootstrap5.2/js.bootstrap.min.js') }}" type="">

</html>