<?php
function redirect($url)
{
    header("Location: $url");
    exit;
}

function setMessage($message, $type = 'info')
{
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

function sanitizeInput($data)
{
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function getCurrentUrl()
{
    return $_SERVER['REQUEST_URI'];
}

function formatDate($date, $format = 'F j, Y, g:i a')
{
    return date($format, strtotime($date));
}

function isAjaxRequest()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function getClientIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
