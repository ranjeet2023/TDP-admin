<!-- Footer -->
<footer class="footer-bg d-none d-sm-block">
    <div class="containerX">
        <div class="row text-white">
            <div class="col-6 col-sm-6 col-md-3 col-lg-2 footer-list p-0 order-md-1 order-md-1">
                <h6>Get in Touch</h6>
                <ul class=" location-city">
                    <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond in Surat"> Surat</a></li>
                    <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond in usa"> United States</a></li>
                    <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond in belgium"> Belgium</a></li>
                    <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond in HongKong"> HongKong</a></li>
                </ul>
            </div>
            <div class="col-sm-6 col-md-2 footer-list p-0 d-none d-sm-inline order-md-3 order-lg-2 ">
                <h6>Useful Links</h6>
                <ul class=" location-city">
                    <li><a href="{{ url('/about') }}">About Us</a></li>
                    <li><a href="{{ url('/buyer') }}">For Buyers</a></li>
                    <li><a href="{{ url('/suppliers') }}">For Supplier</a></li>
                    <li><a href="{{ url('/blog') }}"> Blogs</a></li>
                    <li><a href="{{ url('/login') }}"> Login</a></li>
                </ul>
            </div>
            <div class="col-6 col-sm-6 col-md-2 col-md-2 footer-list p-0 order-md-2 order-md-3 mt-sm-5 mt-md-0">
                <h6>Other Links</h6>
                <ul class=" location-city">
                    <li><a href="{{ url('/contact') }}"> Contact Us</a></li>
                    <li><a href="{{ url('/privacy-policy') }}"> Privacy Policy</a></li>
                    <li class="d-lg-none text-nowrap"><a href="{{ url('/terms-and-conditions') }}"> Terms & Condition</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-md-5 col-lg-6 footer-list d-flex justify-content-md-end p-0 mt-5 mt-md-0 order-4">
                <div class="text-center text-sm-start FollowUs-mail">
                    <h6 class="FollowUsOn">Follow Us On</h6>
                    <ul class=" location-city social-icon">
                        <li>
                            <a href="https://twitter.com/Thediamondportt" target="_blank">
                                <img src="{{ asset('assets/frontend/images/twitter.svg') }}" alt="The Diamont Port twitter" class="img-fluid">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/thediamondport" target="_blank">
                                <img src="{{ asset('assets/frontend/images/facebook.svg') }}" alt="The Diamont Port Facebook" class="img-fluid">
                            </a>
                        </li>
                        <li>
                            <a href="https://in.linkedin.com/company/the-diamond-port" target="_blank">
                                <img src="{{ asset('assets/frontend/images/linkedin-line.svg') }}" alt="The Diamont Port linkedin" class="img-fluid">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/thediamondport" target="_blank">
                                <img src="{{ asset('assets/frontend/images/instagram.svg') }}" alt="The Diamont Port instagram" class="img-fluid">
                            </a>
                        </li>
                    </ul>
                    <div class="footer-input">
                        <h6 class="FollowUsOn">Subscribe</h6>
                        <p class="mt-3">To get exclusive offers</p>
                        <form method="post" action="{{ url('jck-las-vegas-post') }}" class="input-group"
                            name="footermail">
                            {{ csrf_field() }}
                            <input type="email" class="form-control" placeholder="Enter Email" name="fmail" required>
                            <button type="submit">
                                <span class="input-group-text">
                                    <img src="{{ asset('assets/frontend/images/footer-mail-arrow.svg') }}" alt="" class="img-fluid">
                                </span>
                            </button>
                        </form>
                    </div>
                    <div class="mt-4 mail-contact">
                        <p class="d-flex align-items-center justify-content-center justify-content-sm-start"><img src="{{ asset('assets/frontend/images/mail.svg') }}" class="me-3') }}" alt=""><span>info@thediamondport.com</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="containerX">
        <div class="row text-white align-items-center opacity-div">
            <div class="col-12 col-sm-6 col-lg-4 p-0 text-center text-sm-start">
                <h5>The Diamond Port</h5>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 p-0 text-center text-sm-end text-lg-center opacity-div">
                <p>Copyright The Diamond Port 2022</p>
            </div>
            <div class="col-4 p-0 opacity-div d-none d-lg-block">
                <ul class="d-flex justify-content-end">
                    <li class="mx-4"><a href="{{ url('/terms-and-conditions') }}"
                            class="text-white">Terms & Condition</a></li>
                    <li class="ms-4"><a href="{{ url('/privacy-policy') }}" class="text-white">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Mobile -->
<footer class="footer-bg d-sm-none">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item ">
            <div class="accordion-header footer-list" id="headingOne">
                <h6 class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Get in Touch
                </h6>
            </div>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class=" location-city">
                        <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond">India</a></li>
                        <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond">United States</a></li>
                        <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond">Belgium</a></li>
                        <li><a href="#"><img src="{{ asset('assets/frontend/images/loaction.svg') }}" class="me-3" alt="Diamond">HongKong</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header footer-list" id="headingTwo">
                <h6 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Useful Links
                </h6>
            </div>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class=" location-city">
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/buyer') }}">For Buyers</a></li>
                        <li><a href="{{ url('/supplier') }}">For Supplier</a></li>
                        <li><a href="{{ url('/blog') }}"> Blogs</a></li>
                        <li><a href="{{ url('/login') }}"> Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header footer-list" id="headingThree">
                <h6 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Privacy Policy
                </h6>
            </div>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class=" location-city">
                        <li><a href="{{ url('/contact') }}"> Contact Us</a></li>
                        <li><a href="{{ url('/privacy-policy') }}"> Privacy Policy</a></li>
                        <li class="d-lg-none text-nowrap"><a href="{{ url('/terms-and-conditions') }}"> Terms & Condition</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="followUs-footer">
            <div class="footer-list">
                <div class="text-center text-sm-start FollowUs-mail">
                    <h6 class="FollowUsOn">Follow Us On</h6>
                    <ul class=" location-city social-icon">
                        <li>
                            <a href="https://twitter.com/Thediamondportt" target="_blank">
                                <img src="{{ asset('assets/frontend/images/twitter.svg') }}" alt="The Diamont Port twitter" class="img-fluid">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/thediamondport" target="_blank">
                                <img src="{{ asset('assets/frontend/images/facebook.svg') }}" alt="The Diamont Port facebook" class="img-fluid">
                            </a>
                        </li>
                        <li>
                            <a href="https://in.linkedin.com/company/the-diamond-port" target="_blank">
                                <img src="{{ asset('assets/frontend/images/linkedin-line.svg') }}" alt="The Diamont Port linkedin" class="img-fluid">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/thediamondport" target="_blank">
                                <img src="{{ asset('assets/frontend/images/instagram.svg') }}" alt="The Diamont Port instagram" class="img-fluid">
                            </a>
                        </li>
                    </ul>
                    <div class="footer-input">
                        <h6 class="FollowUsOn">Subscribe</h6>
                        <p class="mt-3">To get exclusive offers</p>
                        <form method="post" action="{{ url('jck-las-vegas-post') }}">
                            @csrf
                            <input type="email" class="form-control" placeholder="Enter Email" name="email" required>
                            <button type="submit">
                                <span class="input-group-text">
                                    <img src="{{ asset('assets/frontend/images/footer-mail-arrow.svg') }}" alt="Mail" class="img-fluid">
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="containerX">
        <div class="row text-white align-items-center opacity-div">
            <div class="col-12 col-sm-6 col-lg-4 mb-2 p-0 text-center text-sm-start">
                <h5>The Diamond Port</h5>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 p-0 text-center text-sm-end text-lg-center opacity-div">
                <p>Copyright The Diamond Port 2022</p>
            </div>
            <div class="col-4 p-0 opacity-div d-none d-lg-block">
                <ul class="d-flex justify-content-end">
                    <li class="mx-4"><a href="{{ url('/terms-and-conditions') }}" class="text-white">Terms & Condition</a></li>
                    <li class="ms-4"><a href="{{ url('/privacy-policy') }}" class="text-white">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
