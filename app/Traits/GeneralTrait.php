<?php

namespace App\Traits;

trait GeneralTrait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "success")
    {
        return  response()->json([
            'status' => 200,
            'msg' => $msg
        ]);
    }

    public function returnData($key, $value, $keyo = "", $valueo = "")
    {
        if (!$keyo == "" && !$valueo == "") {
            return response()->json([
                'status' => 200,
                $keyo => $valueo,
                $key => $value,
            ]);
        }
        return response()->json([
            'status' => 200,
            $key => $value
        ]);
    }
}
