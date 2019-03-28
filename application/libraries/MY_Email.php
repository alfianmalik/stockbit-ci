<?php
/**
 * Created by PhpStorm.
 * User: alfian
 * Date: 28/03/19
 * Time: 12:25
 */

class MY_Email extends CI_Email
{
    private $name;

    /**
     * Set FROM
     *
     * @param	string	$from
     * @param	string	$name
     * @param	string	$return_path = NULL	Return-Path
     * @return	CI_Email
     */
    public function from($from, $name = '', $return_path = NULL)
    {
        // Tambahkan fungsi ini untuk mengambil data sender name

        $this->name = $name;

        if (preg_match('/\<(.*)\>/', $from, $match))
        {
            $from = $match[1];
        }

        if ($this->validate)
        {
            $this->validate_email($this->_str_to_array($from));
            if ($return_path)
            {
                $this->validate_email($this->_str_to_array($return_path));
            }
        }

        // prepare the display name
        if ($name !== '')
        {
            // only use Q encoding if there are characters that would require it
            if ( ! preg_match('/[\200-\377]/', $name))
            {
                // add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
                $name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
            }
            else
            {
                $name = $this->_prep_q_encoding($name);
            }
        }

        $this->set_header('From', $name.' <'.$from.'>');

        isset($return_path) OR $return_path = $from;
        $this->set_header('Return-Path', '<'.$return_path.'>');

        return $this;
    }

    /**
     * Send Email
     *
     * @param	bool	$auto_clear = TRUE
     * @return	bool
     */
    public function send($auto_clear = TRUE)
    {
        if ( ! isset($this->_headers['From']))
        {
            $this->_set_error_message('lang:email_no_from');
            return FALSE;
        }

        if ($this->_replyto_flag === FALSE)
        {
            $this->reply_to($this->_headers['From']);
        }

        if ( ! isset($this->_recipients) && ! isset($this->_headers['To'])
            && ! isset($this->_bcc_array) && ! isset($this->_headers['Bcc'])
            && ! isset($this->_headers['Cc']))
        {
            $this->_set_error_message('lang:email_no_recipients');
            return FALSE;
        }

        $this->_build_headers();

        if ($this->bcc_batch_mode && count($this->_bcc_array) > $this->bcc_batch_size)
        {
            $result = $this->batch_bcc_send();

            if ($result && $auto_clear)
            {
                $this->clear();
            }

            return $result;
        }

        if ($this->_build_message() === FALSE)
        {
            return FALSE;
        }

        $result = $this->_spool_email();

        if ($result && $auto_clear)
        {
            $this->clear();
        }

        $data = $this->name." has just sent an email";

        write_file(APPPATH .'/cache/writeme.txt', "\n".$data."\n", "a+");

        return $result;
    }
}