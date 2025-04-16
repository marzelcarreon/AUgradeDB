<style>
    #txtgrade, #txtcourse {
        width: 100%;
    }
</style>

<div class="modal fade" id="createGradeModal" tabindex="-1" role="dialog" aria-labelledby="createGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createGradeModalLabel">Create new Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createGradeForm" action="process-Grade-creation.php" method="POST">
                    <div class="form-group">
                        <label for="txtgrade">Grade Number:</label>
                        <input type="text" class="form-control" id="txtgrade" name="txtgrade" required>
                    </div>
                    <div class="form-group">
                        <label for="txtlastname">Last Name:</label>
                        <input type="text" class="form-control" id="txtlastname" name="txtlastname" required>
                    </div>
                    <div class="form-group">
                        <label for="txtfirstname">First Name:</label>
                        <input type="text" class="form-control" id="txtfirstname" name="txtfirstname" required>
                    </div>
                    <div class="form-group">
                        <label for="txtmiddlename">Middle Name:</label>
                        <input type="text" class="form-control" id="txtmiddlename" name="txtmiddlename" required>
                    </div>
                    <div class="form-group">
                        <label for="cmbcourse">Course:</label>
                        <select class="form-control" id="cmbcourse" name="cmbcourse" required>
                            <option value="">--Select Course--</option>
                            <option value="BSHM">--Bachelor of Science in Hospitality Management--</option>
                            <option value="BSTM">--Bachelor of Science in Tourism Management--</option>
                            <option value="BSBA">--Bachelor of Science in Business Administration--</option>
                            <option value="BAEPPS">--Bachelor of Arts in English Language, Psychology, Political Science--</option>
                            <option value="BSE">--Bachelor of Secondary Education--</option>
                            <option value="BEE">--Bachelor of Elementary Education--</option>
                            <option value="BPE">--Bachelor of Physical Education--</option>
                            <option value="BSCS">--Bachelor of Science in Computer Science--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cmbtype">Year Level:</label>
                        <select class="form-control" id="cmbtype" name="cmbtype" required>
                            <option value="">--Select Year Level--</option>
                            <option value="1st">--1ST YEAR--</option>
                            <option value="2nd">--2ND YEAR--</option>
                            <option value="3rd">--3RD YEAR--</option>
                            <option value="4th">--4TH YEAR--</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" name="btnsubmit">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Clear username field in the modal after successful submission
    function clearUsernameField() {
        document.getElementById("txtgrade").value = "";
    }
</script>
