<?php

namespace App\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
   public function userField()
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        if (session()->has('current_shop_id')) {
            return session('current_shop_id');
        }

        $shop=generaleSetting('shop')->id;

        if ($shop) {
            return $shop;
        }

        return null;
    }



    public function baseDirectory()
    {
        return 'public';
    }
}
