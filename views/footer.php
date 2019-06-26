<!--</div>-->
<hr>
<div id="footer">
  MGP &copy;  )
</div>
<script>var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
        var UserEdit = document.getElementById('edit') || '';
        UserEdit.onclick = function() {
        <?php if (Session::get('role') == 'Owner'): ?>
        for (var i = 1; i <= 7; i++) {
        <?php else: ?>
        for (var i = 1; i <= 6; i++) {
        <?php endif ?>
        document.getElementById('inp' + i).readOnly = false;
        document.getElementById('inp' + i).removeAttribute('style');
    }
    }
    
        $(document).ready(function () {
        var stuedit = document.getElementById('stuedit') || '';
        stuedit.onclick = function() {
            $('#editable').empty();
            $('#editable').append("<?php echo $this->bufferform; ?>");
            };
         });
        
        function confirm_delete() {
        if (confirm('Are you sure?')) {
            return true;
        } else {
            
        }
        }

</script>
</body>
</html>

