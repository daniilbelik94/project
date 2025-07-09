<?php
function set_flash($message, $type = 'success')
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function get_flash()
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function show_flash()
{
    $flash = get_flash();
    if ($flash) {
        $type = $flash['type'] === 'error' ? 'danger' : $flash['type'];
        echo '<div id="flash-toast" class="toast align-items-center text-bg-' . htmlspecialchars($type) . ' show" role="alert" aria-live="assertive" aria-atomic="true" style="position:fixed;left:50%;bottom:40px;transform:translateX(-50%);min-width:300px;max-width:500px;z-index:9999;box-shadow:0 2px 8px rgba(0,0,0,0.2);">'
            . '<div class="d-flex">'
            . '<div class="toast-body w-100 text-center">' . htmlspecialchars($flash['message']) . '</div>'
            . '<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
            . '</div>'
            . '</div>';
        echo '<script>setTimeout(function(){var t=document.getElementById("flash-toast");if(t)t.classList.remove("show")},5000);</script>';
    }
}
