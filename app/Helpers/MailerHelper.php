<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class MailerHelper
{
    public static function getMailableClasses()
    {
        $mailables = [];
        $files = File::allFiles(app_path('Mail'));

        foreach ($files as $file) {
            $namespace = 'App\\Mail\\';
            $className = $namespace . str_replace(['/', '.php'], ['\\', ''], File::name($file->getRelativePathname()));

            if (class_exists($className) && is_subclass_of($className, \Illuminate\Mail\Mailable::class)) {
                $mailables[] = $className;
            }
        }

        return $mailables;
    }

    public static function replaceVariables($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = str_replace("{" . $key . "}", $value, $content);
        }

        return $content;
    }
}
