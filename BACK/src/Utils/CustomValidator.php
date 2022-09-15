<?php
/*
    To use this class, you have to:
    - Define use App\Utils\CustomValidator; at the begining of the file
    - Define CustomValidator $customValidator in parameter of your function
    - use $customValidator->function_name()
*/
namespace App\Utils;

class CustomValidator {
    public function test(){
        return "ok";
    }
}

?>