<?php require_once '_master/head.php' ?>

    <h1>Transaction Examples</h1>
    <p>Here you can easily test different transactions with just simple clicks. You can also check the code for every transaction inside this <code>./examples/{transaction}</code> directory.</p>


    <div class="mb-3" id="accordion">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapse">
                        Read first: About these examples
                    </button>
                </h5>
            </div>
            <div id="collapse" class="collapse" data-parent="#accordion">
                <div class="card-body pb-1">
                    <div class="alert alert-secondary small">
                        Logs use Klogger with the highest log setting (debug). You can check them out in
                        <code>./logs</code>.
                    </div>

                    <div class="alert alert-info small">
                        <p>This examples also expose Webhooks. To use them, expose your PC to Internet and use these
                            examples through the public URL, or Flow won't be able to reach them.</p>
                        You can use <a href="https://ngrok.com" target="_blank">ngrok</a> or similars. On success, you
                        will see a <code>webhook.txt</code> in the transaction directory.
                    </div>

                    <div class="alert alert-info small">
                        If you don't want to put your <strong>real</strong> email for the transactions, you can use <a href="https://www.mailinator.com/" target="_blank">Mailinator</a>, <a href="https://www.guerrillamail.com" target="_blank">Guerrillamail</a>, <a href="https://maildrop.cc/" target="_blank">Maildrop</a> or similars.
                    </div>

                    <div class="alert alert-warning small">
                        Don't copy & paste these examples into your app! These are just for illustration purposes, are very verbose, and may incur in severe <strong>security vulnerabilities</strong> for your application!
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="list-group mb-3">
        <a href="<?php echo currentUrlPath('payment') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-money-bill-alt"></i> Payment</h5>
            Creates a Normal Payment
        </a>
        <a href="<?php echo currentUrlPath('email') ?>-payment" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-envelope"></i> Email Payment</h5>
            Creates an Email Payment
        </a>
        <a href="<?php echo currentUrlPath('refund') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-undo"></i> Refund</h5>
            Refunds an existing Payment.
        </a>
        <a href="<?php echo currentUrlPath('customer') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-user"></i> Customer</h5>
            Creates, Retrievies, Updates, Lists and Deletes a Customer.
        </a>
        <a href="<?php echo currentUrlPath('card') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-credit-card"></i> Credit Card</h5>
            Register a Credit Card, and Unregisters it from an existing Customer.
        </a>
        <a href="<?php echo currentUrlPath('charge') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-receipt"></i> Charge</h5>
            Charges a Customer, List his Charges and Reverses a Charge
        </a>
        <a href="<?php echo currentUrlPath('plan') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-file-invoice"></i> Plan</h5>
            Creates a Plan, edits a Plan, list the Plans and deletes the Plan.
        </a>
        <a href="<?php echo currentUrlPath('subscription') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-address-card"></i> Subscription</h5>
            Creates, Retrieves, Lists a Subscriptions for a Plan, Updates and Cancels a Subscription.
        </a>
        <a href="<?php echo currentUrlPath('coupon') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-file-invoice-dollar"></i> Coupon</h5>
            Creates, Retrieves, Updates, Lists and Deletes a Coupon.
        </a>
        <a href="<?php echo currentUrlPath('subscription-coupon') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-address-card"></i> Subscription with Coupon</h5>
            Attach and Detach a Coupon from a existing Subscription.
        </a>
        <a href="<?php echo currentUrlPath('invoice') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-file-invoice-dollar"></i> Invoice</h5>
            Retrieves an Invoice
        </a>
        <a href="<?php echo currentUrlPath('settlement') ?>" class="list-group-item list-group-item-action">
            <h5><i class="fas fa-fw fa-file-invoice-dollar"></i> Settlement</h5>
            Retrieves a Settlement by ID or Date.
        </a>
    </div>

<?php require_once '_master/footer.php' ?>