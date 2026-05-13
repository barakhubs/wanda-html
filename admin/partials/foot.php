</div><!-- .admin-wrapper -->
<?= $adminExtraScripts ?? '' ?>
</body>

</html>
<?php
if (ob_get_level() > 0) {
    ob_end_flush();
}
