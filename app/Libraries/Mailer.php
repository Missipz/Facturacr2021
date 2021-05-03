<?php
namespace App\Libraries;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends PHPMailer
{
    public function __construct() {
        parent::__construct();
    }

    public function enviarCorreo($data) {
        $mail = new PHPMailer(TRUE);
        $name = (isset($data['name'])) ? $data['name'] : "";
        try {
            $mail->setFrom($data['from'], $name);

            if(is_array($data['correo'])) {
                foreach ($data['correo'] as $value) {
                    $mail->AddAddress($value);
                }
            }else{
                $mail->AddAddress($data['correo']);
            }
            $mail->isSMTP();
            $mail->Host = "mail.jrtec.cl"; // SMTP a utilizar.
            $mail->Username = "pzeledon@jrtec.cl"; // Correo completo a utilizar
            $mail->Password = "Zeledon2021";// contraseña
            $mail->Port = 465; // Puerto a utilizar
            $mail->SMTPAuth = TRUE;
            $mail->SMTPSecure = "ssl";
            $mail->Timeout = 30;
            $mail->CharSet = "UTF-8";
            $mail->IsHTML(true);
            $mail->Subject = $data['asunto'];

            $mail->Body = $data['cuerpo'];

            if(isset($data['adjunto'])){
                if(isset($data['nombre_adjunto'])){
                    $mail->addAttachment($data['adjunto'], $data['nombre_adjunto']);
                }else{
                    $mail->addAttachment($data['adjunto']);
                }
            }

            $exito = $mail->send();
            if($exito){
                return true;
            }else{
                echo $mail->ErrorInfo;
            }
        }
        catch (Exception $e){
            echo $e->errorMessage();
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}