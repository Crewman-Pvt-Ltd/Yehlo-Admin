<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\EmailTemplate;


class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $reset_url;
    protected $name;

    public function __construct($reset_url,$name)
    {
        $this->reset_url = $reset_url;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $company_name = BusinessSetting::where('key', 'business_name')->first()->value;

        $data=EmailTemplate::where('type','admin')->where('email_type', 'forget_password')->first();
        $user_name = $this->name;
        $template=$data?$data->email_template:5;
        $title = Helpers::text_variable_data_format( value:$data['title']??'',user_name:$user_name??'');
        $body = Helpers::text_variable_data_format( value:$data['body']??'',user_name:$user_name??'');
        $footer_text = Helpers::text_variable_data_format( value:$data['footer_text']??'',user_name:$user_name??'');
        $copyright_text = Helpers::text_variable_data_format( value:$data['copyright_text']??'',user_name:$user_name??'');

        $reset_url = $this->reset_url;


        return $this->subject('Vendor Password Reset Mail ')->view('email-templates.admin-password-reset', ['company_name'=>$company_name,'data'=>$data,'title'=>$title,'body'=>$body,'footer_text'=>$footer_text,'copyright_text'=>$copyright_text,'url' => $reset_url]);
    }
}
