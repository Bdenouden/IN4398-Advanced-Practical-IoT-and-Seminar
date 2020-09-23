import requests


class API:
    url = 'http://bram-ubuntu.local/api'
    path = '/get_devices'
    params = {'API_KEY': 123}

    def __init__(
            self,
            url='http://bram-ubuntu.local/api',
            path='/get_devices',
            username='api',
            password='test1234',
            params=None):
        self.url = url
        self.path = path
        self.username = username
        self.password = password
        self.params = params

    def get(self):
        response = requests.get(
            self.url + self.path,
            auth=(self.username, self.password),
            params=self.params)

        return response

    def post(self):
        response = requests.post(
            self.url + self.path,
            auth=(self.username, self.password),
            data=self.params)

        return response



