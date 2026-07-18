<?php
if (extension_loaded('gd')) {
    echo "✅ GD Library sudah aktif!<br>";
    print_r(gd_info());
} else {
    echo "❌ GD Library BELUM aktif!";
}
?>
