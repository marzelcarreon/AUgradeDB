<style>
#txttitle, #txtauthors, #txtresearchadviser {
     width: 100%;
}
    </style>

<div class="modal fade" id="createthesesModal" tabindex="-1" role="dialog" aria-labelledby="createthesesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createthesesModalLabel">Create new theses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form id="createthesesForm" action="theses-management.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="form-group">
            
            <label for="txttitle">Title:</label>
            <input type="text" class="form-control" id="txttitle" name="txttitle" required>
        </div>
        <div class="form-group">
            <label for="txtauthors">Authors:</label>
             <input type="text" class="form-control" id="txtauthors" name="txtauthors" required>
        </div>
        <div class="form-group">
            <label for="txtresearchadviser">Research Adviser:</label>
             <input type="text" class="form-control" id="txtresearchadviser" name="txtresearchadviser" required>
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
        <div class="form-group">
            <label for="txtfilelink">Filelink:</label>
             <input type="file" class="form-control" id="txtfilelink" name="txtfilelink" accept=".pdf,.doc,.docx" required>
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
  