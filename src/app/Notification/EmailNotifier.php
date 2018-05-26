<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of EmailNotifier
 *
 * @author esamm
 */

namespace App\Notification;

use Illuminate\Support\Facades\Mail;

class EmailNotifier {

    private $subject;
    private $from;
    private $to;
    private $cc;
    private $bcc;
    private $template;
    private $attachement;
    private $params;
    private $attachmentData;
    private $attachmentName;

    public function getSubject() {
        return $this->subject;
    }

    public function getTo() {
        return $this->to;
    }

    public function getFrom() {
        return $this->from;
    }

    public function getCc() {
        return $this->cc;
    }

    public function getBcc() {
        return $this->bcc;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function getAttachement() {
        return $this->attachement;
    }

    public function getParams() {
        return $this->params;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function setFrom($from) {
        $this->from = $from;
        return $this;
    }
    
    public function setTo($to) {
        $this->to = $to;
        return $this;
    }

    public function setCc($cc) {
        $this->cc = $cc;
        return $this;
    }

    public function setBcc($bcc) {
        $this->bcc = $bcc;
        return $this;
    }

    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    public function setAttachement($attachement) {
        $this->attachement = $attachement;
        return $this;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    public function getAttachmentData() {
        return $this->attachmentData;
    }

    public function setAttachmentData($attachmentData) {
        $this->attachmentData = $attachmentData;
    }

    public function getAttachmentName() {
        return $this->attachmentName;
    }

    public function setAttachmentName($attachmentName) {
        $this->attachmentName = $attachmentName;
    }

    public function __construct($subject, $template, $from, $toList, $ccList = array(), $bccList = array(), $attachmentPath = null, $params = array(), $attachmentData = null, $attachmentName = null) {
        $this->setTemplate($template);
        $this->setFrom($from);
        $this->setTo($toList);
        $this->setCc($ccList);
        $this->setBcc($bccList);
        $this->setSubject($subject);
        $this->setAttachement($attachmentPath);
        $this->setAttachmentData($attachmentData);
        $this->setAttachmentName($attachmentName);
        $this->setParams($params);
    }

    function generateEmailBody($variablesToMakeLocal) {
        if (\view()->exists($this->getTemplate())) {
            return $this->getTemplate();
        } else if (is_file($this->getTemplate())) {
            ob_start();
            \extract($variablesToMakeLocal);
            include $this->getTemplate();
            return ['raw' => \ob_get_clean()];
        } else if ($this->getTemplate() != "") {
            return ['raw' => $this->getTemplate()];
        }
        return;
    }

    public function send($variables = array()) {
        try {
            if (count($this->to) == 0) {
                throw new Exception("No Address Defined");
            }
            $emailBody = $this->generateEmailBody($variables);
            Mail::send($emailBody, $variables, function($m) {                
                $m->from($this->getFrom());                
                $m->setContentType('text/html');
                $m->subject($this->getSubject());
                $m->to($this->getTo());
                
                if (count($this->getCc()) > 0) {
                    $m->cc($this->getCc());
                }
                if (count($this->getBcc()) > 0) {
                    $m->bcc($this->getBcc());
                }
                if ($this->getAttachement() != "" && \is_file($this->getAttachement())) {
                    $m->attach($this->getAttachement());
                }
                if ($this->getAttachmentData() != "") {
                    $m->attachData($this->getAttachmentData(), $this->getAttachmentName());
                }
            });
            $failures = Mail::failures();
            if (count($failures) > 0) {
                throw new Exception("mail failed for the receipients " . json_encode($failures));
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        return true;
    }

}
