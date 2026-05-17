<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-check me-2"></i>
                    Create Attendance
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <form action="save_attendance.php" method="POST">

                <div class="modal-body">

                    <!-- TOP -->
                    <div class="row g-3 mb-4">

                        <!-- Class -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Class
                            </label>

                            <select name="class_id"
                                id="classSelect"
                                class="form-select"
                                required>

                                <option value="">Select Class</option>

                                <?php
                                $classes = $classCRUD
                                    ->select("
                                        cl.class_id,
                                        cl.class_name,
                                        cl.class_code
                                    ")
                                    ->where("cl.status", "=", "Active")
                                    ->get();

                                foreach ($classes as $class):
                                ?>
                                    <option value="<?= $class['class_id'] ?>">
                                        <?= $class['class_name'] ?>
                                        (<?= $class['class_code'] ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Attendance Date
                            </label>

                            <input type="date"
                                name="attendance_date"
                                class="form-control"
                                value="<?= date('Y-m-d') ?>"
                                required>
                        </div>
                    </div>

                    <!-- Student List -->
                    <div class="border rounded p-3 bg-light">

                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="m-0">
                                Student Attendance
                            </h6>

                            <button type="button"
                                class="btn btn-sm btn-success"
                                id="markAllPresent">
                                Mark All Present
                            </button>
                        </div>

                        <div style="max-height:500px; overflow:auto;">

                            <table class="table table-bordered align-middle bg-white">

                                <thead class="table-light">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Student Name</th>
                                        <th width="180">Status</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>

                                <tbody id="attendanceStudentList">

                                    <!-- AJAX LOAD -->

                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-primary">
                        Save Attendance
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>