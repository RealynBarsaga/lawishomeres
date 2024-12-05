<?php
// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";    
$MySQL_database_name = "u510162695_db_barangay";

// Establishing connection with server
$conn = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Checking connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Setting the default timezone
date_default_timezone_set("Asia/Manila");

// ========================= Permit No ======================= //
$query = "SELECT orNo FROM tblpermit ORDER BY id DESC LIMIT 1"; 
$result = mysqli_query($conn, $query);
$lastPermit = mysqli_fetch_assoc($result);

if ($lastPermit) {
    $lastPermitNumber = $lastPermit['orNo'];
    $permitParts = explode('-', $lastPermitNumber);
    if (count($permitParts) == 3) {
        $year = $permitParts[0];
        $serial = $permitParts[2];
        $serialNumber = (int) $serial;
        $serialNumber++;
        $newSerial = str_pad($serialNumber, 4, '0', STR_PAD_LEFT);
    } else {
        $year = date('Y');
        $newSerial = '0001';
    }
} else {
    $year = date('Y');
    $newSerial = '0001';
}

$randomNumber = rand(1000000000, 9999999999);
$newRandomPart = str_pad($randomNumber, 10, '0', STR_PAD_LEFT);
$newPermitNumber = $year . '-' . $newRandomPart . '-' . $newSerial;

// ========================= Business ID ======================= //
$query = "SELECT bussinessidno FROM tblpermit ORDER BY id DESC LIMIT 1"; 
$result = mysqli_query($conn, $query);
$lastBusinessID = mysqli_fetch_assoc($result);

if ($lastBusinessID) {
    $lastBusinessIDValue = $lastBusinessID['bussinessidno'];
    $businessIDParts = explode('-', $lastBusinessIDValue);
    if (count($businessIDParts) == 3) {
        $businessIDPrefix = $businessIDParts[0];
        $serial = $businessIDParts[2];
        $serialNumber = (int) $serial;
        $serialNumber++;
        $newSerial = str_pad($serialNumber, 5, '0', STR_PAD_LEFT);
        $businessIDPrefix = chr(ord($businessIDPrefix) + 1);
        if ($businessIDPrefix > 'Z') {
            $businessIDPrefix = 'A';
        }
    } else {
        $businessIDPrefix = 'A';
        $newSerial = '00001';
    }
} else {
    $businessIDPrefix = 'A';
    $newSerial = '00001';
}

$randomNumberPart = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
$newBusinessID = $businessIDPrefix . '-' . $randomNumberPart . '-' . $newSerial;

// ========================= Official Receipt No ======================= //
$query = "SELECT offreceiptno FROM tblpermit ORDER BY id DESC LIMIT 1"; 
$result = mysqli_query($conn, $query);
$lastReceipt = mysqli_fetch_assoc($result);

if ($lastReceipt) {
    $lastReceiptNumber = $lastReceipt['offreceiptno'];
    $receiptParts = explode('/', $lastReceiptNumber);
    if (count($receiptParts) == 2) {
        $randomSerial = rand(1, 9999999);
        $newReceiptNumber = str_pad($randomSerial, 7, '0', STR_PAD_LEFT) . '/' . str_pad($randomSerial, 7, '0', STR_PAD_LEFT);
    } else {
        $randomSerial = rand(1, 9999999);
        $newReceiptNumber = str_pad($randomSerial, 7, '0', STR_PAD_LEFT) . '/' . str_pad($randomSerial, 7, '0', STR_PAD_LEFT);
    }
} else {
    $randomSerial = rand(1, 9999999);
    $newReceiptNumber = str_pad($randomSerial, 7, '0', STR_PAD_LEFT) . '/' . str_pad($randomSerial, 7, '0', STR_PAD_LEFT);
}
?>

<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
    <form method="post">
        <div class="modal-dialog modal-sm" style="width:597px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Manage Business Permit</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Name:</label>
                                <input name="txt_name" class="form-control input-sm" type="text" placeholder="Name" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                            </div>
                            <div class="form-group">
                                <label>Business Name:</label>
                                <input name="txt_busname" class="form-control input-sm" type="text" placeholder="Business Name" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                            </div>
                            <div class="form-group">
                                <label>Business Address:</label>
                                <input name="txt_busadd" class="form-control input-sm" type="text" placeholder="Business Address" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                            </div>
                            <div class="form-group">
                                <label>Type of Business:</label>
                                <input name="ddl_tob" class="form-control input-sm" type="text" placeholder="Type of Business" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>                                 
                            </div>
                            <div class="form-group">
                                <label>Permit No:</label>
                                <input name="txt_ornum" id="permit_no" class="form-control input-sm" type="text" placeholder="Permit No" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Amount:</label>
                                <input name="txt_amount" class="form-control input-sm" type="number" placeholder="Amount" required/>
                            </div>
                        </div>

                        <!-- Move "Former Address" to the left column -->
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Type of Application:</label>
                                <input name="txt_typeofapp" class="form-control input-sm" type="text" placeholder="Type of Application" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Line of Business:</label>
                                <input name="txt_lineofbus" class="form-control input-sm" type="text" placeholder="Line of Business" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Payment Mode:</label>
                                <input name="txt_paymode" class="form-control input-sm" type="text" placeholder="Payment Mode" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Official Receipt No:</label>
                                <input name="txt_offrecno" id="offrec_no" class="form-control input-sm" type="text" placeholder="Business ID No" readonly/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Business ID No:</label>
                                <input name="txt_busidno" id="busid_no" class="form-control input-sm" type="text" placeholder="Business ID No" readonly/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">OR Date:</label>
                                <input name="txt_ordate" id="ordate" class="form-control input-sm" type="text" placeholder="OR Date" readonly/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_add" value="Add"/>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pass PHP values to JavaScript
        const newPermitNumber = "<?php echo $newPermitNumber; ?>"; // PHP value passed to JS
        const newBusinessID = "<?php echo $newBusinessID; ?>"; // PHP value passed to JS
        const newReceiptNumber = "<?php echo $newReceiptNumber; ?>"; // PHP value passed to JS
        
        // Set the generated permit number and business ID to the input fields
        document.getElementById('permit_no').value = newPermitNumber;
        document.getElementById('busid_no').value = newBusinessID;
        document.getElementById('offrec_no').value = newReceiptNumber;
        
        // Calculate and set the OR Date (one day before the current date)
        const issuedDate = new Date(); // Today's date
        issuedDate.setDate(issuedDate.getDate() - 1); // Subtract 1 day to get the OR Date
        
        // Format the OR Date to YYYY-MM-DD
        const orDate = issuedDate.toISOString().split('T')[0]; // Format: '2024-01-10'
        
        // Set the OR Date value to the input field
        document.getElementById('ordate').value = orDate;
    });


    document.querySelector('form').addEventListener('submit', function(event) {
        // Check each required input field for empty or space-only values
        const requiredFields = document.querySelectorAll('input[required], select[required]');
        let isValid = true;
    
        requiredFields.forEach(function(field) {
            const value = field.value.trim(); // Remove leading/trailing spaces
            if (value === '') {
                // Show a custom alert or display the error message
                alert(`Please fill out the required field: ${field.placeholder || field.name}`);
                isValid = false;
                field.focus(); // Focus on the first empty required field
            }
        });
    
        if (!isValid) {
            event.preventDefault(); // Prevent form submission if there are invalid fields
        }
    });
</script>