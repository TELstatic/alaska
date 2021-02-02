<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInstallationAccountRequest;
use App\Http\Requests\CreateInstallationDatabaseRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    public function index()
    {
        return view('install.content', ['view' => 'install.welcome']);
    }

    public function requirements()
    {
        $requirements = config('install.extensions');

        $results = [];
        // Check the requirements
        foreach ($requirements as $type => $extensions) {
            if (strtolower($type) == 'php') {
                foreach ($requirements[$type] as $extensions) {
                    $results['extensions'][$type][$extensions] = true;

                    if (!extension_loaded($extensions)) {
                        $results['extensions'][$type][$extensions] = false;

                        $results['errors'] = true;
                    }
                }
            } elseif (strtolower($type) == 'apache') {
                foreach ($requirements[$type] as $extensions) {
                    // Check if the function exists
                    // Prevents from returning a false error
                    if (function_exists('apache_get_modules')) {
                        $results['extensions'][$type][$extensions] = true;

                        if (!in_array($extensions, apache_get_modules())) {
                            $results['extensions'][$type][$extensions] = false;

                            $results['errors'] = true;
                        }
                    }
                }
            }
        }

        // If the current php version doesn't meet the requirements
        if (version_compare(PHP_VERSION, config('install.php_version')) == -1) {
            $results['errors'] = true;
        }

        return view('install.content', ['view' => 'install.requirements', 'results' => $results]);
    }

    public function permissions()
    {
        $permissions = config('install.permissions');

        $results = [];
        foreach ($permissions as $type => $files) {
            foreach ($files as $file) {
                if (is_writable(base_path($file))) {
                    $results['permissions'][$type][$file] = true;
                } else {
                    $results['permissions'][$type][$file] = false;
                    $results['errors'] = true;
                }
            }
        }

        return view('install.content', ['view' => 'install.permissions', 'results' => $results]);
    }

    public function database()
    {
        return view('install.content', ['view' => 'install.database']);
    }

    public function qiniu()
    {
        return view('install.content', ['view' => 'install.qiniu']);
    }

    public function account()
    {
        return view('install.content', ['view' => 'install.account']);
    }

    public function complete()
    {
        return view('install.content', ['view' => 'install.complete']);
    }

    /**
     * Validate the database credentials, and write the .env config file
     *
     * @param CreateInstallationDatabaseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveConfig(CreateInstallationDatabaseRequest $request)
    {
        $validateDatabase = $this->validateDatabaseCredentials();

        if ($validateDatabase !== true) {
            return back()->with('error', __('Invalid database credentials. '.$validateDatabase));
        }

        $validateConfigFile = $this->saveConfigFile();

        try {
            Artisan::call('cache:clear', ['--force' => true]);
        } catch (\Exception $e) {
        }

        if ($validateConfigFile !== true) {
            return back()->with('error', __('Unable to save .env file, check file permissions. '.$validateConfigFile));
        }

        return redirect()->route('install.qiniu');
    }

    public function saveQiniu()
    {
        try {
            Artisan::call('cache:clear', ['--force' => true]);
        } catch (\Exception $e) {
        }

        $validateConfigFile = $this->saveQiniuConfig();

        if ($validateConfigFile !== true) {
            return back()->with('error', __('Unable to save .env file, check file permissions. '.$validateConfigFile));
        }

        return redirect()->route('install.account');
    }

    /**
     * Migrate the database, and add the default admin user
     *
     * @param CreateInstallationAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDatabase(CreateInstallationAccountRequest $request)
    {
        $migrateDatabase = $this->migrateDatabase();
        if ($migrateDatabase !== true) {
            return back()->with('error', __('Failed to migrate the database. '.$migrateDatabase));
        }

        $createDefaultUser = $this->createDefaultUser();

        if ($createDefaultUser !== true) {
            return back()->with('error', __('Failed to create the default user. '.$createDefaultUser));
        }

        $saveInstalledFile = $this->saveInstalledFile();

        if ($saveInstalledFile !== true) {
            return back()->with('error', __('Failed to finalize the installation. '.$saveInstalledFile));
        }

        return redirect()->route('install.complete');
    }

    /**
     * Migrate the database
     *
     * @return bool|string
     */
    private function migrateDatabase()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Create the default admin user
     *
     * @return bool|string
     */
    private function createDefaultUser()
    {
        try {
            $user = new User;

            $user->name = request()->input('name');
            $user->email = request()->input('email');
            $user->password = Hash::make(request()->input('password'));

            $user->save();

            $user->markEmailAsVerified();

            $this->createDefaultMenus();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    private function createDefaultMenus()
    {
        try {
            Artisan::call('zone:create-menus', ['--force' => true]);
        } catch (\Exception $e) {
        }
    }

    private function saveQiniuConfig()
    {
        $qiniuAk = request()->input('qiniu_ak');
        $qiniuSk = request()->input('qiniu_sk');
        $qiniuHost = request()->input('qiniu_host');
        $qiniuBucket = request()->input('qiniu_bucket');
        $qiniuExpire = 3600;

        $config = <<<CONFIG
QINIU_AK={$qiniuAk}
QINIU_SK={$qiniuSk}
QINIU_HOST={$qiniuHost}
QINIU_BUCKET={$qiniuBucket}
QINIU_EXPIRE={$qiniuExpire}
CONFIG;

        try {
            file_put_contents(base_path('.env'), $config, FILE_APPEND);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * Write the .env config file
     *
     * @return bool|string
     */
    private function saveConfigFile()
    {
        $appKey = base64_encode(Str::random(32));
        $appUrl = route('welcome');

        $dbHost = request()->input('database_hostname');
        $dbPort = request()->input('database_port');
        $dbDatabase = request()->input('database_name');
        $dbUsername = request()->input('database_username');
        $dbPassword = request()->input('database_password');

        $config = <<<CONFIG
APP_NAME=Alaska
APP_ENV=production
APP_KEY=base64:{$appKey}
APP_DEBUG=0
APP_URL={$appUrl}

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST={$dbHost}
DB_PORT={$dbPort}
DB_DATABASE={$dbDatabase}
DB_USERNAME={$dbUsername}
DB_PASSWORD={$dbPassword}

BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=480

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

CONFIG;

        try {
            file_put_contents(base_path('.env'), $config);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * Validate the database credentials
     *
     * @return bool|string
     */

    private function validateDatabaseCredentials()
    {
        $settings = config("database.connections.mysql");

        config([
            'database' => [
                'default'     => 'mysql',
                'connections' => [
                    'mysql' => array_merge($settings, [
                        'driver'   => 'mysql',
                        'host'     => request()->input('database_hostname'),
                        'port'     => request()->input('database_port'),
                        'database' => request()->input('database_name'),
                        'username' => request()->input('database_username'),
                        'password' => request()->input('database_password'),
                    ]),
                ],
            ],
        ]);

        DB::purge();

        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Write the installed file
     *
     * @return bool|string
     */
    private function saveInstalledFile()
    {
        if (!file_exists(storage_path('installed'))) {
            try {
                file_put_contents(storage_path('installed'), json_encode([
                    'time' => date('Y-m-d H:i:s'),
                ]));
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return true;
    }
}
