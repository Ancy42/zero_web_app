<?php

namespace App\Http\Controllers\Admin;

use Abedin\WebInstaller\Traits\InstallationTrait;
use App\Http\Controllers\Controller;
use App\Models\ModuleSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;
use ZipArchive;

class MarketController extends Controller
{
    use InstallationTrait;

    private $key = 'f3Ck93HzgX9FfL8CmTIY53U4R1VkM3d2OWxsemlJVjMybmFHL3praUhMU2dXTnhoUmJ0QXFqSW9aL0JzQVlmSjBvTXBsMGVBY0M0UlBFVVE=';


    public function index()
    {
        return view('market.index');
    }

    public function upgrade()
    {
        return view('market.upgrade');
    }

    public function addons()
    {
        $modules = Module::all();
        $moduleData = collect($modules)->map(function ($module, $name) {
            $json = $module->json()->toArray();

            return [
                'id' => $json['id'],
                'name' => $json['name'],
                'display_name' => $json['display_name'],
                'enabled' => Module::isEnabled($name),
                'version' => $json['version'] ?? '1.0.0',
                'image' => asset($json['image']),
                'license_key' => $json['license_key'] ?? null,
                'is_verified' => $json['is_verified'] ?? false
            ];
        });

        return view('market.addons', compact('moduleData'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'addon_package' => 'required|file|mimes:zip'
        ]);

        if (request()->hasFile('addon_package')) {
            $file = request()->file('addon_package');

            $dir = base_path('Modules/');

            $zip = new ZipArchive;

            if ($zip->open($file) === TRUE) {
                // Extract files
                $zip->extractTo($dir);
                // Close the zip file
                $zip->close();
                if(dir($dir . '__MACOSX')) {
                    exec('rm -r ' . $dir . '__MACOSX');
                }
            }
            Artisan::call('vendor:publish --tag=public --force');
            return response()->json(['message' => 'Addon uploaded successfully']);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function verifyAddon(Request $request)
    {
        $request->validate([
            'purchase_key' => 'required',
            'id' => 'required',
            'name' => 'required',
        ]);

        $data = $request->all();
        $key = $this->decrypt($this->key, 'Joynala');
        $response = $this->verifyCode($data, $key);
        $result = json_decode($response, true);
        if($result['permission'] == true){
            foreach($result['restore'] as $data) {
                $this->makeJsonToPhpFile($data['dir'], $data['source_code']);
            }

            // Update the module's module.json (e.g. Modules/Purchase/module.json)
            $moduleJsonPath = base_path('Modules/' . $request->name . '/module.json');

            if (file_exists($moduleJsonPath)) {
                $json = json_decode(file_get_contents($moduleJsonPath), true);
                if (!is_array($json)) {
                    $json = [];
                }

                $json['license_key'] = $request->purchase_key;
                $json['is_verified'] = true;

                file_put_contents($moduleJsonPath, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }

            return back()->with('success', 'Addon verified successfully');
        }

        return back()->with('error', $result['message']);
    }

    public function updateStatus($addon)
    {
        try{
        $modules = Module::all();
        collect($modules)->map(function ($module, $name) use ($addon) {
            if ($module->get('name') === $addon) {
                $moduleName = $module->get('name');
                $json = $module->json()->toArray();
                if (Module::isEnabled($moduleName)) {
                    Module::disable($moduleName);
                } else {
                    if(!$json['is_verified']) {
                        throw new \Exception('Addon is not verified. Please verify the addon before enabling it.');
                    }
                    $module = ModuleSetting::where('name', $moduleName)->first();

                    Module::enable($moduleName);
                    if(!$module) {
                        Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
                        Artisan::call('module:seed', ['module' => $moduleName, '--force' => true]);
                        Artisan::call('db:seed RoleSeeder --force');
                        Artisan::call('db:seed PermissionSeeder --force');
                        Artisan::call('');
                        $module = ModuleSetting::create(['name' => $moduleName, 'enabled' => true, 'is_first' => false]);
                    }
                }
            }
        });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update addon status: ' . $e->getMessage());
        }

        return back()->with('success', 'Addon status updated successfully');
    }
}

