import requests
import json


class API:
    def __init__(
            self,
            url='http://bram-ubuntu.local/api',
            path='/get_devices',
            username='api',
            password='test1234',
            params=None,
            json=None):
        self.url = url
        self.path = path
        self.username = username
        self.password = password
        self.params = params
        self.json = json

    def get(self):
        try:
            response = requests.get(
                self.url + self.path,
                auth=(self.username, self.password),
                params=self.params)
        except:
            response = None
        return response

    def post(self):
        try:
            response = requests.post(
                self.url + self.path,
                auth=(self.username, self.password),
                data=self.params,
                json=self.json)
        except:
            response = None
        return response
