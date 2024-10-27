@extends('layouts.app') <!-- Adjust according to your layout -->

@section('content')

<head>
    <link rel="stylesheet" href="{{ asset('css/privacy-policy.css') }}">
</head>
<style> 

body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #f5f5f9;
    height: 100vh;
    overflow: hidden;
    font-size: 12px;
}
.privacy-container {
    margin: 40px auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 0 0.375rem 0.25rem rgba(161, 172, 184, 0.15);
    overflow-y: auto;
    height: calc(100vh - 160px); 
    max-height: 67%; 
    max-width: 1660px;
}

::-webkit-scrollbar {
    width: 6px; 
}


::-webkit-scrollbar-track {
    background: #f1f1f1; 
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888; 
    border-radius: 10px; 
}

::-webkit-scrollbar-thumb:hover {
    background: #555; 
}
.privacy-container h1 {
    margin-bottom: 20px;
    color: #566a7f;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
}

.policy-section h2 {
    font-size: 18px;
    color: #09b3e4;
    margin-bottom: 10px;
}

.policy-section p {
    font-size: 12px;
    color: #697a8d;
    line-height: 1.6;
    margin-bottom: 16px;
}

.policy-section {
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .privacy-container {
        margin: 20px;
        padding: 20px;
    }
}
   /* GUIDELINES */
   .cont-g {
    margin: 20px;
}

.box-g {
    flex: 1;
    padding-left: 20px;
    padding-right: 20px;
    background-color:#ffffff;
    border-radius: 5px;
    background: linear-gradient(to bottom right, #FFD9B3, #FFB347); 
    box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    text-align: center;
    justify-content: center;
    align-items: center;
    margin-top: 0;
}
.box-g ul {
    list-style: none;
    margin: 15px;
}
.guide-title-2 {
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}


</style>
<div class="cont-g">
    <div class="box-g">
        <ul>
            <li class="guide-title-2">PRIVACY POLICY</li>
        </ul>
    </div>
</div>

<div class="privacy-container box">
 

    <section class="policy-section">
        <h2>Who we are</h2>
        <p>Bicol University is the premiere state university in the Bicol region founded on June 21, 1969, through the passage of RA 5521. As an institution promoting Scholarship, Leadership, Character, and Service as its core values, BU has risen to SUC Level IV status.</p>
    </section>

    <section class="policy-section">
        <h2>Media</h2>
        <p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
    </section>

    <section class="policy-section">
        <h2>Cookies</h2>
        <p>If you leave a comment on our site, you may opt-in to saving your name, email address, and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>
        <p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>
        <p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select “Remember Me,” your login will persist for two weeks. If you log out, the login cookies will be removed.</p>
        <p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after one day.</p>
    </section>

    <section class="policy-section">
        <h2>Embedded Content from Other Websites</h2>
        <p>Articles on this site may include embedded content (e.g., videos, images, articles, etc.). Embedded content from other websites behaves in the same way as if the visitor has visited the other website. These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction if you have an account and are logged in to that website.</p>
    </section>

    <section class="policy-section">
        <h2>What Rights You Have Over Your Data</h2>
        <p>If you have an account on this site or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
    </section>
</div>
@endsection
