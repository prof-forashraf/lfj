import urllib.request
import urllib.parse
import http.cookiejar
from urllib.error import HTTPError, URLError

BASE = 'http://127.0.0.1:8000'

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

print('GET /sanctum/csrf-cookie...')
req = urllib.request.Request(f'{BASE}/sanctum/csrf-cookie', headers={
    'Accept': 'application/json',
})
try:
    with opener.open(req, timeout=15) as resp:
        print('CSRF status', resp.status)
        print('Cookies:', [(cookie.name, cookie.value) for cookie in cj])
except Exception as e:
    print('CSRF error', e)
    raise

xsrf_cookie = None
for cookie in cj:
    if cookie.name == 'XSRF-TOKEN':
        xsrf_cookie = urllib.parse.unquote(cookie.value)
        break

if not xsrf_cookie:
    raise SystemExit('Missing XSRF-TOKEN cookie')

print('XSRF token:', xsrf_cookie[:50], '...')

login_data = urllib.parse.urlencode({
    'email': 'admin@latestfashionjewellery.com',
    'password': 'admin123',
    'remember': 'true',
}).encode('utf-8')

login_req = urllib.request.Request(
    f'{BASE}/api/login',
    data=login_data,
    headers={
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-XSRF-TOKEN': xsrf_cookie,
        'Referer': BASE + '/',
    },
    method='POST'
)

try:
    with opener.open(login_req, timeout=15) as resp:
        body = resp.read().decode('utf-8', errors='replace')
        print('LOGIN status', resp.status)
        print('LOGIN headers:', dict(resp.getheaders()))
        print('LOGIN body:', body)
except HTTPError as e:
    print('LOGIN status', e.code)
    print('LOGIN body', e.read().decode('utf-8', errors='replace'))
    raise
except Exception as e:
    print('LOGIN error', e)
    raise

print('\nGET /admin with cookies...')
admin_req = urllib.request.Request(
    f'{BASE}/admin',
    headers={
        'Accept': 'text/html',
        'Referer': BASE + '/',
    }
)
try:
    with opener.open(admin_req, timeout=15) as resp:
        print('ADMIN status', resp.status)
        print('ADMIN content-type', resp.getheader('Content-Type'))
        body = resp.read(400).decode('utf-8', errors='replace')
        print(body)
except HTTPError as e:
    print('ADMIN status', e.code)
    print('ADMIN body', e.read(400).decode('utf-8', errors='replace'))
except Exception as e:
    print('ADMIN error', e)
