<?php
if (!function_exists('response'))
{

    function response($result, $messages = FALSE, $data = FALSE)
    {
        $arr['result'] = $result;
        if ($messages)
        {
            $arr['messages'] = $messages;
        }
        if ($data!==FALSE)
        {
            $arr['data'] = $data;
        }
        return $arr;
    }

}