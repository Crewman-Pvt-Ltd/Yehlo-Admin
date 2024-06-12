<?php

namespace App\Library;

class VendorDetails
{
    private $f_name;
    private $l_name;
    private $email;
    private $phone;
    

    public function __construct($f_name, $email, $l_name,$phone)
    {
        $this->f_name = $f_name;
        $this->email = $email;
        $this->l_name = $l_name;
        $this->phone = $phone;
        
    }

    public function getName() {
        return $this->f_name." ". $this->l_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }



    public function information(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),    
            'type' => 'plan'       
        ];
    }
}
