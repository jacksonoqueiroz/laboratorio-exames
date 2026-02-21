    </div> <!-- container -->
</div> <!-- content -->
</div> <!-- wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function() {

    const btn = document.getElementById("sidebarCollapse");
    const sidebar = document.getElementById("sidebar");

    if(btn){
        btn.addEventListener("click", function(){
            sidebar.classList.toggle("active");
        });
    }

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const btnToggle = document.getElementById("sidebarCollapse");

    if (btnToggle) {
        btnToggle.addEventListener("click", function () {
            const dropdowns = sidebar.querySelectorAll(".dropdown-menu");
            dropdowns.forEach(menu => menu.classList.remove("show"));
        });
    }

});
</script>


</body>
</html>
