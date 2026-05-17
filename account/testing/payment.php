<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Payment Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="payment_student_id">

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <input type="text" id="invoice_no" class="form-control" placeholder="Invoice Number">
          </div>
          <div class="col-md-4">
            <input type="number" id="amount" class="form-control" placeholder="Amount ($)">
          </div>
          <div class="col-md-4">
            <input type="date" id="payment_date" class="form-control">
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <select id="payment_method" class="form-select">
              <option value="">-- Payment Method --</option>
              <option value="Cash">Cash</option>
              <option value="ABA">ABA</option>
              <option value="Wing">Wing</option>
              <option value="Bank Transfer">Bank Transfer</option>
            </select>
          </div>

          <div class="col-md-6">
            <input type="text" id="transaction_id" class="form-control" placeholder="Transaction ID">
          </div>
        </div>

        <div class="mb-3">
          <textarea id="note" class="form-control" placeholder="Note"></textarea>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="savePaymentBtn" class="btn btn-success">Save Payment</button>
      </div>

    </div>
  </div>
</div>