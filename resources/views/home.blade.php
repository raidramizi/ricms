<x-app-layout>


<style>
/* FLOAT BUTTON */
#help-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    padding: 12px 16px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    z-index: 999;
    transition: all 0.3s ease;
}

/* icon */
#help-btn .icon {
    font-size: 18px;
}

/* hover effect */
#help-btn:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 12px 25px rgba(0,0,0,0.25);
}

/* pulse animation */
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(37,99,235, 0.6); }
    70% { box-shadow: 0 0 0 10px rgba(37,99,235, 0); }
    100% { box-shadow: 0 0 0 0 rgba(37,99,235, 0); }
}

#help-btn {
    animation: pulse 2.5s infinite;
}

/* CHATBOX */
#help-box {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 280px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    display: none;
    overflow: hidden;
    z-index: 999;
}

/* HEADER */
.help-header {
    background: #1d4ed8;
    color: white;
    padding: 10px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
}

/* BODY */
.help-body {
    padding: 15px;
}

.help-body button {
    display: block;
    width: 100%;
    margin-bottom: 8px;
    padding: 8px;
    border: none;
    background: #f3f4f6;
    cursor: pointer;
    border-radius: 6px;
}

.help-body button:hover {
    background: #e5e7eb;
}

#answer-box {
    margin-top: 10px;
    font-size: 14px;
    color: #374151;
}
.hero {
    height: 70vh;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

/* background slides */
.hero-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    z-index: 0;
}

/* dark overlay */
.hero-slide::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(2,3,83,0.6);
}

/* active slide */
.hero-slide.active {
    opacity: 1;
}

/* text on top */
.hero-content {
    position: relative;
    z-index: 2;
    padding: 20px;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
}

.hero-content p {
    margin-top: 10px;
    font-size: 1.2rem;
    opacity: 0.9;
}

.hero-btn {
    margin-top: 20px;
    display: inline-block;
    background: #2563eb;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
}

.hero-btn:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}
.section {
    padding: 60px 20px;
    max-width: 1000px;
    margin: auto;
    text-align: center;
}

.card-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    padding: 50px 20px;
    max-width: 1100px;
    margin: auto;
}

.info-card {
    height: 300px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 1.2rem;
    font-weight: bold;
    background-size: cover;
    background-position: center;
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-8px);
}

.info-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
}

.info-card span {
    position: relative;
    z-index: 1;
    padding: 20px;
}

.claim { background-image: url('{{ asset('images/claim.jpg') }}') }
.grant { background-image: url('{{ asset('images/grant.jpeg') }}') }
.application { background-image: url('{{ asset('images/background6.png') }}') }

</style>
<x-slot name="title">
    Home
</x-slot>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">
        Home
    </h2>
</x-slot>

<!-- LOGIN WELCOME -->
<div class="bg-white shadow rounded-xl p-6 mb-6 text-center max-w-4xl mx-auto">
    <h1 class="text-xl font-semibold text-gray-800">
        Hi {{ Str::before(auth()->user()->name, ' ') ?? auth()->user()->name }}!
    </h1>
    <p class="text-gray-500 mt-1">
        Welcome — let’s manage your claims efficiently.
    </p>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <h1>UniKL Malaysian Institute of Information Technology (MIIT)</h1>
        <p>Payment Request & Claim Management Platform</p>

        @if(auth()->user()->isAcademician())
    <a href="{{ route('claim') }}" class="hero-btn">Get Started</a>
@endif
    </div>

    <!-- SLIDES -->
    <div class="hero-slide active" style="background-image:url('{{ asset('images/research.jpg') }}')"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/claim.jpg') }}')"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/grant.jpeg') }}')"></div>
    <div class="hero-slide" style="background-image:url('{{ asset('images/background6.png') }}')"></div>
</div>

<!-- ABOUT -->
<div class="section">
    <div class="card-box">
        <h2 class="text-2xl font-bold mb-4">About This System</h2>

        <p class="text-gray-600 leading-relaxed">
            This system supports the Research & Innovation (R&I) Section of Universiti Kuala Lumpur MIIT.
            It centralizes grant applications, funding management, and claim submissions into a unified digital platform.
        </p>

        <p class="text-gray-600 mt-4 leading-relaxed">
            It improves efficiency, transparency, and tracking of all research-related processes within the institution.
        </p>
    </div>
</div>

<!-- FEATURES -->
<div class="sections">

    <div class="info-card claim">
        <span>
            Claim Management<br><br>
            Submit and track research claims with proper verification workflow.
        </span>
    </div>

    <div class="info-card grant">
        <span>
            Grant Management<br><br>
            Manage funding allocation and monitor grant usage efficiently.
        </span>
    </div>

    <div class="info-card application">
        <span>
            Claim Application<br><br>
            Apply, review, and approve claims through a structured workflow.
        </span>
    </div>

</div>

<!-- FOOTER -->
<div class="mt-16 text-white"
     style="background: linear-gradient(135deg, #1f2937, #111827, #374151);">

    <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- ABOUT -->
        <div>
            <h3 class="text-lg font-semibold mb-4">About</h3>
            <p class="text-gray-300 text-sm leading-relaxed">
                UniKL MIIT Payment Request & Claim Management System supports Research & Innovation (R&I) section
                by digitalizing grant applications, claims, and funding workflows.
            </p>
        </div><br/>

        <!-- CONTACT -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Contact</h3>
            <p class="text-gray-300 text-sm">
                Research & Innovation (R&I)<br>
                Universiti Kuala Lumpur MIIT<br>
                Email: ri@unikl.edu.my<br>
                Phone: +603-0000 0000
            </p>
        </div><br/>

        <!-- QUICK LINKS -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>

            <ul class="space-y-2 text-sm">
                <li>
                    <a href="https://cas.unikl.edu.my/cas-web/login?service=https://portal.unikl.edu.my/j_spring_cas_security_check?spring-security-redirect=/home.htm"
                       target="_blank"
                       class="text-gray-300 hover:text-white">
                        UniKL Portal
                    </a>
                </li>

                <li>
                    <a href="https://vle.unikl.edu.my/my/"
                       target="_blank"
                       class="text-gray-300 hover:text-white">
                        UniKL VLE
                    </a>
                </li>

            </ul>

            <div class="flex gap-4 mt-4">

                <a href="https://www.instagram.com/uniklmiit/" target="_blank" class="flex items-center gap-2 text-gray-400 hover:text-white"> Instagram</a>
            </div>
        </div>

    </div>



</div>
<!-- FLOATING HELP BUTTON -->
<div id="help-btn">
    <span class="icon">💬</span>
    <span class="text">Need Help?</span>
</div>

<!-- CHATBOX -->
<div id="help-box">
    <div class="help-header">
        Help Center
        <span onclick="toggleHelp()">✖</span>
    </div>

    <div class="help-body">
        <p><strong>Common Questions:</strong></p>

        <button onclick="showAnswer('claim')">How to submit claim?</button>
        <button onclick="showAnswer('grant')">Where can I submit virement form?</button>
        <button onclick="showAnswer('status')">Check application status?</button>

        <div id="answer-box"></div>
    </div>
</div>
<script>
let slides = document.querySelectorAll('.hero-slide');
let current = 0;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) {
            slide.classList.add('active');
        }
    });
}

function nextSlide() {
    current = (current + 1) % slides.length;
    showSlide(current);
}

// auto change every 4 seconds
setInterval(nextSlide, 4000);


const helpBtn = document.getElementById('help-btn');
const helpBox = document.getElementById('help-box');

helpBtn.onclick = toggleHelp;

function toggleHelp() {
    helpBox.style.display = helpBox.style.display === 'block' ? 'none' : 'block';
}

function showAnswer(type) {
    let answer = '';

    if(type === 'claim') {
        answer = "Go to Claim page → Fill form → Upload documents → Submit.";
    }
    else if(type === 'grant') {
        answer = "Go to Claim page → Look for Virement under Grant Claims → Download and complete form .";
    }
    else if(type === 'status') {
        answer = "Go to My Application → View your application status under submissions.";
    }

    document.getElementById('answer-box').innerText = answer;
}

</script>

</x-app-layout>
