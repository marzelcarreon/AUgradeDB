<div class="modal fade" id="deletegradeModal" tabindex="-1" role="dialog" aria-labelledby="deletegradeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletegradeModalLabel">Delete grade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="deletegradeForm" action="process-grade-deletion.php" method="POST">
        <input type="hidden" name="txtstudentnumber" id="deletegradeStudentNumber">
        <input type="hidden" name="txtcode" id="deletegradeCode">
        <input type="hidden" name="txtgrade" id="deletegradeGrade">
          <p>Are you sure you want to delete this grade?</p><br>
          <div class="text-center">
            <button type="submit" class="btn btn-danger" name="btnsubmit">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
