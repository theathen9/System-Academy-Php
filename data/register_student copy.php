<?php
function register_student($conn)
{
?>
    <div id="studentSection" class="card shadow border-0">

        <div class="card-body p-4 p-md-5">



            <!-- Student Information -->

            <h3 class="mb-4">Student Information</h3>
            <div class="d-flex justify-content-between">
                <div class="w-75">
                    <div class="row g-3 mb-3">
                        <input type="hidden" name="action" value="register_student">
                        <div class="col-md-4">
                            <input type="text" name="student_fst_name" class="form-control" placeholder="First Name Khmer" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="student_middle_name" class="form-control" placeholder="Middle Name Khmer">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="student_lst_name" class="form-control" placeholder="Last Name Khmer" required>
                        </div>

                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="student_fst_name_eng" class="form-control" placeholder="First Name English" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="student_middle_name_eng" class="form-control" placeholder="Middle Name English">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="student_lst_name_eng" class="form-control" placeholder="Last Name English" required>
                        </div>
                    </div>
                </div>
                <div class="w-25 text-center">

                    <label for="student_photo" style="cursor:pointer;">
                        <img id="studentPreviewPhoto"
                            src="../src/assets/register.png"

                            style="height:99px;width:99px;object-fit:cover;border-radius:6px;border:1px solid #ccc;">
                    </label>

                    <input
                        type="file"
                        name="student_photo"
                        id="student_photo"
                        accept="image/*"
                        style="display:none">

                    <div class="small text-muted mt-1">Click photo to upload</div>

                </div>
            </div>

            <div class="row g-3 mb-5">
                <div class="col-lg-3">
                    <input
                        type="text"
                        id="student_dob"
                        name="student_dob"
                        class="form-control"
                        placeholder="Date of Birth"
                        required>
                </div>
                <div class="col-lg-3">
                    <select name="student_gender" id="gender" class="form-control" required>
                        <option value="">-- Gender --</option>
                        <option value="Male">ប្រុស</option>
                        <option value="Female">ស្រី</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="student_academic_year" class="form-control" placeholder="Academic Year" required>
                </div>
            </div>

            <!-- Address -->

            <h3 class="mb-4">Address Date Of Birth</h3>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <select id="student_dob_addr_province" name="student_dob_addr_province" class="form-select" required>
                        <option value="">-- Province --</option>

                    </select>

                </div>
                <div class="col-md-6">
                    <select id="student_dob_addr_district" name="student_dob_addr_district" class="form-select" disabled required>
                        <option value="">-- District --</option>
                        <option value="other">-- Other --</option>
                    </select>
                    <input type="text" id="other_student_dob_addr_district" class="form-control" placeholder="Enter village name" style="display:none;">

                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <select id="student_dob_addr_commune" name="student_dob_addr_commune" class="form-select" disabled required>
                        <option value="">-- Commune --</option>
                        <option value="other">-- Other --</option>

                    </select>
                    <input type="text" id="other_student_dob_addr_commune" class="form-control" placeholder="Enter village name" style="display:none;">
                </div>
                <div class="col-md-6">
                    <select id="student_dob_addr_village" name="student_dob_addr_village" class="form-select" disabled required>
                        <option value="">-- Village --</option>
                        <option value="other">-- Other --</option>
                    </select>
                    <input type="text" id="other_student_dob_addr_village" class="form-control" placeholder="Enter village name" style="display:none;">
                </div>
            </div>

            <h3 class="mb-4">Current Address </h3>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <select id="student_curr_addr_province" name="student_curr_addr_province" class="form-select" required>
                        <option value="">-- Province --</option>

                    </select>

                </div>
                <div class="col-md-6">
                    <select id="student_curr_addr_district" name="student_curr_addr_district" class="form-select" disabled required>
                        <option value="">-- District --</option>
                        <option value="other">-- Other --</option>
                    </select>
                    <input type="text" id="other_student_curr_addr_district" class="form-control" placeholder="Enter village name" style="display:none;">

                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <select id="student_curr_addr_commune" name="student_curr_addr_commune" class="form-select" disabled required>
                        <option value="">-- Commune --</option>
                        <option value="other">-- Other --</option>

                    </select>
                    <input type="text" id="other_student_curr_addr_commune" class="form-control" placeholder="Enter village name" style="display:none;">
                </div>
                <div class="col-md-6">
                    <select id="student_curr_addr_village" name="student_curr_addr_village" class="form-select" disabled required>
                        <option value="">-- Village --</option>
                        <option value="other">-- Other --</option>
                    </select>
                    <input type="text" id="other_student_curr_addr_village" class="form-control" placeholder="Enter village name" style="display:none;">
                </div>
            </div>

            <!-- Contact Information -->

            <h3 class="mb-4">Contact Information</h3>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <input type="email" name="student_email" class="form-control" placeholder="Email Address">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <input type="tel" name="student_phone1" class="form-control" placeholder="Phone 1">
                </div>
                <div class="col-md-6">
                    <input type="tel" name="student_phone2" class="form-control" placeholder="Phone 2">
                </div>
            </div>

            <!-- guardian imformation -->

            <h3 class="mb-4">Parent/Guardian Information</h3>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input type="text" name="student_guardian1_fst_name" class="form-control" placeholder="Guardian First Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="student_guardian1_lst_name" class="form-control" placeholder="Guardian Last Name">
                </div>
                <div class="col-md-4">
                    <select name="student_guardian1_relationship" class="form-select" required>
                        <option value="">-- Relationship --</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input type="text" name="student_guardian2_fst_name" class="form-control" placeholder="Guardian First Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="student_guardian2_lst_name" class="form-control" placeholder="Guardian Last Name">
                </div>
                <div class="col-md-4">
                    <select name="student_guardian2_relationship" class="form-select" required>
                        <option value="">-- Relationship --</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                    </select>
                </div>

            </div>
            <!-- Phone -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input type="text" name="student_guardian1_phone" class="form-control" placeholder="Guardian 1 Phone Number " required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="student_guardian2_phone" class="form-control" placeholder="Guardian 2 Phone Number ">
                </div>
                <div class="col-md-4">
                    <input type="text" name="student_guardian_email" class="form-control" placeholder="Guardian Email">
                </div>
            </div>



            <h3 class="mb-4">Parent/Guardian Current Address </h3>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <select id="student_guardian_curr_addr_province" name="student_guardian_curr_addr_province" class="form-select" required>
                        <option value="">-- Province --</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select id="student_guardian_curr_addr_district" name="student_guardian_curr_addr_district" class="form-select" disabled required>
                        <option value="">-- District --</option>
                        <option value="other">-- Other --</option>

                    </select>
                    <input type="text" id="other_student_guardian_curr_addr_district" class="form-control" placeholder="Enter village name" style="display:none;">

                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <select id="student_guardian_curr_addr_commune" name="student_guardian_curr_addr_commune" class="form-select" disabled required>
                        <option value="">-- Commune --</option>
                        <option value="other">-- Other --</option>

                    </select>
                    <input type="text" id="other_student_guardian_curr_addr_commune" class="form-control" placeholder="Enter village name" style="display:none;">

                </div>
                <div class="col-md-6">
                    <select id="student_guardian_curr_addr_village" name="student_guardian_curr_addr_village" class="form-select" disabled required>
                        <option value="">-- Village --</option>
                        <option value="other">-- Other --</option>
                    </select>
                    <input type="text" id="other_student_guardian_curr_addr_village" class="form-control" placeholder="Enter village name" style="display:none;">

                </div>
            </div>




            <div class="w-100 d-flex justify-content-center mt-5">
                <div>
                    <button type="button" id="printBtn" class="btn btn-primary">
                        Print
                    </button>
                    <button type="submit" style="width: 99px;" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </div>
            
            <!-- PAYMENT MODAL -->
            <div class="modal fade" id="modalPayment" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Payment Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <!-- hidden student id -->
                            <input type="hidden" id="student_id">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" id="invoice" class="form-control" placeholder="Invoice No">
                                </div>

                                <div class="col-md-4">
                                    <input type="number" id="amount" class="form-control" placeholder="Amount">
                                </div>

                                <div class="col-md-4">
                                    <input type="date" id="date" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <select id="method" class="form-select">
                                        <option value="">Payment Method</option>
                                        <option>Cash</option>
                                        <option>ABA</option>
                                        <option>Wing</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" id="trx" class="form-control" placeholder="Transaction ID">
                                </div>

                                <div class="col-12">
                                    <textarea id="note" class="form-control" placeholder="Note"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="btnSavePayment" class="btn btn-success">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

}
?>