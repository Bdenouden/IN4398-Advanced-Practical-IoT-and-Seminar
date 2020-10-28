<?php

class api_update_triggers_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {
        $this->model = $this->loadModel('api');

        try {
            $login = Auth::requireBasicLogin();

            if ($login){
                $this::checkForTriggeredTriggers();
            }
            else {
                $this->loadController('e403');
            }
        } catch (UserException $e) {
            $this->loadController('e403');
        }

    }

    private function checkForTriggeredTriggers(){
        $triggers = $this->model->getTriggersWithRecentData();

        $triggered = [];

        foreach($triggers as $trigger){

            if (!isset($triggered[$trigger['node_id']]) || $triggered[$trigger['node_id']] !== true) {

                if ($trigger['lessThan_greaterThan'] == 0 && $trigger['value'] < $trigger['val']) {
                    $message = $trigger['node_id'] . ' below threshold value of ' . $trigger['val'] . $trigger['unit'] . ' with value of ' . $trigger['value'] . $trigger['unit'];
                    $this::sendEmail($trigger['recipient'], $message);
                    $triggered[$trigger['node_id']] = true;

                } else if ($trigger['lessThan_greaterThan'] == 1 && $trigger['value'] > $trigger['val']) {
                    $message = $trigger['node_id'] . ' above threshold value of ' . $trigger['val'] . $trigger['unit'] . ' with value of ' . $trigger['value'] . $trigger['unit'];
                    $this::sendEmail($trigger['recipient'], $message);
                    $triggered[$trigger['node_id']] = true;
                }
            }

        }

    }

    private function sendEmail(string $recipient, string $message){
        $mail = new Mailer();
        $mail->setTitle("Your " . WEBSITE_NAME . " trigger was triggered!");
        $mail->setMessage($message);
        $mail->setReceiver($recipient);
        $mail->setSender(WEBSITE_MAIL_ADDRESS, WEBSITE_NAME);
        $mail->send_mail();
    }


}
