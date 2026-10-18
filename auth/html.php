<?php
function htmlBody(string $activationLink)
{
  return '
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Activate Your Account</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
    <tr>
      <td align="center">

        <table width="600" cellpadding="0" cellspacing="0"
          style="background:#121212;color:#dddddd;border-radius:10px;border:1px solid #dddddd;overflow:hidden;">

          <tr>
            <td style="background:#5b5eeb;color:white;padding:25px;text-align:center;">
              <h1 style="margin:0;">My Notes</h1>
            </td>
          </tr>

          <tr>
            <td style="padding:40px;">

              <h2>Welcome!</h2>

              <p>
                Thank you for creating your <strong>My Notes</strong> account.
                Please click the button below to activate your account.
              </p>

              <p style="text-align:center;margin:35px 0;">
                <a href="' . $activationLink . '" target="_self" style="
background:#5b5eeb;
color:#ffffff;
padding:14px 30px;
text-decoration:none;
border-radius:6px;
display:inline-block;
font-weight:bold;">
                  Activate My Account
                </a>
              </p>

              <p>If the button doesn\'t work, copy this link into your browser:</p>

              <p style="word-break:break-all;color:#5b5eeb;">
                ' . $activationLink . '
              </p>

              <hr>

              <p style="font-size:13px;color:#777;">
                If you didn\'t create a My Notes account, simply ignore this email.
              </p>

            </td>
          </tr>

          <tr>
            <td style="background:#f7f7f7;padding:20px;text-align:center;color:#888;">
              © ' . date("Y") . ' My Notes
            </td>
          </tr>

        </table>

      </td>
    </tr></table></body></html>';
}

function resetPasswordHtmlBody(string $resetLink)
{
  return '
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Reset Your Password</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
    <tr>
      <td align="center">

        <table width="600" cellpadding="0" cellspacing="0"
          style="background:#121212;color:#dddddd;border-radius:10px;border:1px solid #dddddd;overflow:hidden;">

          <tr>
            <td style="background:#5b5eeb;color:white;padding:25px;text-align:center;">
              <h1 style="margin:0;">My Notes</h1>
            </td>
          </tr>

          <tr>
            <td style="padding:40px;">

              <h2>Reset your password</h2>

              <p>
                We received a request to reset the password for your <strong>My Notes</strong> account.
                Click the button below to choose a new password. This link expires in 30 minutes.
              </p>

              <p style="text-align:center;margin:35px 0;">
                <a href="' . $resetLink . '" target="_self" style="
background:#5b5eeb;
color:#ffffff;
padding:14px 30px;
text-decoration:none;
border-radius:6px;
display:inline-block;
font-weight:bold;">
                  Reset My Password
                </a>
              </p>

              <p>If the button doesn\'t work, copy this link into your browser:</p>

              <p style="word-break:break-all;color:#5b5eeb;">
                ' . $resetLink . '
              </p>

              <hr>

              <p style="font-size:13px;color:#777;">
                If you didn\'t request a password reset, simply ignore this email — your password won\'t be changed.
              </p>

            </td>
          </tr>

          <tr>
            <td style="background:#f7f7f7;padding:20px;text-align:center;color:#888;">
              © ' . date("Y") . ' My Notes
            </td>
          </tr>

        </table>

      </td>
    </tr></table></body></html>';
}
