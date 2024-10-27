@extends('layouts.app')
@section('content')

<head>
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> <!-- Add Bootstrap -->
</head>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #f5f5f9;
    overflow-x: hidden;
    font-size: 12px;
}

.container-custom {
    margin: 20px auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 0 0.375rem 0.25rem rgba(161, 172, 184, 0.15);
    overflow-y: auto;
    height: calc(100vh - 160px);
    max-width: 95%;
    max-height: 67%;
}

::-webkit-scrollbar {
    width: 6px; 
}

::-webkit-scrollbar-track {
    background: #f1f1f1; 
}

::-webkit-scrollbar-thumb {
    background: #888; 
    border-radius: 10px; 
}

::-webkit-scrollbar-thumb:hover {
    background: #555; 
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.page-title {
    color: #566a7f;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.faq-content {
    margin-top: 20px;
}

.faq-item {
    margin-bottom: 20px;
}

.faq-question {
    font-size: 15px;
    font-weight: normal;
    color: #09b3e4;
    margin-bottom: 5px;
}

.faq-answer, li {
    font-size: 12px;
    color: #697a8d;
    line-height: 1.6;
    margin-bottom: 16px;
}

a {
    color: #ff0000;
    text-decoration: none;
    font-size: 15px;
}

a:hover {
    text-decoration: underline;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-custom {
        margin: 10px;
        padding: 15px;
        height: auto;
    }

    .page-title {
        font-size: 20px;
    }

    .faq-question {
        font-size: 16px;
    }

    .faq-answer {
        font-size: 12px;
    }
}

/* GUIDELINES */
.cont-g {
    margin: 20px;
}

.box-g {
    flex: 1;
    padding: 15px 20px;
    background: linear-gradient(to bottom right, #FFD9B3, #FFB347);
    border-radius: 5px;
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
    padding: 0;
}

.guide-title-2 {
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); 
}
</style>

<body>
    <div class="cont-g">
        <div class="box-g">
            <ul>
                <li class="guide-title-2">FREQUENTLY ASKED QUESTIONS</li>
            </ul>
        </div>
    </div>

    <div class="container container-custom">

        <div class="faq-content">
            <div class="faq-item">
                <h2 class="faq-question">What is the Sticker Vehicle Pass?</h2>
                <p class="faq-answer">
                    The Sticker Vehicle Pass allows authorized vehicles to enter and park within the university premises. It ensures that only registered and approved vehicles have access to the campus.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">Who is eligible to apply for a Sticker Vehicle Pass?</h2>
                <p class="faq-answer">
                    Students, faculty, and staff members with valid university IDs and registered vehicles are eligible to apply for the pass.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">What documents are required for the application?</h2>
                <p class="faq-answer">
                    You will need the following documents:
                    <ul>
                        <li>Scanned Copy of Driver's license</li>
                        <li>Scanned Copy of Certificate of Registration (CR) and Official Receipt (OR)</li>
                        <li>Scanned Copy of School ID and COR for Students</li>
                        <li>Vehicle registration details (model, plate number, etc.)</li>

                    </ul>
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">How can I apply for the Sticker Vehicle Pass?</h2>
                <p class="faq-answer">
                    You can apply online through the <a href="{{ route('register') }}">Vehicle Registration Portal</a>. Complete the form, upload the required documents, and wait for approval from the motorpool department.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">How long does the approval process take?</h2>
                <p class="faq-answer">
                    The approval process typically takes 3-5 working days. You will be notified via email once your application is reviewed.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">Where can I collect my sticker once approved?</h2>
                <p class="faq-answer">
                    Once approved, you can collect the sticker from the motorpool office. Please bring a valid ID and the email confirmation for verification.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">What is the validity period of the sticker?</h2>
                <p class="faq-answer">
                    The sticker is valid for one academic year. You need to renew it at the start of each academic year.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">What happens if I lose my sticker?</h2>
                <p class="faq-answer">
                    In case of a lost sticker, report it immediately to the motorpool office. A replacement fee may apply.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">Can I apply for multiple stickers for different vehicles?</h2>
                <p class="faq-answer">
                    Yes, you can apply for multiple stickers, but each vehicle must be registered separately with its own set of documents.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">Who should I contact for further assistance?</h2>
                <p class="faq-answer">
                    For inquiries, you can contact the motorpool office at <a href="mailto:motorpool@university.edu.ph">motorpool@university.edu.ph</a> or call (02) 1234-5678.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

@endsection
