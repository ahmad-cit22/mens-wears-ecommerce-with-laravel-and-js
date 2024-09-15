<footer class="footer-area section-padding-1">
            <div class="container-fluid">
                <div class="footer-top pt-100 pb-60">
                    <div class="row">
                        <div class="footer-column footer-width-24">
                            <div class="footer-widget footer-about mb-30">
                                <a href="{{ route('index') }}">
                                    <img src="{{ asset('images/website/'.$business->footer_logo) }}" alt="logo">
                                </a>
                                {!! $business->combine_address !!}
                                <div class="social-icon-style-2 social-icon-hm4">
                                    @if($business->facebook != NULL)
                                    <a class="facebook" href="{{ $business->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a>
                                    @endif
                                    @if($business->twitter != NULL)
                                    <a class="twitter" href="{{ $business->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a>
                                    @endif
                                    @if($business->youtube != NULL)
                                    <a class="youtube" href="{{ $business->youtube }}" target="_blank"><i class="fa fa-youtube"></i></a>
                                    @endif
                                    @if($business->instagram != NULL)
                                    <a class="dribbble" href="{{ $business->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a>
                                    @endif
                                    @if($business->linkedin != NULL)
                                    <a class="facebook" href="{{ $business->linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="footer-column footer-width-19">
                            <div class="footer-widget footer-info-list-2 footer-contect mb-30">
                                <h3 class="footer-title">Contact info</h3>
                                <ul>
                                    <!-- <li><i class="dlicon ui-2_time-clock"></i> Monday - Friday: 9:00 - 19:00</li> -->
                                    <li><i class="dlicon ui-1_home-simple"></i> {{ $business->address }}</li>
                                    <li><i class="dlicon tech-2_rotate"></i> {{ $business->phone }}</li>
                                    <li><i class="dlicon ui-1_email-83"></i> {{ $business->email }}</li>

                                </ul>
                            </div>
                        </div>
                        <div class="footer-column footer-width-12">
                            <div class="footer-widget footer-info-list-2 mb-30">
                                <h3 class="footer-title">About</h3>
                                <ul>
                                    <li><a href="{{ route('about') }}">About Us</a></li>
                                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    <li><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                                    <li><a href="{{ route('cancellation.policy') }}">Cancellation & Refund Policy</a></li>
                                    <li><a href="{{ route('term.condition') }}">Terms & Condition</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="footer-column footer-width-14">
                            <div class="footer-widget footer-info-list-2 mb-30">
                                <h3 class="footer-title">Userful Link</h3>
                                <ul>
                                    <li><a href="{{ route('order.track') }}">Track My Order</a></li>
                                    <li><a href="{{ route('carts') }}">Shopping Cart</a></li>
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="footer-column footer-width-29 mb-30">
                            <div class="contact-page-map">
                                <!-- <div id="contact-map"></div> -->
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14601.556398409222!2d90.3690562!3d23.804759!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x60049e2df6c2e04b!2sGo%20By%20Fabrifest!5e0!3m2!1sen!2sbd!4v1656419579274!5m2!1sen!2sbd" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-botoom">
                    <div class="row">
                        <div class="col-12">
                            <div class="copyright-2 text-center">
                                <p style="font-size: 11px;"> Designed & Developed by <a href="https://www.imbdagency.com/" style="color: red !important;">IMBD Agency Ltd.</a> </p>
                                <p>© <a href="{{ route('index') }}" style="color: orange !important;">{{ $business->name }}</a>. All rights reserved | {{ date('Y') }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- <div class="support-lists">
            <ul>
                <li><a href="#"><i class="dlicon ui-3_chat-46"></i></a></li>
                <li><a href="#"><i class=" dlicon ui-3_phone"></i></a></li>
                <li><a href="#"><i class="dlicon ui-1_email-85"></i></a></li>
                <li>
                    <a href="https://wa.me/8801797910098?text=" target="_blank" class="chaty-tooltip pos-left" data-form="chaty-form-0-Whatsapp" data-hover="WhatsApp"><span class="chaty-icon channel-icon-Whatsapp"><span class="chaty-svg"><svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg"><circle class="color-element" cx="19.4395" cy="19.4395" r="19.4395" fill="#49E670"></circle><path d="M12.9821 10.1115C12.7029 10.7767 11.5862 11.442 10.7486 11.575C10.1902 11.7081 9.35269 11.8411 6.84003 10.7767C3.48981 9.44628 1.39593 6.25317 1.25634 6.12012C1.11674 5.85403 2.13001e-06 4.39053 2.13001e-06 2.92702C2.13001e-06 1.46351 0.83755 0.665231 1.11673 0.399139C1.39592 0.133046 1.8147 1.01506e-06 2.23348 1.01506e-06C2.37307 1.01506e-06 2.51267 1.01506e-06 2.65226 1.01506e-06C2.93144 1.01506e-06 3.21063 -2.02219e-06 3.35022 0.532183C3.62941 1.19741 4.32736 2.66092 4.32736 2.79397C4.46696 2.92702 4.46696 3.19311 4.32736 3.32616C4.18777 3.59225 4.18777 3.59224 3.90858 3.85834C3.76899 3.99138 3.6294 4.12443 3.48981 4.39052C3.35022 4.52357 3.21063 4.78966 3.35022 5.05576C3.48981 5.32185 4.18777 6.38622 5.16491 7.18449C6.42125 8.24886 7.39839 8.51496 7.81717 8.78105C8.09636 8.91409 8.37554 8.9141 8.65472 8.648C8.93391 8.38191 9.21309 7.98277 9.49228 7.58363C9.77146 7.31754 10.0507 7.1845 10.3298 7.31754C10.609 7.45059 12.2841 8.11582 12.5633 8.38191C12.8425 8.51496 13.1217 8.648 13.1217 8.78105C13.1217 8.78105 13.1217 9.44628 12.9821 10.1115Z" transform="translate(12.9597 12.9597)" fill="#FAFAFA"></path><path d="M0.196998 23.295L0.131434 23.4862L0.323216 23.4223L5.52771 21.6875C7.4273 22.8471 9.47325 23.4274 11.6637 23.4274C18.134 23.4274 23.4274 18.134 23.4274 11.6637C23.4274 5.19344 18.134 -0.1 11.6637 -0.1C5.19344 -0.1 -0.1 5.19344 -0.1 11.6637C-0.1 13.9996 0.624492 16.3352 1.93021 18.2398L0.196998 23.295ZM5.87658 19.8847L5.84025 19.8665L5.80154 19.8788L2.78138 20.8398L3.73978 17.9646L3.75932 17.906L3.71562 17.8623L3.43104 17.5777C2.27704 15.8437 1.55796 13.8245 1.55796 11.6637C1.55796 6.03288 6.03288 1.55796 11.6637 1.55796C17.2945 1.55796 21.7695 6.03288 21.7695 11.6637C21.7695 17.2945 17.2945 21.7695 11.6637 21.7695C9.64222 21.7695 7.76778 21.1921 6.18227 20.039L6.17557 20.0342L6.16817 20.0305L5.87658 19.8847Z" transform="translate(7.7758 7.77582)" fill="white" stroke="white" stroke-width="0.2"></path></svg></span></span></a>
                </li>
            </ul>
        </div> -->
        <!-- Product Quick View Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span class="dlicon ui-1_simple-remove" aria-hidden="true"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-0" id="product_details_output">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
