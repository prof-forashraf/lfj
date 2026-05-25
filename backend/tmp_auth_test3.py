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


def get_xsrf():
    for c in jar:
        if c.name == 'XSRF-TOKEN':
            return urllib.parse.unquote(c.value)
    return None


def print_cookies(label):
    print(label, [(c.name, c.value, c.path, c.domain) for c in jar])


def fetch(path, method='GET', data=None, extra_headers=None):
    url = base + path
    hdrs = {'User-Agent': 'ValidationBot/1.0'}
    if extra_headers:
        hdrs.update(extra_headers)
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
            print('HEADERS', {k: v for k, v in r.getheaders() if k.lower() != 'set-cookie'})
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


print('CSRF fetch')
fetch('/sanctum/csrf-cookie')
print_cookies('after csrf')

email = 'qa_test_' + ''.join(random.choice(string.ascii_lowercase + string.digits) for _ in range(8)) + '@example.com'
register_data = {'name': 'QA Test', 'email': email, 'password': 'Test1234!', 'password_confirmation': 'Test1234!'}
xsrf = get_xsrf()
print('register xsrf', xsrf)
fetch('/api/register', method='POST', data=register_data, extra_headers={'X-XSRF-TOKEN': xsrf})
print_cookies('after register')

xsrf = get_xsrf()
print('login xsrf', xsrf)
fetch('/api/login', method='POST', data={'email': email, 'password': 'WrongPass1!'}, extra_headers={'X-XSRF-TOKEN': xsrf})
print_cookies('after invalid login')

xsrf = get_xsrf()
print('login xsrf again', xsrf)
fetch('/api/login', method='POST', data={'email': email, 'password': 'Test1234!'}, extra_headers={'X-XSRF-TOKEN': xsrf})
print_cookies('after login')

fetch('/api/user', method='GET')
print_cookies('after api user')

xsrf = get_xsrf()
print('logout xsrf', xsrf)
fetch('/api/logout', method='POST', extra_headers={'X-XSRF-TOKEN': xsrf})
print_cookies('after logout')

fetch('/api/user', method='GET')
