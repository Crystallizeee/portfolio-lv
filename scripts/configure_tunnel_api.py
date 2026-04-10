import requests
import json

ACCOUNT_ID = "29aa5035a6f089ec1b9bd6f9bd4b94d4"
TUNNEL_ID = "769a95dc-29b1-489a-ba65-aff0f74f8c31"
API_KEY = "9e9e5e898693fbdbc15c548d3fa54f53c2bef"
EMAIL = "Blood.last54@gmail.com"

API_URL = f"https://api.cloudflare.com/client/v4/accounts/{ACCOUNT_ID}/cfd_tunnel/{TUNNEL_ID}/configurations"

headers = {
    "X-Auth-Key": API_KEY,
    "X-Auth-Email": EMAIL,
    "Content-Type": "application/json"
}

# First, GET current config
print("=" * 50)
print("Step 1: Getting current tunnel configuration")
print("=" * 50)
resp = requests.get(API_URL, headers=headers)
print(f"Status: {resp.status_code}")
print(f"Response: {json.dumps(resp.json(), indent=2)}")

# PUT new config with public hostnames
print("\n" + "=" * 50)
print("Step 2: Setting public hostnames")
print("=" * 50)

config = {
    "config": {
        "ingress": [
            {
                "hostname": "portfolio.great-x-attach.xyz",
                "service": "http://localhost:80",
                "originRequest": {}
            },
            {
                "hostname": "great-x-attach.xyz",
                "service": "http://localhost:80",
                "originRequest": {}
            },
            {
                "hostname": "ytdownloader.great-x-attach.xyz",
                "service": "http://localhost:80",
                "originRequest": {}
            },
            {
                "service": "http_status:404"
            }
        ]
    }
}

resp = requests.put(API_URL, headers=headers, json=config)
print(f"Status: {resp.status_code}")
print(f"Response: {json.dumps(resp.json(), indent=2)}")

if resp.status_code == 200 and resp.json().get("success"):
    print("\n✅ Public hostnames configured successfully!")
    print("   - portfolio.great-x-attach.xyz -> http://localhost:80")
    print("   - great-x-attach.xyz -> http://localhost:80")
    print("   - ytdownloader.great-x-attach.xyz -> http://localhost:80")
else:
    print("\n❌ Failed. Check the error above.")
