import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

APP_PHP_CONTENT = r"""<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\TrackPageVisits::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
"""

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        print("--- Writing bootstrap/app.php ---")
        sftp = ssh.open_sftp()
        with sftp.file(f"{APP_DIR}/bootstrap/app.php", "w") as f:
            f.write(APP_PHP_CONTENT)
        sftp.close()
        
        print("--- Clearing Cache ---")
        ssh.exec_command(f"cd {APP_DIR} && php artisan config:clear")
        ssh.exec_command(f"cd {APP_DIR} && php artisan view:clear")
        
        print("--- Final Verification (HTTP Check for HTTPS link) ---")
        stdin, stdout, stderr = ssh.exec_command("curl -s http://localhost | grep .css")
        print(stdout.read().decode())

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
