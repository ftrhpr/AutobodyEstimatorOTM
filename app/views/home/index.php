<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <?= __('app_name') ?>
                </h1>
                <p class="lead mb-4">
                    Professional auto damage assessment services. Upload photos of your vehicle damage
                    and receive detailed repair cost estimates from our expert assessors.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/register" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i><?= __('auth.register') ?>
                    </a>
                    <a href="/login" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i><?= __('auth.login') ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <img src="<?= asset('images/hero-car.svg') ?>" alt="Car Assessment" class="img-fluid hero-image">
            </div>
        </div>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto">
                            <i class="bi bi-camera fs-3"></i>
                        </div>
                        <h5 class="card-title">1. Upload Photos</h5>
                        <p class="card-text text-muted">
                            Take clear photos of the damage from multiple angles and upload them to our platform.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto">
                            <i class="bi bi-search fs-3"></i>
                        </div>
                        <h5 class="card-title">2. Expert Assessment</h5>
                        <p class="card-text text-muted">
                            Our certified assessors review your submission and provide detailed repair estimates.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto">
                            <i class="bi bi-file-earmark-text fs-3"></i>
                        </div>
                        <h5 class="card-title">3. Get Your Report</h5>
                        <p class="card-text text-muted">
                            Receive a comprehensive assessment report with itemized costs via SMS notification.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="mb-4">Why Choose Us?</h2>
                <div class="d-flex mb-3">
                    <div class="feature-check me-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h5>Fast Turnaround</h5>
                        <p class="text-muted">Get your assessment within 24-48 hours</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="feature-check me-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h5>Expert Assessors</h5>
                        <p class="text-muted">Certified professionals with years of experience</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="feature-check me-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h5>Transparent Pricing</h5>
                        <p class="text-muted">Detailed breakdown of all repair costs</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="feature-check me-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h5>Easy to Use</h5>
                        <p class="text-muted">Simple mobile-friendly interface</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">Quick Start</h4>
                        <p class="text-muted">
                            Register with your phone number, add your vehicle details, and submit your first
                            damage report in minutes. It's that simple!
                        </p>
                        <a href="/register" class="btn btn-primary">
                            Get Started Now <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 60px 0;
}
.min-vh-75 {
    min-height: 75vh;
}
.hero-image {
    max-height: 400px;
}
.feature-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
