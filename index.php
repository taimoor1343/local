<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project Inquiry Form with reCAPTCHA</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google reCAPTCHA v2 Script -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <style>
    * { box-sizing: border-box; font-family: system-ui, -apple-system, sans-serif; }
    body {
      background: #f1f5f9;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 24px 16px;
    }
    .form-card {
      background: #ffffff;
      padding: 36px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
      width: 100%;
      max-width: 680px;
    }
    .form-card h2 {
      margin: 0 0 6px;
      font-size: 24px;
      font-weight: 700;
      color: #0f172a;
    }
    .form-card p {
      margin: 0 0 24px;
      font-size: 14px;
      color: #64748b;
    }
    .form-label {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }
    .form-control, .form-select {
      padding: 10px 14px;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      font-size: 14px;
      color: #0f172a;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
      border-color: #0284c7;
      box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .form-check-input:checked {
      background-color: #0b5375;
      border-color: #0b5375;
    }
    .form-check-label {
      font-size: 13.5px;
      color: #475569;
    }
    .submit-btn {
      width: 100%;
      height: 48px;
      margin-top: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(90deg, #0b5375, #18a5b5);
      color: #ffffff;
      font-size: 15px;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: opacity 0.3s ease;
    }
    .submit-btn:hover:not(:disabled) {
      opacity: 0.94;
    }
    .submit-btn:disabled {
      opacity: 0.85;
      cursor: not-allowed;
    }
    .submit-btn.is-submitted {
      cursor: default;
    }
    .status-msg {
      margin-top: 12px;
      font-size: 13px;
      text-align: center;
      color: #ef4444;
    }
    .bg-video {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      z-index: -1;
      pointer-events: none;
    }
  </style>
</head>
<body>
  <video class="bg-video" autoplay muted loop playsinline>
    <source src="assets/video/bg.mp4" type="video/mp4">
  </video>

  <div class="form-card">
    <h2>Project &amp; Service Inquiry</h2>
    <p>Fill out your details below and our team will get back to you shortly.</p>

    <form id="contactForm" class="row g-3">
      <!-- Row 1: Full Name & Email -->
      <div class="col-md-6">
        <label for="inputName" class="form-label">Full Name *</label>
        <input type="text" class="form-control" id="inputName" name="name" placeholder="e.g. Alice Johnson" required>
      </div>
      <div class="col-md-6">
        <label for="inputEmail" class="form-label">Email Address *</label>
        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="e.g. alice@company.com" required>
      </div>

      <!-- Row 2: Phone & Service Dropdown -->
      <div class="col-md-6">
        <label for="inputPhone" class="form-label">Phone Number *</label>
        <input type="tel" class="form-control" id="inputPhone" name="phone" placeholder="+1 (555) 234-5678" required>
      </div>
      <div class="col-md-6">
        <label for="inputService" class="form-label">Service Needed *</label>
        <select id="inputService" name="service" class="form-select" required>
          <option value="" selected disabled>Choose a service...</option>
          <option value="Web Development">Web Development</option>
          <option value="UI/UX Design">UI/UX Design</option>
          <option value="Mobile App">Mobile App Development</option>
          <option value="SEO & Marketing">SEO &amp; Digital Marketing</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <!-- Row 3: Street Address -->
      <div class="col-12">
        <label for="inputAddress" class="form-label">Street Address</label>
        <input type="text" class="form-control" id="inputAddress" name="address" placeholder="1234 Main St, Suite 100">
      </div>

      <!-- Row 4: City, State/Province, Zip -->
      <div class="col-md-6">
        <label for="inputCity" class="form-label">City</label>
        <input type="text" class="form-control" id="inputCity" name="city" placeholder="e.g. Lahore">
      </div>
      <div class="col-md-4">
        <label for="inputState" class="form-label">State / Province</label>
        <select id="inputState" name="state" class="form-select">
          <option value="" selected>Choose...</option>
          <option value="Punjab">Punjab</option>
          <option value="Sindh">Sindh</option>
          <option value="KPK">Khyber Pakhtunkhwa</option>
          <option value="Balochistan">Balochistan</option>
          <option value="Islamabad">Islamabad Capital Territory</option>
          <option value="International">Outside Pakistan</option>
        </select>
      </div>
      <div class="col-md-2">
        <label for="inputZip" class="form-label">Zip</label>
        <input type="text" class="form-control" id="inputZip" name="zip" placeholder="54000">
      </div>

      <!-- Row 5: Message / Project Details -->
      <div class="col-12">
        <label for="inputMessage" class="form-label">Project Details / Message *</label>
        <textarea class="form-control" id="inputMessage" name="message" rows="3" placeholder="Tell us a bit about your timeline, goals, or requirements..." required></textarea>
      </div>

      <!-- Row 6: Agreement Checkbox -->
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="gridCheck" name="newsletter" value="Yes">
          <label class="form-check-label" for="gridCheck">
            Send me occasional updates and project tips via email.
          </label>
        </div>
      </div>

      <!-- Row 7: reCAPTCHA v2 Widget (Placed right before the submit button) -->
      <div class="col-12 d-flex justify-content-center my-2">
        <div class="g-recaptcha" data-sitekey="6LcCUCMuAAAAACFFnMKMruznthEEah6g9gdoX_8u"></div>
      </div>

      <!-- Row 8: Submit Button -->
      <div class="col-12">
        <button type="submit" id="submitBtn" class="submit-btn">
          <span id="btnText">Submit</span>
        </button>
        <div id="statusMsg" class="status-msg"></div>
      </div>
    </form>
  </div>

  <script>
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const statusMsg = document.getElementById('statusMsg');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (submitBtn.classList.contains('is-submitted')) return;

      // Verify reCAPTCHA token is generated on frontend
      const recaptchaResponse = grecaptcha.getResponse();
      if (!recaptchaResponse) {
        statusMsg.textContent = 'Please complete the reCAPTCHA verification.';
        return;
      }

      statusMsg.textContent = '';
      submitBtn.disabled = true;
      btnText.textContent = 'Sending...';

      const formData = new FormData(form);

      try {
        const response = await fetch('send.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          submitBtn.classList.add('is-submitted');
          btnText.textContent = 'Submitted';
          form.reset();
          grecaptcha.reset(); // Reset captcha widget
        } else {
          throw new Error(result.message || 'Failed to send email.');
        }
      } catch (err) {
        submitBtn.disabled = false;
        btnText.textContent = 'Submit';
        statusMsg.textContent = err.message;
        grecaptcha.reset(); // Reset captcha on failure
      }
    });
  </script>
</body>
</html>