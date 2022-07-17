<br><br><br>
<div class="navbar fixed-bottom navbar-light bg-light">
  <div class="container-fluid justify-content-center">
    <?php if(isset($_COOKIE["email"]) && $_COOKIE["email"] != ""): ?>
      <a class="navbar-brand" href="principal.php">&#169; Controlin</a>
    <?php else: ?>
      <a class="navbar-brand" href="index.php">&#169; Controlin</a>
    <?php endif ?>  
  </div>  
</div>

</body>
</html>