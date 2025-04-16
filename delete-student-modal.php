<div class="modal fade" id="deletestudentModal" tabindex="-1" role="dialog" aria-labelledby="deletestudentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletestudentModalLabel">Delete Student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="deletestudentForm" action="process-student-deletion.php" method="POST">
          <input type="hidden" name="txtstudentnumber" id="deletestudentnumberInput">
          <p>Are you sure you want to delete this student?</p><br>
          <div class="text-center">
            <button type="submit" class="btn btn-danger" name="btnsubmit">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
