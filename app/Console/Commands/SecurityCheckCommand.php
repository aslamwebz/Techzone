<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SecurityCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the security status of the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔒 Running security checks...');

        $this->checkEnvironmentFile();
        $this->checkAppDebug();
        $this->checkAppEnvironment();
        $this->checkAppKey();
        $this->checkDatabaseConfig();
        $this->checkPasswordHashing();
        $this->checkSessionConfig();
        $this->checkFilePermissions();
        $this->checkDependencies();

        $this->info('\n✅ Security check completed.');

        return 0;
    }

    /**
     * Check if .env file exists and has proper permissions.
     *
     * @return void
     */
    protected function checkEnvironmentFile()
    {
        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            $this->warn('⚠️  .env file does not exist!');

            return;
        }

        $permissions = substr(sprintf('%o', fileperms($envPath)), -4);

        if ($permissions !== '0600' && $permissions !== '0400') {
            $this->warn("⚠️  .env file permissions are too permissive (current: $permissions, recommended: 0600 or 0400)");
        } else {
            $this->info('✅ .env file has secure permissions');
        }
    }

    /**
     * Check if debug mode is disabled in production.
     *
     * @return void
     */
    protected function checkAppDebug()
    {
        if (config('app.debug') === true) {
            $this->warn('⚠️  Debug mode is enabled. Disable in production!');
        } else {
            $this->info('✅ Debug mode is disabled');
        }
    }

    /**
     * Check if the application is not running in production.
     *
     * @return void
     */
    protected function checkAppEnvironment()
    {
        $env = config('app.env');

        if ($env === 'production') {
            $this->info('✅ Application is in production mode');
        } else {
            $this->warn("⚠️  Application is in $env mode");
        }
    }

    /**
     * Check if application key is set.
     *
     * @return void
     */
    protected function checkAppKey()
    {
        $key = config('app.key');

        if (empty($key) || $key === 'base64:') {
            $this->warn('⚠️  Application key is not set! Run: php artisan key:generate');
        } else {
            $this->info('✅ Application key is set');
        }
    }

    /**
     * Check database configuration.
     *
     * @return void
     */
    protected function checkDatabaseConfig()
    {
        try {
            DB::connection()->getPdo();
            $this->info('✅ Database connection is working');

            // Check if using SSL for database connection in production
            if (config('app.env') === 'production') {
                $connection = config('database.default');
                $ssl = config("database.connections.$connection.ssl_mode", false);

                if (! $ssl) {
                    $this->warn('⚠️  Database connection is not using SSL');
                } else {
                    $this->info('✅ Database connection is using SSL');
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: '.$e->getMessage());
        }
    }

    /**
     * Check password hashing configuration.
     *
     * @return void
     */
    protected function checkPasswordHashing()
    {
        $defaultDriver = config('hashing.driver', 'bcrypt');

        if ($defaultDriver !== 'bcrypt' && $defaultDriver !== 'argon2id') {
            $this->warn("⚠️  Using $defaultDriver for password hashing. Consider using bcrypt or argon2id.");
        } else {
            $this->info("✅ Using $defaultDriver for password hashing");
        }

        // Check bcrypt rounds
        if ($defaultDriver === 'bcrypt') {
            $rounds = config('hashing.bcrypt.rounds', 10);

            if ($rounds < 10) {
                $this->warn("⚠️  Bcrypt rounds ($rounds) is too low. Consider increasing to at least 12.");
            } else {
                $this->info("✅ Bcrypt rounds set to $rounds");
            }
        }
    }

    /**
     * Check session configuration.
     *
     * @return void
     */
    protected function checkSessionConfig()
    {
        $driver = config('session.driver');
        $secure = config('session.secure');
        $httpOnly = config('session.http_only', true);
        $sameSite = config('session.same_site', 'lax');

        $this->info("✅ Session driver: $driver");

        if (! $secure && config('app.env') === 'production') {
            $this->warn('⚠️  Session secure flag is disabled');
        }

        if (! $httpOnly) {
            $this->warn('⚠️  Session HTTP-only flag is disabled');
        }

        if ($sameSite === 'none' && ! $secure) {
            $this->warn('⚠️  Session same-site is set to none without secure flag');
        }
    }

    /**
     * Check file and directory permissions.
     *
     * @return void
     */
    protected function checkFilePermissions()
    {
        $directories = [
            storage_path(),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            $permissions = substr(sprintf('%o', fileperms($directory)), -4);

            if ($permissions !== '0775' && $permissions !== '0777') {
                $this->warn("⚠️  Directory $directory has permissions $permissions (recommended: 0775 or 0777)");
            } else {
                $this->info("✅ Directory $directory has correct permissions");
            }
        }
    }

    /**
     * Check for known vulnerable dependencies.
     *
     * @return void
     */
    protected function checkDependencies()
    {
        $this->info('\n🔍 Checking dependencies for known vulnerabilities...');

        $output = [];
        $exitCode = 0;

        // Check if we can run npm audit
        if (file_exists(base_path('package-lock.json'))) {
            $this->info('Running npm audit...');
            exec('npm audit --json 2>&1', $output, $exitCode);

            if ($exitCode === 0) {
                $this->info('✅ No known vulnerabilities found in npm packages');
            } else {
                $audit = json_decode(implode('', $output), true);
                $vulnerabilities = $audit['vulnerabilities'] ?? [];

                if (! empty($vulnerabilities)) {
                    $this->warn('⚠️  Found vulnerabilities in npm packages:');

                    foreach ($vulnerabilities as $package => $details) {
                        $this->line("  - $package: {$details['severity']} - {$details['title']}");
                    }
                }
            }
        } else {
            $this->info('ℹ️  package-lock.json not found, skipping npm audit');
        }
    }
}
