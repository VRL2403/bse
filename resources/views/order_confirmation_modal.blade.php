<!-- modal.blade.php -->
<div class="modal fade hidden" id="order_confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="--bs-modal-width: 60rem !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Please Confirm the Order:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="task_form" class="form-horizontal form-label-left">

                    @csrf
                    <!-- Table to display your data -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Buy Quantity</th>
                                <th>Buy Value</th>
                                <th>Sell Quantity</th>
                                <th>Sell Value</th>
                                <th>Brokerage</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
            </div>
            <div class="modal-footer">
                <label>Order Total:</label>
                <label class="order_total"></label>
                <button type="button" class="btn btn-primary" id="confirm_and_place" data-dismiss="modal">Place Order</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>