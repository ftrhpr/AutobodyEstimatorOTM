<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-mobile">
            <div class="col-lg-6 text-center text-lg-start">
                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill">
                    <i class="bi bi-star-fill me-1"></i>
                    <?= Lang::getLocale() === 'ka' ? 'სწრაფი & ზუსტი შეფასება' : 'Fast & Accurate Assessment' ?>
                </div>
                <h1 class="display-5 fw-bold mb-3">
                    <?= __('app_name') ?>
                </h1>
                <p class="lead mb-4 text-muted">
                    <?= Lang::getLocale() === 'ka' 
                        ? 'პროფესიონალური დაზიანების შეფასება. ატვირთეთ ფოტოები და მიიღეთ დეტალური კალკულაცია.'
                        : 'Professional damage assessment. Upload photos and get detailed repair cost estimates.' ?>
                </p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-lg-start">
                    <a href="/register" class="btn btn-primary btn-lg px-4 py-3">
                        <i class="bi bi-person-plus me-2"></i><?= __('auth.register') ?>
                    </a>
                    <a href="/login" class="btn btn-outline-primary btn-lg px-4 py-3">
                        <?= __('auth.login') ?>
                    </a>
                </div>
                <div class="mt-4 d-flex align-items-center justify-content-center justify-content-lg-start gap-4 text-muted small">
                    <span><i class="bi bi-check-circle-fill text-success me-1"></i> <?= Lang::getLocale() === 'ka' ? '24/7 მხარდაჭერა' : '24/7 Support' ?></span>
                    <span><i class="bi bi-check-circle-fill text-success me-1"></i> <?= Lang::getLocale() === 'ka' ? 'სწრაფი პასუხი' : 'Quick Response' ?></span>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center mt-5 mt-lg-0">
                <div class="hero-illustration">
                    <i class="bi bi-car-front-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill"><?= Lang::getLocale() === 'ka' ? 'მარტივი პროცესი' : 'Simple Process' ?></span>
            <h2 class="fw-bold mb-2"><?= Lang::getLocale() === 'ka' ? 'როგორ მუშაობს?' : 'How It Works' ?></h2>
            <p class="text-muted"><?= Lang::getLocale() === 'ka' ? '3 მარტივი ნაბიჯი შეფასების მისაღებად' : '3 Simple Steps to Get Your Assessment' ?></p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card text-center h-100">
                    <div class="step-number">1</div>
                    <div class="stat-icon stat-icon-primary mx-auto">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                    <h5 class="fw-bold mt-3"><?= Lang::getLocale() === 'ka' ? 'ატვირთეთ ფოტოები' : 'Upload Photos' ?></h5>
                    <p class="text-muted small">
                        <?= Lang::getLocale() === 'ka' 
                            ? 'გადაიღეთ დაზიანების ფოტოები სხვადასხვა კუთხიდან'
                            : 'Take clear photos of the damage from multiple angles' ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card text-center h-100">
                    <div class="step-number">2</div>
                    <div class="stat-icon stat-icon-info mx-auto">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold mt-3"><?= Lang::getLocale() === 'ka' ? 'ექსპერტის შეფასება' : 'Expert Assessment' ?></h5>
                    <p class="text-muted small">
                        <?= Lang::getLocale() === 'ka' 
                            ? 'ჩვენი სპეციალისტები შეისწავლიან და შეაფასებენ დაზიანებას'
                            : 'Our certified experts review and assess the damage' ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card text-center h-100">
                    <div class="step-number">3</div>
                    <div class="stat-icon stat-icon-success mx-auto">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <h5 class="fw-bold mt-3"><?= Lang::getLocale() === 'ka' ? 'მიიღეთ ანგარიში' : 'Get Your Report' ?></h5>
                    <p class="text-muted small">
                        <?= Lang::getLocale() === 'ka' 
                            ? 'მიიღეთ დეტალური კალკულაცია რემონტის ღირებულებით'
                            : 'Receive detailed report with itemized repair costs' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3 rounded-pill"><?= Lang::getLocale() === 'ka' ? 'უპირატესობები' : 'Benefits' ?></span>
                <h2 class="fw-bold mb-4"><?= Lang::getLocale() === 'ka' ? 'რატომ ჩვენ?' : 'Why Choose Us?' ?></h2>
                
                <div class="feature-item d-flex mb-4">
                    <div class="stat-icon stat-icon-primary me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1"><?= Lang::getLocale() === 'ka' ? 'სწრაფი შეფასება' : 'Fast Turnaround' ?></h6>
                        <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'შეფასება 24-48 საათში' : 'Get assessment within 24-48 hours' ?></p>
                    </div>
                </div>
                
                <div class="feature-item d-flex mb-4">
                    <div class="stat-icon stat-icon-success me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1"><?= Lang::getLocale() === 'ka' ? 'გამოცდილი სპეციალისტები' : 'Expert Assessors' ?></h6>
                        <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'სერტიფიცირებული პროფესიონალები' : 'Certified professionals with experience' ?></p>
                    </div>
                </div>
                
                <div class="feature-item d-flex mb-4">
                    <div class="stat-icon stat-icon-warning me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1"><?= Lang::getLocale() === 'ka' ? 'გამჭვირვალე ფასები' : 'Transparent Pricing' ?></h6>
                        <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'დეტალური კალკულაცია ყველა ხარჯით' : 'Detailed breakdown of all costs' ?></p>
                    </div>
                </div>
                
                <div class="feature-item d-flex">
                    <div class="stat-icon stat-icon-info me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1"><?= Lang::getLocale() === 'ka' ? 'მობილურისთვის' : 'Mobile Friendly' ?></h6>
                        <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'მარტივი გამოყენება ტელეფონით' : 'Easy to use on any device' ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
                                <i class="bi bi-rocket-takeoff-fill text-primary fs-3"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-center"><?= Lang::getLocale() === 'ka' ? 'დაიწყეთ ახლავე' : 'Get Started Now' ?></h4>
                        <p class="text-muted mb-4 text-center">
                            <?= Lang::getLocale() === 'ka' 
                                ? 'დარეგისტრირდით ტელეფონის ნომრით, დაამატეთ მანქანა და გაგზავნეთ პირველი მოთხოვნა წუთებში.'
                                : 'Register with your phone, add your vehicle, and submit your first damage report in minutes.' ?>
                        </p>
                        <a href="/register" class="btn btn-primary btn-lg w-100 py-3">
                            <?= Lang::getLocale() === 'ka' ? 'რეგისტრაცია' : 'Create Account' ?>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 0;
    color: white;
}
.hero-section .lead {
    color: rgba(255,255,255,0.9) !important;
}
.hero-section .btn-outline-primary {
    color: white;
    border-color: rgba(255,255,255,0.5);
}
.hero-section .btn-outline-primary:hover {
    background: white;
    color: #667eea;
}
.min-vh-mobile {
    min-height: 60vh;
}
@media (max-width: 991px) {
    .hero-section {
        padding: 60px 0;
    }
    .min-vh-mobile {
        min-height: auto;
        padding: 40px 0;
    }
}
.hero-illustration {
    font-size: 15rem;
    opacity: 0.3;
    color: white;
}
.step-card {
    padding: 1.5rem;
    border-radius: 16px;
    background: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s;
}
.step-card:hover {
    transform: translateY(-5px);
}
.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--bs-primary, #6366f1);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}
.step-icon {
    font-size: 2.5rem;
}
.feature-icon-sm {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.bg-primary-subtle {
    background-color: rgba(99, 102, 241, 0.1) !important;
}
</style>
