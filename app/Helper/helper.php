<?php

function sendError($code,$message,$data)
{
    $response = [
        'code' => $code,
        'message' => $message,
        'data' => $data,
    ];

    return  response()->json($response);
}
function sendResponse($code,$message,$data)
{
    $response = [
        'code' => $code,
        'message' => $message,
        'data'    => $data,
    ];


    return response()->json($response);
}
function combinedFieldsResponse($validator)
{
    $errors = $validator->errors()->toArray();
    $requiredArray = [];
    $invalidArray = [];
    $uniqueArray = [];

    foreach ($errors as $key => $value){
        if(strpos($value[0],'required')>-1)
        {
            $message = str_replace('is required','',$value[0]);
            $message = str_replace('!','',$message);
            $message = str_replace('The','',$message);
            $message = str_replace('field','',$message);
            $message = str_replace('.','',$message);
            $message = trim($message);
            $message = ucwords($message);
            array_push($requiredArray, $message);
        }
        else if (strpos($value[0],'already')>-1)
        {
            array_push($uniqueArray, $value[0]);
        }
        else
        {
            array_push($invalidArray, $value[0]);
        }
    }

    $response = '';
    if(!empty($requiredArray))
    {
        $response = 'Following fields are required: '.implode(", ",$requiredArray);
    }
    else if(!empty($uniqueArray))
    {
        $response = implode(", ",$uniqueArray);
    }
    else
    {
        $response = implode(", ",$invalidArray);
    }
    return $response;
}
?>