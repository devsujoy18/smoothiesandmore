<!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Get in Touch with <span class="text-primary-custom">Smoothies & More</span>
                </h2>
                <p class="text-muted">We'd love to hear from you or see you in person!</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact-form-card">
                        <h4 class="Contact-title">Contact Us</h4>
                        <form id="contact-form" action="https://api.web3forms.com/submit" method="POST">
                            <!-- Web3Forms Access Key - You will need to replace this with your actual key from web3forms.com -->
                            <input type="hidden" name="access_key" value="b1177f57-44db-4a7f-9219-9311b0d75c60">
                            <input type="hidden" name="subject" value="New Contact Form Submission - Smoothies & More">
                            <input type="hidden" name="from_name" value="Smoothies & More Website">
                            <!-- Optional: Redirect to a thank you page -->
                            <!-- <input type="hidden" name="redirect" value="https://web3forms.com/success"> -->

                            <div class="mb-3">
                                <label for="name" class="form-label small text-muted">Name</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label small text-muted">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" id="phone">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label small text-muted">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label small text-muted">Address</label>
                                <textarea name="address" class="form-control" id="address" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label small text-muted">Message</label>
                                <textarea name="message" class="form-control" id="message" rows="4"></textarea>
                            </div>
                            <button type="submit" id="submit-btn" class="btn btn-primary w-100 mt-3 text-white">Send
                                Message</button>
                            <div id="form-status" class="mt-3 text-center small"></div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visit-card">
                        <h4 class="Contact-title">Visit Us</h4>
                        <ul class="list-unstyled">
                            <li class="mb-3"><strong class="d-block">Smoothies & More</strong>Link Road, Kalipark,
                                Bablatala, Gopalpur, Kolkata, India, 700136</li>
                            <li class="mb-3"><strong class="d-block">Phone:</strong> <a href="tel:+101234567889"
                                    class="text-decoration-none text-muted">+91 9147759811</a></li>
                            <li class="mb-3"><strong class="d-block">Email:</strong> <a
                                    href="mailto:contact@smoothiesnmore.com"
                                    class="text-decoration-none text-muted">contact@smoothiesnmore.com</a></li>
                            <li class="mb-3"><strong class="d-block">Hours:</strong> 12:00 PM - 9:00 PM Daily</li>
                        </ul>
                        <div class="map-placeholder d-flex align-items-center justify-content-center text-muted">

                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3682.4944626796614!2d88.4542479!3d22.635345799999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f89f52481d20e7%3A0x819fba1c6e0e77c5!2sSmoothies%20%26%20More%20%7C%20Best%20Cafe%20In%20Newtown%20of%20Rajarhat%20-%20Near%20Chinar%20Park!5e0!3m2!1sen!2sin!4v1768917534707!5m2!1sen!2sin"
                                width="100%" height="auto" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>