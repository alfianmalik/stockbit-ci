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
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_user' => 'dac9063088f47f',
            'smtp_pass' => '236dab6f464944',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );

        $this->email->initialize($config);

        $this->load->helper('file');
    }

    public function index()
    {
        $from_email = "email@example.com";
        $to_email = 'email@testemail.com';
        $sender_name = $this->RandomStringGenerator(10);
        //Load email library
        $this->email->from($from_email, $sender_name);
        $this->email->to($to_email);
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');

        //Send mail
        if($this->email->send()) {
            return 'Send';
        }
        else
        {
            return 'not send';
        }

    }

    protected function RandomStringGenerator($n)
    {
        // Variable which store final string
        $generated_string = "";

        // Create a string with the help of
        // small letters, capital letters and
        // digits.
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

        // Find the lenght of created string
        $len = strlen($domain);

        // Loop to create random string
        for ($i = 0; $i < $n; $i++)
        {
            // Generate a random index to pick
            // characters
            $index = rand(0, $len - 1);

            // Concatenating the character
            // in resultant string
            $generated_string = $generated_string . $domain[$index];
        }

        // Return the random generated string
        return $generated_string;
    }
}