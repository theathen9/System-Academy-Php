<?php
// register.php
include_once 'config/db.php';

// Fetch courses from DB (MySQLi compatible)
$courses = [];
$result = $mysqli->query("SELECT * FROM tblcourse ORDER BY course_name");
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Step form styling */
    .step {
      display: none;
    }

    .step.active {
      display: block;
    }

    #progressbar {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    #progressbar div {
      width: 24%;
      padding: 10px;
      background: #ccc;
      text-align: center;
      border-radius: 5px;
      position: relative;
    }

    #progressbar div.active {
      background: #0d6efd;
      color: #fff;
    }

    #progressbar div.active::after {
      content: "✓";
      display: block;
      font-size: 0.8em;
      margin-top: 5px;
    }
  </style>
</head>

<body class="container my-4">

  <h2 class="mb-4">Student Registration</h2>

  <form id="regForm" action="register_process.php" method="post" enctype="multipart/form-data">

    <!-- Progress bar -->
    <div id="progressbar">
      <div class="active">Student Info</div>
      <div>Address</div>
      <div>Parent Info</div>
      <div>Enrollment/Payment</div>
    </div>

    <!-- Step 1: Student Info -->
    <div class="step active">
      <h4>Step 1: Student Info</h4>
      <div class="mb-3">
        <label>First Name Khmer</label>
        <input type="text" name="first_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Last Name Khmer</label>
        <input type="text" name="last_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Date of Birth</label>
        <input type="date" name="dob" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Gender</label>
        <select name="gender" class="form-select" required>
          <option value="">-- Select --</option>
          <option value="ប្រុស">ប្រុស</option>
          <option value="ស្រី">ស្រី</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewPhoto(event)" required>
        <img id="photoPreview" style="max-width:150px; display:block; margin-top:10px;">
      </div>
    </div>

    <!-- Step 2: Address -->
    <div class="step">
      <h4>Step 2: Address</h4>
      <div class="mb-3">
        <label>Current Address</label>
        <input type="text" name="address" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="mb-3">
        <label>Phone</label>
        <input type="tel" name="phone" class="form-control" required>
      </div>
    </div>

    <!-- Step 3: Parent Info -->
    <div class="step">
      <h4>Step 3: Parent Info</h4>
      <div class="mb-3">
        <label>Father Name</label>
        <input type="text" name="father_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Mother Name</label>
        <input type="text" name="mother_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Parent Phone</label>
        <input type="tel" name="parent_phone" class="form-control" required>
      </div>
    </div>

    <!-- Step 4: Enrollment & Payment -->
    <div class="step">
      <h4>Step 4: Enrollment & Payment</h4>
      <div class="mb-3">
        <label>Select Courses</label>
        <select name="courses[]" class="form-select" multiple required>
          <?php foreach ($courses as $course): ?>
            <option value="<?= $course['course_id'] ?>"><?= htmlspecialchars($course['course_name']) ?> (<?= $course['level'] ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Payment Type</label>
        <select name="payment_type" class="form-select" required>
          <option value="">-- Select --</option>
          <option value="cash">Cash</option>
          <option value="card">Card</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Amount</label>
        <input type="number" name="amount" class="form-control" required>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-between mt-3">
      <button type="button" id="prevBtn" class="btn btn-secondary" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextPrev(1)">Next</button>
    </div>

  </form>

  <script>
    // Multi-step form JS
    let currentStep = 0;
    showStep(currentStep);

    function showStep(n) {
      const steps = document.getElementsByClassName("step");
      for (let step of steps) {
        step.style.display = "none";
      }
      steps[n].style.display = "block";

      document.getElementById("prevBtn").style.display = n == 0 ? "none" : "inline";
      document.getElementById("nextBtn").innerHTML = n == (steps.length - 1) ? "Submit" : "Next";

      // Progress bar
      const progress = document.querySelectorAll("#progressbar div");
      progress.forEach((el, i) => el.classList.toggle("active", i <= n));
    }

    function nextPrev(n) {
      if (n == 1 && !validateForm()) return false;

      const steps = document.getElementsByClassName("step");
      steps[currentStep].style.display = "none";
      currentStep += n;

      if (currentStep >= steps.length) {
        document.getElementById("regForm").submit();
        return false;
      }

      showStep(currentStep);
    }

    function validateForm() {
      const steps = document.getElementsByClassName("step");
      const inputs = steps[currentStep].querySelectorAll("input, select, textarea");

      for (let input of inputs) {
        if (input.hasAttribute("required") && input.value === "") {
          alert("Please fill all required fields");
          return false;
        }
      }

      // Special check for multiple select courses
      if (currentStep === 3) { // enrollment step
        const courseSelect = document.querySelector('select[name="courses[]"]');
        if (courseSelect.selectedOptions.length === 0) {
          alert("Please select at least one course");
          return false;
        }
      }

      return true;
    }

    // Photo preview
    function previewPhoto(event) {
      const reader = new FileReader();
      reader.onload = function() {
        document.getElementById("photoPreview").src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>

</body>

</html>