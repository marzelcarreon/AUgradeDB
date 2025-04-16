<div class="modal fade" id="deletethesesModal" tabindex="-1" role="dialog" aria-labelledby="deletethesesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletethesesModalLabel">Delete theses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="deletethesesForm" action="process-theses-deletion.php" method="POST">
          <input type="hidden" name="numID" id="deletethesesInput">
          <p>Are you sure you want to delete this theses?</p><br>
          <div class="text-center">
            <button type="submit" class="btn btn-danger" name="btnsubmit">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
