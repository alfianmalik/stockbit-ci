<?php
/**
 * Created by PhpStorm.
 * User: alfian
 * Date: 28/03/19
 * Time: 6:36
 */

class EmailController extends CI_Controller
{
    /**
     * EmailController constructor.
     */
    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('session');
    }

    public function index()
    {
        $from_email = "email@example.com";
        $to_email = 'email@testemail.com';

        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mailtrap.io';
        $config['smtp_user'] = 'dac9063088f47f';
        $config['smtp_pass'] = '236dab6f464944';
        $config['smtp_port'] = 25;
        $this->email->initialize($config);

        //Load email library
        $this->email->from($from_email, 'Identification');
        $this->email->to($to_email);
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');
        //Send mail
        if($this->email->send())
            $this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
        else
            $this->session->set_flashdata("email_sent","You have encountered an error");
    }
}