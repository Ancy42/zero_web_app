<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- google fonts -->
    <link rel="shortcut icon" href="https://i.ibb.co.com/Jtzfxw0/08-icon-Dark-Background.png" type="image/x-icon"> 
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />

    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <!-- bootstrap 5.2 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <!-- css -->
    <style>
      * {
        padding: 0;
        margin: 0;
      }

      ul,
      ol {
        list-style: none;
        padding: 0;
      }

      a {
        text-decoration: none !important;
      }

      h1,
      h2,
      h3,
      h4,
      h5,
      h6,
      p {
        margin: 0;
      }
      body {
        background: #fcfffd;
      }

      /* warning-nav */

      #warning-nav {
        padding: 24px 0px;
      }
      #warning-nav ul {
        display: flex;
        align-items: center;
        justify-content: end;
        gap: 40px;
      }

      #warning-nav li a {
        font-family: "Montserrat", sans-serif;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        color: #24262d;
      }

      /* warning-content */

      #warning-content {
        margin-top: 94px;
      }

      #warning-content .main {
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
      }
      #warning-content .main .content {
        margin-top: 35px;
      }
      #warning-content .main p {
        font-family: "Montserrat", sans-serif;
        font-weight: 500;
        font-size: 24px;
        line-height: 32px;
        color: #687387 !important;
        text-align: center;
      }

      /* warning-policy */

      #warning-policy {
        margin-top: 35px;
      }

      #warning-policy .policy {
        border-radius: 8px;
        padding: 16px;
        background: #f6f7f9;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        -ms-border-radius: 8px;
        -o-border-radius: 8px;
      }

      #warning-policy .policy-extends {
        position: relative;
        border: 1px solid #3b82f6;
        padding: 16px;
        background: #ffffff !important;
      }

      #warning-policy .policy-extends img {
        position: absolute;

        top: 16px;
        right: 16px;
      }

      #warning-policy .policy-extends .hyper {
        color: #ffffff;
        background: #3b82f6;
      }

      #warning-policy .policy h3 {
        font-family: "Inter", sans-serif;
        font-weight: 400;
        font-size: 24px;
        line-height: 32px;
        color: #24262d !important;
      }

      #warning-policy .policy h4 {
        font-family: "Inter", sans-serif;
        font-weight: 600;
        font-size: 32px;
        line-height: 40px;
        color: #24262d !important;
        padding-bottom: 16px;
      }

      #warning-policy .policy p {
        padding: 8px 0px;
        font-family: "Inter", sans-serif;
        font-weight: 300;
        font-size: 10px;
        line-height: 18px;
        color: #24262d !important;
      }

      #warning-policy .policy a {
        border-radius: 4px;
        font-family: "Inter", sans-serif;
        font-weight: 500;
        font-size: 16px;
        line-height: 24px;
        color: #3b82f6;
        padding: 7px 50px;
        border: 1px solid #3b82f6;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -ms-border-radius: 4px;
        -o-border-radius: 4px;
      }

    </style>
    
    <title>Warning</title>
  </head>
  <body>
    <section id="warning-nav">
      <div class="container">
        <div class="row">
          <div class="col-md-6 h-100">
            <div class="logo">
              <a href="https://razinsoft.com/" _target="_blank">
                <img src="https://razinsoft.com/web/src/home/image/logo/logo-dark.svg" alt="logo" />
              </a>
            </div>
          </div>
          <div
            class="col-md-6 d-flex align-items-center justify-content-center"
          >
            <ul class="m-0">
              <li>
                <a target="_blank" href="https://codecanyon.net/user/razinsoft/portfolio">Products</a>
              </li>
              <li>
                <a target="_blank" href="https://razinsoft.com/contact-us">Support</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="warning-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="main">
              <div class="img">
                <img src="https://razinsoft.com/image/warning-image.png" alt="hero" />
              </div>
              <div class="content">
                <p style="font-size:16px">
                  This license key isn’t valid for this domain. </br> Each license key can only be used for one domain, and it may have been used on another domain. </br> Therefore, we suggest buying a new package for better performance.
                  </br>
                  Go ahead to install after purchasing the license. <a href="{{route('installer.welcome.index')}}">Click Here</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="warning-policy">
      <div class="container">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <div class="policy">
              <h3>REGULAR License</h3>
              <div class="content">
                <p>Start Small But Effective.</p>
              </div>
              <h4>${{ config('installer.regular_license.price') }}</h4>
              <a href="{{ config('installer.regular_license.link') }}">Buy Now</a>
            </div>
          </div>
          <div class="col-md-4">
            <div class="policy policy-extends">
              <img src="https://razinsoft.com/image/rockets.png" alt="rocket" />
              <h3>Extended License</h3>
              <div class="content">
                <p>Start Small But Effective.</p>
              </div>
              <h4>${{ config('installer.extende_license.price') }}</h4>
              <a class="hyper" href="{{ config('installer.extende_license.link') }}">Buy Now</a>
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
    </section>

    <!-- bootstrap 5.2 -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
