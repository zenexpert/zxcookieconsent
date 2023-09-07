<?php
// -----
// Admin-level auto-loader for ZX Cookie Consent
//
if (!defined ('IS_ADMIN_FLAG')) {
    die ('Illegal Access');
}

$autoLoadConfig[200][] = array (
    'autoType' => 'init_script',
    'loadFile' => 'init_zx_cookie_consent_install.php'
);