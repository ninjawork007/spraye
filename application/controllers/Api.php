<?php
/**
 * This controller creates Public APIs for Lambda function to execute by scheduling
 *
 * @author nhanduong99
 * @createdAt 21/05/2023 UTC+7
 */

defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';

class Api extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('../modules/admin/models/AdminTbl_company_model', 'CompanyModel');
        $this->load->model('../modules/admin/models/Company_email_model', 'CompanyEmail');
        $this->load->model("../modules/admin/models/Administrator");
    }


    /**
     * execute once per day to send an email notification to user related
     *
     * @return array
     */
    public function on_note_due_date()
    {
        $response = array('success' => true, 'message' => '');
        try {
            $notes = $this->CompanyModel->getNoteDueDateToday();

            if (!empty($notes)) {
                foreach ($notes as $note) {
                    $this->notification_on_note($note->note_id);
                }
            }
            $response['message'] = 'Sent notification successfully';
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        die(json_encode($response));
    }


    /**
     * check whether the users of note will be received the email notification or not
     * then, send email notification to them
     *
     * @param $note_id
     * @return void
     */
    private function notification_on_note($note_id)
    {
        $action = 'note_on_due_date';

        $company_data = $this->CompanyModel->getCompanyByNoteId($note_id);
        $company_id = $company_data->company_id;

        $note = $this->CompanyModel->getNoteById($note_id);
        $where = array(
            'company_id' => $company_id,
            'is_smtp' => 1
        );
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

        $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        if ($note['notify_me']) {
            // email to note user created
            $note_user = $this->Administrator->getUserById($note['note_user_id']);

            $mail_data = [
                'note' => $note,
                'company_details' => $company_details,
                'note_user' => $note_user,
            ];

            $this->_send_mail_on_note($company_email_details, $mail_data, $action);

            // email to note assigned user
            $note_user = $this->Administrator->getUserById($note['note_assigned_user']);

            $mail_data = [
                'note' => $note,
                'company_details' => $company_details,
                'note_user' => $note_user,
            ];

            $this->_send_mail_on_note($company_email_details, $mail_data, $action);
        }

        if ($note['is_enable_notifications']) {
            if (!empty($note['notification_to'])) {
                $users = explode(',', $note['notification_to']);
                // email to specific users chosen for notification
                foreach ($users as $key => $user_id) {
                    $note_user = $this->Administrator->getUserById($user_id);

                    $mail_data = [
                        'note' => $note,
                        'company_details' => $company_details,
                        'note_user' => $note_user,
                    ];
                    $this->_send_mail_on_note($company_email_details, $mail_data, $action);
                }
            }
        }
    }

    /**
     * send email notification to users
     *
     * @param $company_email_details
     * @param $mail_data
     * @param $action
     * @return void
     */
    private function _send_mail_on_note($company_email_details, $mail_data, $action)
    {
        $mail_data['action'] = $action;
        $body = $this->load->view('admin/email/action_note_email', $mail_data, true);
        $subject = 'Today is Due Date on Note';

        if ($body != '') {
            Send_Mail_dynamic($company_email_details,
                $mail_data['note_user']['email'],
                array(
                    'name' => $mail_data['company_details']->company_name,
                    'email' => $mail_data['company_details']->company_email
                ),
                $body,
                $subject);
        }
    }
}