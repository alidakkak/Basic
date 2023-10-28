<?php
namespace App\Traits;

trait GeneralTrait
{


    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => $errNum,
            'message' => $msg
        ]);
    }


    public function returnSuccessMessage($status,$msg = "success")
    {
        return  response()->json( [
            'status' => $status,
            'message' => $msg
        ]);
    }

    public function returnData($status,$keys,$values)
    {
        $data=array_merge( ['status' => $status],array_combine($keys,$values));
        return response()->json([
            $data
        ]);
    }

}
