<style>
    /* Eye icon */
    .eye-icon {
        position: absolute;
        right: 3px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px; 
        height: auto; 
        cursor: pointer; 
    }

    /* Password input */
    #txtpassword {
        padding-right: 30px; 
    }

    /* Modal footer button alignment */
    .modal-footer .btn {
        margin-right: 5px;
    }
    #txtusername {
    width: 100%;
}
</style>

<div class="modal fade" id="createAccountModal" tabindex="-1" role="dialog" aria-labelledby="createAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createAccountModalLabel">Create new account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form id="createAccountForm" action="accounts-management.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            
            <label for="txtusername">Username:</label>
            <input type="text" class="form-control" id="txtusername" name="txtusername" required>
        </div>
        <div class="form-group">
            <label for="txtpassword">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="txtpassword" name="txtpassword" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword">
                            <img src="https://static.thenounproject.com/png/4334035-200.png" alt="Show Password" id="eyeIcon" class="eye-icon" onclick="togglePasswordVisibility()">
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
            <label for="txtretypepassword">Re-type Password:</label>
                <input type="password" class="form-control" id="txtretypepassword" name="txtretypepassword" required>
            </div>
            <div class="form-group">
            <label for="cmbtype">Account type:</label>
                <select class="form-control" id="cmbtype" name="cmbtype" required>
                    <option value="">--Select Account type--</option>
                    <option value="ADMINISTRATOR">--ADMINISTRATOR--</option>
                    <option value="REGISTRAR">--REGISTRAR--</option>
                    <option value="STAFF">--STAFF--</option>
                    <option value="STUDENT">--STUDENT--</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="btnsubmit">Submit</button>
                <a href="accounts-management.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
      function validateForm() {
        var password = document.getElementById("txtpassword").value;
        var retypePassword = document.getElementById("txtretypepassword").value;

        if (password !== retypePassword) {
            alert("Retyped password does not match.");
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }

    // Clear username field in the modal after successful submission
    function clearUsernameField() {
        document.getElementById("txtusername").value = "";
    }
    // Function to toggle password visibility
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("txtpassword");
        var eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.src = "https://icons.veryicon.com/png/o/miscellaneous/computer-room-integration/hide-password.png";
        } else {
            passwordInput.type = "password";
            eyeIcon.src = "https://static.thenounproject.com/png/4334035-200.png";
        }
    }
</script>