<?php

if (! function_exists('sendNotificationEmail')) {
    function sendNotificationEmail($user = null, $password = null, $mailTemplate, $subject) {
        // send mail to the user with his password and mail
        $mailError = "";
        try {
            Mail::send($mailTemplate, ['user' => $user, 'password' => $password, 'subject' => $subject], function($mail) use ($user, $password, $subject){
                $mail->from('info@2brothers-tobacco.de', '2 Brothers Tobacco');
                $mail->to($user->email, $user->name);
                $mail->subject('2 Brothers Tobacco - ' . $subject);
            });
        } catch (Exception $e) {
            if ($e) {
                $mailError = "Fehler beim Senden <strong>Kunden-E-Mail</strong>. Möglicherweise liegt ein Problem mit Ihrer Internetverbindung vor.";
            }
        }

        if($mailError) {
            return $mailError;
        } else {
            return 'Mail sent successfully';
        }
    }
}