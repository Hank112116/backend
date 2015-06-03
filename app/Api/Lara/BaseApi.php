<?php namespace Backend\Api\Lara;

use Response;

class BaseApi
{
    public function jsonOK($data)
    {
        return $this->json($data);
    }

    public function jsonFail($msg = '')
    {
        return $this->json(['msg' => $msg], false);
    }

    private function json($data, $is_ok = true)
    {
        $data['ok'] = $is_ok ? 'ok' : '';

        return Response::json($data);
    }
}
