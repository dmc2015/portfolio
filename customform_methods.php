<?php
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
$resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
if ($resp->isSuccess()) {
    // verified!
} else {
    $errors = $resp->getErrorCodes();
}
