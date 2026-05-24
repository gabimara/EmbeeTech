<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private PHPMailer $mailer;
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
        $this->mailer = new PHPMailer(true);

        try {
            if ($this->config['driver'] === 'smtp') {
                $this->mailer->isSMTP();
                $this->mailer->Host = $this->config['host'];
                $this->mailer->Port = $this->config['port'];
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = $this->config['username'];
                $this->mailer->Password = $this->config['password'];
                $this->mailer->SMTPSecure = $this->config['encryption'];
            }

            $this->mailer->setFrom(
                $this->config['from']['address'],
                $this->config['from']['name']
            );
            $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
        } catch (Exception $e) {
            throw new Exception('Erro ao configurar o serviço de email: ' . $e->getMessage());
        }
    }

    /**
     * Enviar um email
     *
     * @param string $to Email do destinatário
     * @param string $subject Assunto do email
     * @param string $body Corpo do email (suporta HTML)
     * @param string|null $name Nome do destinatário
     * @return bool true se enviado com sucesso, false caso contrário
     */
    public function send(string $to, string $subject, string $body, ?string $name = null): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $name ?? '');
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->isHTML(false);

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Erro ao enviar email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar email HTML
     *
     * @param string $to Email do destinatário
     * @param string $subject Assunto do email
     * @param string $htmlBody Corpo HTML do email
     * @param string|null $name Nome do destinatário
     * @return bool true se enviado com sucesso, false caso contrário
     */
    public function sendHtml(string $to, string $subject, string $htmlBody, ?string $name = null): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $name ?? '');
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlBody;
            $this->mailer->isHTML(true);

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Erro ao enviar email HTML: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obter mensagem de erro do último envio
     */
    public function getLastError(): string
    {
        return $this->mailer->ErrorInfo;
    }
}
