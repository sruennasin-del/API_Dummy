@extends('layouts.welcome')
@section('content')

<div class="container my-5" style="max-width: 1000px; font-family: 'DM Sans', sans-serif;">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="font-family: 'Syne', sans-serif; font-size: 40px; background: linear-gradient(135deg, var(--orange) 30%, #ff8c52 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Get In Touch
        </h1>
        <p class="text-muted mt-2 fs-5" style="max-width: 600px; margin: 0 auto;">
            Have questions about products, shipping, or orders? Let us help you.
        </p>
    </div>

    <div class="row g-4">
        {{-- LEFT SIDE: CONTACT CARDS --}}
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-3 h-100">
                {{-- CARD 1: OFFICE --}}
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 50px; height: 50px; background: var(--orange-pale); color: var(--orange); flex-shrink: 0;">
                            <i class="ti ti-map-pin fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Our Office</h6>
                            <p class="text-muted m-0" style="font-size: 13.5px;">Phnom Penh, Cambodia</p>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: CALL --}}
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 50px; height: 50px; background: var(--orange-pale); color: var(--orange); flex-shrink: 0;">
                            <i class="ti ti-phone fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Phone Number</h6>
                            <p class="text-muted m-0" style="font-size: 13.5px;">+855 12 345 678</p>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: EMAIL --}}
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 50px; height: 50px; background: var(--orange-pale); color: var(--orange); flex-shrink: 0;">
                            <i class="ti ti-mail fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Email Support</h6>
                            <p class="text-muted m-0" style="font-size: 13.5px;">support@zestshop.com</p>
                        </div>
                    </div>
                </div>

                {{-- CARD 4: HOURS --}}
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 50px; height: 50px; background: var(--orange-pale); color: var(--orange); flex-shrink: 0;">
                            <i class="ti ti-clock fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 15px;">Business Hours</h6>
                            <p class="text-muted m-0" style="font-size: 13.5px;">Mon - Sun (8:00 AM - 9:00 PM)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: CONTACT FORM --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background: #fff;">
                <h4 class="fw-bold mb-4" style="font-family: 'Syne', sans-serif;">Send Us a Message</h4>
                
                @if(session('contact_success'))
                    <div class="alert alert-success border-0 rounded-3 p-3 mb-4" style="background: #DEF7EC; color: #03543F;">
                        <i class="ti ti-circle-check-fill me-2 fs-5"></i>{{ session('contact_success') }}
                    </div>
                @endif

                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for contacting us! We will get back to you shortly.'); this.reset();">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Your Name</label>
                            <input type="text" class="form-control rounded-3" required placeholder="e.g. John Doe">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Email Address</label>
                            <input type="email" class="form-control rounded-3" required placeholder="e.g. john@example.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Subject</label>
                            <input type="text" class="form-control rounded-3" required placeholder="What can we help you with?">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Message</label>
                            <textarea class="form-control rounded-3" rows="4" required placeholder="Describe your request..."></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill text-white py-3 fw-semibold" style="background-color: var(--orange); border-color: var(--orange);">
                                Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
