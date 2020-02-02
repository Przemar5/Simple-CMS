    <?php if (isset($_SESSION['last_action']['success'])): ?>
        <p style="color:green; margin-bottom:3rem;">
            <?php echo $_SESSION['last_action']['success']; ?>
        </p>
    <?php elseif (isset($_SESSION['last_action']['error'])): ?>
        <p style="color:red; margin-bottom:3rem;">
            <?php echo $_SESSION['last_action']['error']; ?>
        </p>
    <?php endif; ?>