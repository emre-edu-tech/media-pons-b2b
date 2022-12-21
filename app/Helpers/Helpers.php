<?php

if (! function_exists('sendNotificationEmail')) {
    function sendNotificationEmail($mailTemplate, $subject, $user = null, $password = null) {
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
                // $mailError = $e->getMessage();
            }
        }

        if($mailError) {
            return $mailError;
        } else {
            return 'Mail sent successfully';
        }
    }
}

if(!function_exists('formatPrice')) {
    function formatPrice($price) {
        return number_format($price/100, 2, ',', '.');
    }
}