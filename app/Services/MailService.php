<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/5/19
 * Time: 12:28
 */

namespace App\Services;
use App\Exception\WrongRequestException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailService
{

    public static $verifyCodeMsg = "非常欢迎您的到来,寒舍蓬荜生辉,您的令牌是:";

    public const NAME = '三海';
    /**
     * @param        $sendEmail string 接收方邮箱
     * @param        $senderName string 显示的用户名
     * @param        $subject string 主题
     * @param        $body string  内容
     * @param string $altBody string  附加信息
     */
    public static function sendMail($sendEmail,$senderName,$subject,$body,$altBody = '')
    {
        $mail = new PHPMailer();

        try {
            //Server settings
            $mail->SMTPDebug = config('email_debug') == true ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;                      // Enable verbose debug output
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            // Send using SMTP
            $mail->Host       = config('email.host');                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = config('email.username');                     // SMTP username
            $mail->Password   = config('email.password');                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //Recipients
            $mail->setFrom(config('email.username') ,$senderName);
            $mail->addAddress($sendEmail);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody;

            $result = $mail->send();
            if ($result === false) {
                throw new WrongRequestException("发送邮件失败,请联系管理员!");
            }
        } catch (\Exception $e) {
            throw new WrongRequestException("发送邮件失败,请联系管理员!");
        }
    }

    /**
     * @param $sendEmail
     * @param $code
     */
    public static function sendVerifyCode($sendEmail,$code)
    {
        self::sendMail($sendEmail,self::NAME,'令牌核对',self::$verifyCodeMsg.$code);
    }

    /**
     * @param $content
     */
    public static function sendWrongEmail($content)
    {
        $receiver = config('email.username');
        $subject = '系统异常警告';
        self::sendMail($receiver,self::NAME,$subject,$content);
    }
}
