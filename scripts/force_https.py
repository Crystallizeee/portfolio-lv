import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        print("--- Adding ASSET_URL to .env ---")
        # Check if already exists, if not append. If exists, replace.
        ssh.exec_command(f"grep -q 'ASSET_URL' {APP_DIR}/.env && sed -i 's|ASSET_URL=.*|ASSET_URL=https://portfolio.great-x-attach.xyz|g' {APP_DIR}/.env || echo 'ASSET_URL=https://portfolio.great-x-attach.xyz' >> {APP_DIR}/.env")
        
        print("--- Configuring Trusted Proxies in bootstrap/app.php ---")
        # We need to inject the trust proxies configuration into the 'withMiddleware' callback
        # This is tricky with sed. Let's overwrite the file with a known good configuration since we can see the file content locally or assume standard structure.
        # But wait, overwriting might break other things.
        # Let's read it first? No, let's look at it locally since I have the code.
        pass 

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
