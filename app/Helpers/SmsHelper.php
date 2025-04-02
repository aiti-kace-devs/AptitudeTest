<?php

namespace App\Helpers;

use App\Models\SmsTemplate;

class SmsHelper
{
    public static function getTemplate($name, $variables = [])
    {
        $template = SmsTemplate::where('name', $name)->first();

        if (!$template) {
            return null;
        }

        $content = $template->content;

        foreach ($variables as $key => $value) {
            $content = str_replace("{" . $key . "}", $value, $content);
        }

        return $content;
    }
}
