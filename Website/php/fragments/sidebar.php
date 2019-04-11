<div class="list-group border-0 card text-center text-md-left" id="sidebar">
    <?php if (is_logged()): ?>
        <?php if(is_page("index.php")): ?>
            <a href="moves.php" class="list-group-item d-inline-block collapsed text-white" data-parent="#sidebar"><i class="fa fa-gamepad"></i><span class="d-none d-md-inline"> Play</span></a>
        <?php else: ?>
            <a href="index.php" class="list-group-item d-inline-block collapsed text-white" data-parent="#sidebar"><i class="fa fa-home"></i><span class="d-none d-md-inline"> Home</span></a>
        <?php endif; ?>
        <a href="logout.php" class="list-group-item d-inline-block collapsed text-white" data-parent="#sidebar"><i class="fa fa-sign-out"></i><span class="d-none d-md-inline"> Logout</span></a>
    <?php else: ?>
        <?php if (!is_page("index.php")): ?>
            <a href="index.php" class="list-group-item d-inline-block collapsed text-white" data-parent="#sidebar"><i class="fa fa-home"></i><span class="d-none d-md-inline"> Home</span></a>
        <?php else: ?>
            <a href="login.php" class="list-group-item d-inline-block collapsed text-white" data-parent="#sidebar"><i class="fa fa-sign-in"></i><span class="d-none d-md-inline"> Login</span></a>
            <a href="register.php" class="list-group-item d-inline-block collapsed text-white" data-parent="#sidebar"><i class="fa fa-user-plus"></i><span class="d-none d-md-inline"> Sign Up</span></a>
        <?php endif; ?>
    <?php endif; ?>
</div>
