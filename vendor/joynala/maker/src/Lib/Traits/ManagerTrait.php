<?php

namespace Abedin\Maker\Lib\Traits;

trait ManagerTrait
{
    private static $daFormat = 'YmdH';
    public static function getLastDate(): string
    {

        $filePath = storage_path('r'.'a'.'cord.'.'txt');
        if(!file_exists(filename: $filePath)){
            return now()->subDay()->format(self::$daFormat);
        }
        try{
            $file = file($filePath);
            $date = $file[1];
            $date = self::decrypt($date);
            return $date;
        } catch (\Exception $e){}
        return now()->subDay()->format(self::$daFormat);
    }

    public static function getPurchaseKey(): string|null
    {
        $filePath = storage_path('r'.'a'.'cord.'.'txt');
        if(!file_exists(filename: $filePath)){
            return null;
        }
        try{
            $file = file($filePath);
            $date = $file[2];
            $date = self::decrypt($date);
            return $date;
        } catch (\Exception $e){}
        return null;
    }

    public static function storeDate(): void
    {
        $filePath = self::findOrCreateFile();
        $file = file($filePath);

        $date = date(self::$daFormat);
        $date = self::encript($date);

        $file[1] = "$date\n";
        file_put_contents($filePath, $file);
    }

    public static function storePurchaseKey($key): void
    {
        $filePath = storage_path('r'.'a'.'cord.'.'txt');
        $file = file($filePath);
        $key = self::encript($key);

        $file[2] = "$key\n";

        file_put_contents($filePath, $file);
    }

    public static function decrypt($key): string|bool
    {
        $data = base64_decode($key);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encryptedData = substr($data, $ivLength);
        return openssl_decrypt($encryptedData, 'aes-256-cbc', 'Joynala', 0, $iv);
    }

    public static function encript($value): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($value, 'aes-256-cbc', 'Joynala', 0, $iv);
        return base64_encode($iv . $encryptedData);
    }

    private static function findOrCreateFile(): string
    {
        $filePath = storage_path(  'r'.'a'.'cord.'.'txt');
        if(!file_exists($filePath)){
            $data = ["Don't remove this file, if you try to remove this file your website can be down\n"];
            file_put_contents($filePath, $data);
        }
        return $filePath;
    }
}
