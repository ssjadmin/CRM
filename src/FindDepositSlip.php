<?php
/*******************************************************************************
 *
 *  filename    : FindDepositSlip.php
 *  last change : 2016-02-28
 *  website     : http://www.churchcrm.io
 *  copyright   : Copyright 2016 ChurchCRM
 *
 *  ChurchCRM is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

$iDepositSlipID = $_SESSION['iCurrentDeposit'];

//Set the page title
$sPageTitle = gettext("Deposit Listing");

// Security: User must have finance permission to use this form
if (!$_SESSION['bFinance']) {
  Redirect("index.php");
  exit;
}

require "Include/Header.php";
?>

<!-- Delete Confirm Modal -->
<div id="confirmDelete" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= gettext("Confirm Delete") ?></h4>
      </div>
      <div class="modal-body">
        <p><?= gettext("Are you sure you want to delete the selected"); ?> <span
            id="deleteNumber"></span> <?= gettext("Deposit(s)"); ?>?
        </p>
        <p><?= gettext("This will also delete all payments associated with this deposit"); ?></p>
        <p><?= gettext("This action CANNOT be undone, and may have legal implications!") ?></p>
        <p><?= gettext("Please ensure this what you want to do.") ?></p>
        <button type="button" class="btn btn-danger" id="deleteConfirmed"><?php echo gettext("Delete"); ?></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?= gettext("Close"); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- End Delete Confirm Modal -->

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo gettext("Add New Deposit: "); ?></h3>
  </div>
  <div class="box-body">
    <form action="#" method="get" class="form">
      <div class="row">
        <div class="container-fluid">
          <div class="col-lg-4">
            <label for="addNewGruop"><?= gettext("Deposit Comment") ?></label>
            <input class="form-control newDeposit" name="depositComment" id="depositComment" style="width:100%">
          </div>
          <div class="col-lg-3">
            <label for="depositType"><?= gettext("Deposit Type") ?></label>
            <select class="form-control" id="depositType" name="depositType">
              <option value="Bank"><?= gettext("Bank") ?></option>
              <option value="CreditCard"><?= gettext("Credit Card") ?></option>
              <option value="BankDraft"><?= gettext("Bank Draft") ?></option>
              <option value="eGive"><?= gettext("eGive") ?></option>
            </select>
          </div>
          <div class="col-lg-3">
            <label for="addNewGruop"><?= gettext("Deposit Date") ?></label>
            <input class="form-control" name="depositDate" id="depositDate" style="width:100%" class="date-picker">
          </div>
        </div>
      </div>
      <p>
      <div class="row">
        <div class="col-xs-3">
          <button type="button" class="btn btn-primary" id="addNewDeposit"><?= gettext("Add New Deposit") ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo gettext("Deposits: "); ?></h3>
  </div>
  <div class="box-body">
    <div class="container-fluid">
      <table class="display responsive nowrap data-table" id="depositsTable" width="100%"></table>

      <button type="button" id="deleteSelectedRows" class="btn btn-danger"
              disabled> <?= gettext("Delete Selected Rows") ?> </button>
      <button type="button" id="exportSelectedRows" class="btn btn-success exportButton" data-exportType="ofx"
              disabled><i class="fa fa-download"></i> <?= gettext("Export Selected Rows (OFX)") ?></button>
      <button type="button" id="exportSelectedRowsCSV" class="btn btn-success exportButton" data-exportType="csv"
              disabled><i class="fa fa-download"></i> <?= gettext("Export Selected Rows (CSV)") ?></button>
      <button type="button" id="generateDepositSlip" class="btn btn-success exportButton" data-exportType="pdf"
              disabled> <?= gettext("Generate Deposit Split for Selected Rows (PDF)") ?></button>
    </div>
  </div>
</div>
<script src="<?= $sRootPath; ?>/skin/js/FindDepositSlip.js"></script>

<?php require "Include/Footer.php" ?>
