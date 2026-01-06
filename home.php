<?php 
   
    include 'includes/header.php';

?>
<div class="container" style="text-align: center; padding: 50px;">
    <h1>Welcome to College Helpdesk</h1>
    <p>A unified platform for Students and Faculty to resolve issues.</p>
    <br>
    <a href="create_ticket.php" class="btn btn-success" style="font-size: 19px;">+ Create New Ticket</a>
    <a href="view_ticket.php" class="btn btn-secondary" style="font-size: 19px;">Check Ticket Status</a>
     <script>
        document.addEventListener("DOMContentLoaded",function(){
        const sidebar = document.getElementById("sidebar");
        const opensidebarBtn = document.getElementById("opensidebarBtn");
        const closebtn = document.getElementById("closesidebar");


        if(opensidebarBtn && sidebar){
        opensidebarBtn.addEventListener("click", function(e)  {
            e.preventDefault();
            sidebar.classList.add("open");
        });
        }

        if(closeBtn && sidebar){
        closebtn.addEventListener("click", function() {
            sidebar.classList.remove("open");
        });
        }
        });
    </script>
</div>
<?php 
include 'includes/footer.php'; 
?>
