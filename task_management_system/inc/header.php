<!-- HEADER -->
<header class="header d-flex justify-content-between align-items-center px-3 py-2 shadow-sm" style="background: rgba(32, 35, 36, 0.6); backdrop-filter: blur(10px); color: #fff;">
  <h2 class="u-name m-0 d-flex align-items-center">
    GuardianCare<b style="color:#00d4ff; margin-left: 4px;">360</b>
    <label for="checkbox" class="ms-3 mb-0" style="cursor: pointer;">
      <i id="navbtn" class="fa fa-bars fa-lg"></i>
    </label>
  </h2>
  <span class="notification position-relative" id="notificationBtn" style="cursor:pointer;">
    <i class="fa fa-bell fa-lg"></i>
    <span id="notificationNum" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill px-2"></span>
  </span>
</header>

<!-- NOTIFICATION BAR -->
<div class="notification-bar animate__animated" id="notificationBar" style="position: absolute; top: 60px; right: 20px; width: 300px; background: rgba(37, 231, 215, 0.85); color: #fff; border-radius: 10px; box-shadow: 0 8px 16px rgba(0,0,0,0.3); display: none; z-index: 999;">
  <ul id="notifications" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
    <!-- Notifications will be loaded here -->
  </ul>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
<script>
  const notificationBtn = document.querySelector("#notificationBtn");
  const notificationBar = document.querySelector("#notificationBar");

  notificationBtn.addEventListener("click", () => {
    if (notificationBar.style.display === "block") {
      notificationBar.classList.remove("animate__fadeInDown");
      notificationBar.style.display = "none";
    } else {
      notificationBar.style.display = "block";
      notificationBar.classList.add("animate__fadeInDown");
    }
  });

  $(document).ready(function () {
    $("#notificationNum").load("app/notification-count.php");
    $("#notifications").load("app/notification.php");
  });
</script>