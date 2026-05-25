import http.cookiejar
import json
import random
import string
import urllib.error
import urllib.parse
import urllib.request

base = 'http://localhost/lfjproj/backend/public'
jar = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))


def fetch(path, method='GET', data=None, headers=None):
    url = base + path
    hdrs = {'User-Agent': 'ValidationBot/1.0'}
    if headers:
        hdrs.update(headers)
    body_bytes = None
    if data is not None:
        if isinstance(data, dict):
            body_bytes = json.dumps(data).encode('utf-8')
            hdrs['Content-Type'] = 'application/json'
    req = urllib.request.Request(url, data=body_bytes, headers=hdrs, method=method)
    try:
        with opener.open(req, timeout=30) as r:
            body = r.read().decode('utf-8', 'replace')
            print('URL', url, 'STATUS', r.status)
            print('HEADERS', dict(r.headers))
            print('BODY', body[:800])
            return r.status, r.headers, body
    except urllib.error.HTTPError as e:
        body = e.read().decode('utf-8', 'replace')
        print('URL', url, 'HTTPERROR', e.code)
        print('BODY', body[:800])
        return e.code, e.headers, body
    except Exception as e:
        print('URL', url, 'ERROR', type(e).__name__, e)
        raise


print('Fetch CSRF cookie...')
status, headers, body = fetch('/sanctum/csrf-cookie')
print('cookie jar', [(c.name, c.value) for c in jar])

email = 'qa_test_' + ''.join(random.choice(string.ascii_lowercase + string.digits) for _ in range(8)) + '@example.com'
register_data = {
    'name': 'QA Test',
    'email': email,
    'password': 'Test1234!',
    'password_confirmation': 'Test1234!'
}

xsrf = None
for c in jar:
    if c.name == 'XSRF-TOKEN':
        xsrf = urllib.parse.unquote(c.value)
print('XSRF present', bool(xsrf))
headers = {'X-XSRF-TOKEN': xsrf} if xsrf else {}

print('Registering user', email)
status, headers, body = fetch('/api/register', method='POST', data=register_data, headers=headers)

print('Invalid login attempt')
status, headers, body = fetch('/api/login', method='POST', data={'email': email, 'password': 'WrongPass1!'}, headers=headers)

print('Correct login attempt')
status, headers, body = fetch('/api/login', method='POST', data={'email': email, 'password': 'Test1234!'}, headers=headers)

print('Fetch authenticated user')
status, headers, body = fetch('/api/user', method='GET', headers=headers)

print('Logout')
status, headers, body = fetch('/api/logout', method='POST', headers=headers)

print('Fetch user after logout')
status, headers, body = fetch('/api/user', method='GET', headers=headers)
