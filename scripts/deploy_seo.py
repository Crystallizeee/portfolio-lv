import paramiko
import os
import posixpath
import time
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

LOCAL_BASE = r'd:\src_code\Py\Portfolio\portfolio-lv'

files = [
    'resources/views/partials/jsonld-person.blade.php',
    'resources/views/partials/jsonld-blogposting.blade.php',
    'resources/views/partials/jsonld-website.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/welcome.blade.php',
    'resources/views/blog/show.blade.php',
    'resources/views/blog/index.blade.php',
    'app/Models/Post.php',
    'app/Http/Controllers/OgImageController.php',
    'app/Console/Commands/MakeSitemap.php',
    'routes/web.php',
]

def get_ssh():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(SERVER, username=USER, password=PASSWORD)
    transport = ssh.get_transport()
    transport.set_keepalive(10)  # Send keepalive every 10 seconds
    return ssh

def mkdir_p(sftp, remote_dir):
    dirs_to_create = []
    current = remote_dir
    while current and current != '/':
        try:
            sftp.stat(current)
            break
        except FileNotFoundError:
            dirs_to_create.insert(0, current)
            current = posixpath.dirname(current)
    for d in dirs_to_create:
        sftp.mkdir(d)

def upload_files():
    ssh = get_ssh()
    sftp = ssh.open_sftp()
    
    print("UPLOADING FILES")
    print("=" * 50)
    
    for rel_path in files:
        local_path = os.path.join(LOCAL_BASE, rel_path)
        remote_path = f'{REMOTE_DIR}/{rel_path}'
        remote_dir = posixpath.dirname(remote_path)
        mkdir_p(sftp, remote_dir)
        sftp.put(local_path, remote_path)
        print(f"  ✓ {rel_path}")
    
    sftp.close()
    ssh.close()
    print("\n✅ All files uploaded!")

def post_deploy():
    ssh = get_ssh()
    
    print("\nPOST-DEPLOYMENT")
    print("=" * 50)
    
    commands = [
        ('Creating OG images dir', f'mkdir -p {REMOTE_DIR}/storage/app/public/og-images'),
        ('Storage link', f'cd {REMOTE_DIR} && php artisan storage:link 2>/dev/null || true'),
        ('Clear caches', f'cd {REMOTE_DIR} && php artisan optimize:clear'),
        ('Generate sitemap', f'cd {REMOTE_DIR} && php artisan sitemap:generate'),
        ('Fix permissions', f'chown -R www-data:www-data {REMOTE_DIR}/storage {REMOTE_DIR}/bootstrap/cache'),
        ('Check GD', 'php -m | grep -i gd'),
    ]
    
    for desc, cmd in commands:
        print(f"  → {desc}...")
        stdin, stdout, stderr = ssh.exec_command(cmd, timeout=30)
        out = stdout.read().decode().strip()
        err = stderr.read().decode().strip()
        if out: print(f"    {out}")
        if err and 'warning' not in err.lower(): print(f"    ⚠️ {err}")
    
    ssh.close()
    print("\n✅ Post-deployment complete!")

def verify():
    ssh = get_ssh()
    
    print("\nVERIFICATION")
    print("=" * 50)
    
    checks = [
        ('Sitemap', f'test -f {REMOTE_DIR}/public/sitemap.xml && echo "EXISTS" || echo "MISSING"'),
        ('JSON-LD count', 'curl -s http://localhost | grep -c "application/ld+json"'),
        ('OG tags', 'curl -s http://localhost | grep -c "og:type"'),
    ]
    
    for desc, cmd in checks:
        stdin, stdout, stderr = ssh.exec_command(cmd, timeout=15)
        out = stdout.read().decode().strip()
        print(f"  {desc}: {out}")
    
    ssh.close()
    print("\n✅ Done!")

if __name__ == '__main__':
    upload_files()
    time.sleep(1)
    post_deploy()
    time.sleep(1)
    verify()
