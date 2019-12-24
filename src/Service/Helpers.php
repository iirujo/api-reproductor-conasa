<?php
namespace App\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Helpers {

    public function __construct(TranslatorInterface $translator){
        
        $this->translator = $translator;

    }
    
    public function handleErrors(\Exception $e){
        $msg = $e->getMessage();
        $msg = mb_convert_encoding($msg, 'UTF-8', 'UTF-8');
        return $msg;
      }

    public function translate ($msg, $locale){

        if ($locale == "es"){

            $msg = $this->translator->trans($msg);

        }        

        return $msg;
    }

}